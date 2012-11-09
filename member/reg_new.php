<?php
require_once(dirname(__FILE__)."/config.php");
if($cfg_ml->IsLogin())
{
	ShowMsg( GetLang('err_haslogin'), 'index.php');
	exit();
}
if(!isset($dopost))
{
	$dopost = '';
}
//提交后提示
if($dopost=='regok')
{
	$svali = GetCkVdValue();
	if(strtolower($vdcode)!=$svali || $svali=='')
	{
		ResetVdValue();
		ShowMsg(GetLang('err_vdcode'), '-1');
		exit();
	}
	$userid = trim($userid);
	$pwd = trim($userpwd);
	$pwdc = trim($userpwdok);
	$rs = CheckUserID($userid,'Username');
	if($rs != 'ok')
	{
		ShowMsg($rs,"-1");
		exit();
	}
	if(strlen($userid) > 30 || strlen($uname) > 50)
	{
		ShowMsg(GetLang('err_username1'), '-1');
		exit();
	}
	if(strlen($userid) < $cfg_mb_idmin || strlen($pwd) < $cfg_mb_pwdmin)
	{
		ShowMsg(GetLang('err_username2'), '-1');
		exit();
	}
	if($pwdc!=$pwd)
	{
		ShowMsg(GetLang('err_passworderr'), '-1');
		exit();
	}	
	if(!eregi("^[0-9a-z][a-z0-9\.-]{1,}@[a-z0-9-]{1,}[a-z0-9]\.[a-z\.]{1,}[a-z]$",$email))
	{
		ShowMsg(GetLang('err_email'), '-1');
		exit();
	}
	if($cfg_md_mailtest=='Y')
	{
		$row = $dsql->GetOne("Select mid From `#@__member` where email like '$email' ");
		if(is_array($row))
		{
			ShowMsg(GetLang('err_loginid1'), '-1');
			exit();
		}
	}
	//检测用户名是否存在
	$row = $dsql->GetOne("Select mid From `#@__member` where userid like '$userid' ");
	if(is_array($row))
	{
		ShowMsg( str_replace('#userid#', $userid, GetLang('err_loginid2')), '-1');
		exit();
	}
	//处理其它变量
	$lang = eregi_replace('[^0-9a-z]','',$slang);
	$uname  = cn_substrR(HtmlReplace($uname,1), 50);
  $company  = cn_substrR(HtmlReplace($company,1), 100);
  $tel  = cn_substrR(HtmlReplace($tel,1), 50);
  $fax  = cn_substrR(HtmlReplace($fax,1), 50);
  $mobile  = cn_substrR(HtmlReplace($mobile,1), 50);
  $address  = cn_substrR(HtmlReplace($address,1), 250);
  $sex  = ereg_replace("[^0-9]",'',$sex);
	$jointime = time();
	$logintime = time();
	$joinip = GetIP();
	$loginip = GetIP();
	$pwd = md5($userpwd);
	$inQuery = "INSERT INTO `#@__member` (`userid` ,`pwd` ,`uname` ,`sex` ,`rank`  ,
	`email` , `lang` ,`company`,`mobile`,`tel`,`fax`, `address` ,`matt` ,`face` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
   VALUES ('$userid','$pwd','$uname','$sex','10',
   '$email', '$lang' ,'$company', '$mobile', '$tel', '$fax', '$address','0','','$jointime','$joinip','$logintime','$loginip'); ";
	if($dsql->ExecuteNoneQuery($inQuery))
	{
		$mid = $dsql->GetLastID();
		$ml = new MemberLogin(7*3600);
		$rs = $ml->CheckUser($userid, $userpwd);
		ShowMsg( GetLang('msg_ok1'), 'index.php', 0, 2000);
		exit();
	}
	else
	{
		echo $dsql->GetError();
		exit();
	}
}

require_once(DEDEMEMBER."/templets/reg-new.htm");
?>