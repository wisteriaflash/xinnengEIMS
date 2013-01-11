<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/oxwindow.class.php");
if(empty($dopost))
{
	$dopost = '';
}
if(empty($fmdo))
{
	$fmdo = '';
}
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? 'member_main.php' : '';

/*----------------
function __DelMember()
删除会员
----------------*/
if($dopost=="delmember")
{
	CheckPurview('member_Del');
	if($fmdo=='yes')
	{
		$id = ereg_replace('[^0-9]','',$id);
		$safecodeok = substr(md5($cfg_cookie_encode.$randcode),0,24);
		if($safecodeok!=$safecode)
		{
			ShowMsg("请填写正确的安全验证串！","member_do.php?id={$id}&dopost=delmember");
			exit();
		}
		if(!empty($id))
		{
			//删除用户信息
			$rs = $dsql->ExecuteNoneQuery2("Delete From `#@__member` where mid='$id' And matt<>10 limit 1");
			if($rs > 0)
			{
				//删除用户相关数据
				$dsql->ExecuteNoneQuery("Delete From `#@__member_stow` where mid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_pms` where toid='$id' Or fromid='$id' ");
				$dsql->ExecuteNoneQuery("update `#@__archives` set mid='0' where mid='$id'");
			}
			else
			{
				ShowMsg("无法删除此会员，如果这个会员是管理员关连的ID，<br />必须先删除这个管理员才能删除此帐号！",$ENV_GOBACK_URL,0,3000);
				exit();
			}
		}
		ShowMsg("成功删除一个会员！",$ENV_GOBACK_URL);
		exit();
	}
	$randcode = mt_rand(10000,99999);
	$safecode = substr(md5($cfg_cookie_encode.$randcode),0,24);
	$wintitle = "会员管理-删除会员";
	$wecome_info = "<a href='".$ENV_GOBACK_URL."'>会员管理</a>::删除会员";
	$win = new OxWindow();
	$win->Init("member_do.php","js/blank.js","POST");
	$win->AddHidden("fmdo","yes");
	$win->AddHidden("dopost",$dopost);
	$win->AddHidden("id",$id);
	$win->AddHidden("randcode",$randcode);
	$win->AddHidden("safecode",$safecode);
	$win->AddTitle("你确实要删除(ID:".$id.")这个会员?");
	$win->AddMsgItem("安全验证串：<input name='safecode' type='text' id='safecode' size='16' style='width:200px' />&nbsp;(复制本代码： <font color='red'>$safecode</font> )","30");
	$winform = $win->GetWindow("ok");
	$win->Display();
}
/*----------------
function __UpRank()
会员升级
----------------*/
else if($dopost=="uprank")
{
	CheckPurview('member_Edit');
	if($fmdo=="yes")
	{
		$id = ereg_replace('[^0-9]','',$id);
		$membertype = ereg_replace('[^0-9]','',$membertype);
		$dsql->ExecuteNoneQuery("update `#@__member` set rank='$membertype' where mid='$id'");
		ShowMsg("成功更改一个会员等级！",$ENV_GOBACK_URL);
		exit();
	}
	$MemberTypes = '';
	$dsql->SetQuery("Select rank,membername From `#@__arcrank` where rank>0");
	$dsql->Execute();
	$MemberTypes[0] = "限制会员";
	while($row = $dsql->GetObject())
	{
		$MemberTypes[$row->rank] = $row->membername;
	}
	$options = "<select name='membertype' style='width:100px'>\r\n";
	foreach($MemberTypes as $k=>$v)
	{
		if($k!=$mtype)
		{
			$options .= "<option value='$k'>$v</option>\r\n";
		}
		else
		{
			$options .= "<option value='$k' selected>$v</option>\r\n";
		}
	}
	$options .= "</select>\r\n";
	$wintitle = "会员管理-会员升级";
	$wecome_info = "<a href='".$ENV_GOBACK_URL."'>会员管理</a>::会员升级";
	$win = new OxWindow();
	$win->Init("member_do.php","js/blank.js","POST");
	$win->AddHidden("fmdo","yes");
	$win->AddHidden("dopost",$dopost);
	$win->AddHidden("id",$id);
	$win->AddTitle("会员升级：");
	$win->AddItem("会员目前的等级：",$MemberTypes[$mtype]);
	$win->AddItem("开通等级：",$options);
	$winform = $win->GetWindow("ok");
	$win->Display();
}
/*----------------
function __Recommend()
推荐会员
----------------*/
else if($dopost=="recommend")
{
	CheckPurview('member_Edit');
	$id = ereg_replace("[^0-9]","",$id);
	if($matt==0)
	{
		$dsql->ExecuteNoneQuery("update `#@__member` set matt=1 where mid='$id' And matt<>10 limit 1");
		ShowMsg("成功设置一个会员推荐！",$ENV_GOBACK_URL);
		exit();
	}
	else
	{
		$dsql->ExecuteNoneQuery("update `#@__member` set matt=0 where mid='$id' And matt<>10 limit 1");
		ShowMsg("成功取消一个会员推荐！",$ENV_GOBACK_URL);
		exit();
	}
}
/*----------------
function __EditUser()
更改会员
----------------*/
else if($dopost=="edituser")
{
	CheckPurview('member_Edit');
	if(!isset($_POST['id']))
	{
		exit("Request Error!");
	}
	$pwd = '';
	if($pwd!='')
	{
		if($matt!=10) $pwd = ",pwd='".md5($pwd)."'";
	}
	$query = "update `#@__member` set
			email = '$email',
			uname = '$uname',
			sex = '$sex',
			matt = '$matt',
			rank = '$rank',
			company='$company',
			mobile='$mobile',
			tel='$tel',
			fax='$fax',
			address='$address'
			$pwd
			where mid='$id' ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("成功更改会员资料！",$ENV_GOBACK_URL);
	exit();
}
/*--------------
function __LoginCP()
登录会员的控制面板
----------*/
else if($dopost=="memberlogin")
{
	CheckPurview('member_Edit');
	PutCookie('DedeUserID',$id,1800);
	PutCookie('DedeLoginTime',time(),1800);
	header("location:../member/index.php");
}
elseif($dopost == "upoperations")
{
	$nid = ereg_replace('[^0-9,]','',ereg_replace('`',',',$nid));
	$nid = explode(',',$nid);
	if(is_array($nid))
	{
		foreach ($nid as $var)
		{
			$query = "update `#@__member_operation` set sta = '1' where aid = '$var'";
			$dsql->ExecuteNoneQuery($query);
			ShowMsg("设置成功！","member_operations.php");
			exit();
		}
	}
}
elseif($dopost == "okoperations")
{
	$nid = ereg_replace('[^0-9,]','',ereg_replace('`',',',$nid));
	$nid = explode(',',$nid);
	if(is_array($nid))
	{
		foreach ($nid as $var)
		{
			$query = "update `#@__member_operation` set sta = '2' where aid = '$var'";
			$dsql->ExecuteNoneQuery($query);
			ShowMsg("设置成功！","member_operations.php");
			exit();
		}
	}
}
?>