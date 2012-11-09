<?php
require_once(dirname(__FILE__)."/config.php");
if(empty($dopost))
{
	$dopost = '';
}
if(empty($fmdo))
{
	$fmdo = '';
}

/*********************
function Case_user()
*******************/
if($fmdo=='user')
{

	//检查用户名是否存在
	if($dopost=="checkuser")
	{
		AjaxHead();
		$msg = '';
		$uid = trim($uid);
		if($cktype==0)
		{
			$msgtitle = GetLang('uname');
		}
		else
		{
			$msgtitle = GetLang('username');
		}
		$msg = CheckUserID($uid,$msgtitle);
		if($msg=='ok')
		{
			$msg = "<font color='#4E7504'><b>√{$msgtitle}".GetLang('canuse')."</b></font>";
		}
		else
		{
			$msg = "<font color='red'><b>×{$msg}</b></font>";
		}
		echo $msg;
		exit();
	}

	//检查email是否存在
	else  if($dopost=="checkmail")
	{
		AjaxHead();
		if($cfg_md_mailtest=='N')
		{
			$msg = "<font color='#4E7504'><b>√".GetLang('canuse')."</b></font>";
		}
		else
		{
			if(!eregi("^[0-9a-z][a-z0-9\.-]{1,}@[a-z0-9-]{1,}[a-z0-9]\.[a-z\.]{1,}[a-z]$",$email))
			{
				$msg = "<font color='#4E7504'><b>×Email".GetLang('typeerr')."</b></font>";
			}
			else
			{
				 $row = $dsql->GetOne("Select mid From `#@__member` where email like '$email' limit 1");
				 if(!is_array($row)) {
					 $msg = "<font color='#4E7504'><b>√".GetLang('canuse')."</b></font>";
				 }
				 else {
					 $msg = "<font color='red'><b>×Email".GetLang('hasuse')."</b></font>";
				 }
			}
		}
		echo $msg;
		exit();
	}

	//引入注册页面
	else if($dopost=="regnew")
	{

		require_once(dirname(__FILE__)."/reg_new.php");
		exit();
	}
}

/*********************
function login()
*******************/
else if($fmdo=='login')
{

	//用户登录
	if($dopost=="login")
	{
		if(!isset($vdcode))
		{
			$vdcode = '';
		}
		$svali = GetCkVdValue();
		if(strtolower($vdcode)!=$svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg(GetLang('err_vdcode'), '-1');
			exit();
		}
		if(CheckUserID($userid,'',false)!='ok')
		{
			ShowMsg( str_replace('#userid#', $userid, GetLang('err_loginid2')), '-1');
			exit();
		}
		if($pwd=='')
		{
			ShowMsg( GetLang('password').' '.GetLang('cannotempty'), '-1' ,0,2000);
			exit();
		}

		//检查帐号
		$rs = $cfg_ml->CheckUser($userid,$pwd);
		if($rs==0)
		{
			ShowMsg( GetLang('err_usernotex'), '-1', 0, 2000);
			exit();
		}
		else if($rs==-1) {
			ShowMsg( GetLang('err_password'), '-1', 0, 2000);
			exit();
		}
		else if($rs==-2) {
			ShowMsg( GetLang('err_admin'),"-1",0,2000);
			exit();
		}
		else
		{
			if(empty($gourl) || eregi("action|_do",$gourl))
			{
				ShowMsg( GetLang('msg_loginok'), 'index.php', 0, 2000);
			}
			else
			{
				ShowMsg( GetLang('msg_loginok'), $gourl, 0, 2000);
			}
			exit();
		}
	}

	//退出登录
	else if($dopost=="exit")
	{
		setcookie('sellang', '', time()-(3600 * 24 * 30), '/');
		$cfg_ml->ExitCookie();
		ShowMsg( GetLang('msg_loginoutok'), 'index.php', 0, 2000);
		exit();
	}
}
else
{
	ShowMsg( GetLang('msg_notback'), 'index.php');
}

?>