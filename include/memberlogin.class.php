<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}

//检查用户名的合法性
function CheckUserID($uid,$msgtitle='Login ID',$ckhas=true)
{
	global $cfg_mb_notallow,$cfg_mb_idmin,$cfg_md_idurl,$cfg_soft_lang,$dsql;
	if($cfg_mb_notallow != '')
	{
		$nas = explode(',',$cfg_mb_notallow);
		if(in_array($uid,$nas))
		{
			return $msgtitle.GetLang('err_uid3');
		}
	}
	if($cfg_md_idurl=='Y' && eregi("[^a-z0-9]",$uid))
	{
		return $msgtitle.GetLang('err_uid2');
	}

	if($cfg_soft_lang=='utf-8')
	{
		$ck_uid = utf82gb($uid);
	}
	else
	{
		$ck_uid = $uid;
	}

	for($i=0;isset($ck_uid[$i]);$i++)
	{
			if(ord($ck_uid[$i]) > 0x80)
			{
				if(isset($ck_uid[$i+1]) && ord($ck_uid[$i+1])>0x40)
				{
					$i++;
				}
				else
				{
					return $msgtitle.GetLang('err_uid');
				}
			}
			else
			{
				if(eregi("[^0-9a-z@\.-]",$ck_uid[$i]))
				{
					return $msgtitle.' '.GetLang('err_symbols');
				}
			}
	}
	if($ckhas)
	{
		$row = $dsql->GetOne("Select * From `#@__member` where userid like '$uid' ");
		if(is_array($row)) return $msgtitle.GetLang('hasuse');
	}
	return 'ok';
}


//网站会员登录类
class MemberLogin
{
	var $M_ID;
	var $M_LoginID;
	var $M_UserName;
	var $M_Rank;
	var $M_LoginTime;
	var $M_KeepTime;
	var $fields;
	var $isAdmin;

	//php5构造函数
	function __construct($kptime = -1)
	{
		global $dsql;
		if($kptime==-1)
		{
			$this->M_KeepTime = 3600 * 24 * 7;
		}
		else
		{
			$this->M_KeepTime = $kptime;
		}
		$this->M_ID = $this->GetNum(GetCookie("DedeUserID"));
		$this->M_LoginTime = GetCookie("DedeLoginTime");
		$this->fields = array();
		$this->isAdmin = false;
		if(empty($this->M_ID))
		{
			$this->ResetUser();
		}
		else
		{
			$this->M_ID = intval($this->M_ID);
			$this->fields = $dsql->GetOne("Select * From `#@__member` where mid='{$this->M_ID}' ");
			if(is_array($this->fields))
			{
				//间隔一小时更新一次用户登录时间
				if(time() - $this->M_LoginTime > 3600)
				{
					$dsql->ExecuteNoneQuery("update `#@__member` set logintime='".time()."',loginip='".GetIP()."' where mid='".$this->fields['mid']."';");
					PutCookie("DedeLoginTime",time(),$this->M_KeepTime);
				}
				$this->M_LoginID = $this->fields['userid'];
				$this->M_UserName = $this->fields['uname'];
				$this->M_Rank = $this->fields['rank'];
				if($this->fields['matt']==10)
				{
					$this->isAdmin = true;
				}
			}
			else
			{
				$this->ResetUser();
			}
		}
	}

	function MemberLogin($kptime = -1)
	{
		$this->__construct($kptime);
	}

	//退出cookie的会话
	function ExitCookie()
	{
		$this->ResetUser();
	}

	//验证用户是否已经登录
	function IsLogin()
	{
		if($this->M_ID > 0) return true;
		else return false;
	}

	//检测用户上传空间
	function GetUserSpace()
	{
		global $dsql;
		$uid = $this->M_ID;
		$row = $dsql->GetOne("select sum(filesize) as fs From `#@__uploads` where mid='$uid'; ");
		return $row['fs'];
	}

	function CheckUserSpace()
	{
		global $cfg_mb_max;
		$uid = $this->M_ID;
		$hasuse = $this->GetUserSpace();
		$maxSize = $cfg_mb_max * 1024 * 1024;
		if($hasuse >= $maxSize)
		{
			ShowMsg('你的空间已满，不允许上传新文件！','-1');
			exit();
		}
	}

	//重置用户信息
	function ResetUser()
	{
		$this->fields = '';
		$this->M_ID = 0;
		$this->M_LoginID = '';
		$this->M_Rank = 0;
		$this->M_UserName = "";
		$this->M_LoginTime = 0;
		DropCookie('DedeUserID');
		DropCookie('DedeLoginTime');
	}

	//获取整数值
	function GetNum($fnum){
		$fnum = ereg_replace("[^0-9\.]",'',$fnum);
		return $fnum;
	}

	//用户登录
	//把登录密码转为指定长度md5数据
	function GetEncodePwd($pwd)
	{
		global $cfg_mb_pwdtype;
		if(empty($cfg_mb_pwdtype)) $cfg_mb_pwdtype = '32';
		switch($cfg_mb_pwdtype)
		{
			case 'l16':
				return substr(md5($pwd), 0, 16);
			case 'r16':
				return substr(md5($pwd), 16, 16);
			case 'm16':
				return substr(md5($pwd), 8, 16);
			default:
				return md5($pwd);
		}
	}
	
	//把数据库密码转为特定长度
	//如果数据库密码是明文的，本程序不支持
	function GetShortPwd($dbpwd)
	{
		global $cfg_mb_pwdtype;
		if(empty($cfg_mb_pwdtype)) $cfg_mb_pwdtype = '32';
		$dbpwd = trim($dbpwd);
		if(strlen($dbpwd)==16)
		{
			return $dbpwd;
		}
		else
		{
			switch($cfg_mb_pwdtype)
			{
				case 'l16':
					return substr($dbpwd, 0, 16);
				case 'r16':
					return substr($dbpwd, 16, 16);
				case 'm16':
					return substr($dbpwd, 8, 16);
				default:
					return $dbpwd;
			}
		}
	}
	
	function CheckUser(&$loginuser,$loginpwd)
	{
		global $dsql;

		//检测用户名的合法性
		$rs = CheckUserID($loginuser,'用户名',false);

		//用户名不正确时返回验证错误，原登录名通过引用返回错误提示信息
		if($rs!='ok')
		{
			$loginuser = $rs;
			return '0';
		}

		//matt=10 是管理员关连的前台帐号，为了安全起见，这个帐号只能从后台登录，不能直接从前台登录
		$row = $dsql->GetOne("Select mid,matt,pwd From `#@__member` where userid like '$loginuser' ");
		if(is_array($row))
		{
			if($this->GetShortPwd($row['pwd']) != $this->GetEncodePwd($loginpwd))
			{
				return -1;
			}
			else
			{
				//管理员帐号不允许从前台登录
				if($row['matt']==10) {
					return -2;
				}
				else {
					$this->PutLoginInfo($row['mid']);
					return 1;
				}
			}
		}
		else
		{
			return 0;
		}
	}

	//保存用户cookie
	function PutLoginInfo($uid)
	{
		$this->M_ID = $uid;
		$this->M_LoginTime = time();
		if($this->M_KeepTime > 0)
		{
			PutCookie('DedeUserID',$uid,$this->M_KeepTime);
			PutCookie('DedeLoginTime',$this->M_LoginTime,$this->M_KeepTime);
		}
		else
		{
			PutCookie('DedeUserID',$uid);
			PutCookie('DedeLoginTime',$this->M_LoginTime);
		}
	}

	//获得会员目前的状态
	function GetSta($dsql)
	{
		$sta = '';
		if($this->M_Rank==0)
		{
			$sta .= GetLang('yourrating').GetLang('normalmember');
		}
		else
		{
			$row = $dsql->GetOne("Select membername From `#@__arcrank` where rank='".$this->M_Rank."'");
			$sta .= GetLang('yourrating').$row['membername'];
		}
		return $sta;
	}
}
?>