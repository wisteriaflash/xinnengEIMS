<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
if(!isset($dopost))
{
	$dopost = '';
}
$row=$dsql->GetOne("select  * from `#@__member` where mid='".$cfg_ml->M_ID."'");
$face = $row['face'];
if($dopost=='save')
{
	$svali = GetCkVdValue();
  $pwd2 = $addupquery = '';
	$sex = empty($sex) ? '' : $sex;
	if(strtolower($vdcode) != $svali || $svali=='')
	{
		ResetVdValue();
		ShowMsg( GetLang('err_vdcode'),'-1');
		exit();
	}
	if(!is_array($row) || $row['pwd']!=md5($oldpwd))
	{
		ShowMsg( GetLang('needoldpassword'),'-1');
		exit();
	}
	if($userpwd!=$userpwdok)
	{
		ShowMsg( GetLang('newoldpassowrddiffer'),'-1');
		exit();
	}
	
	if($userpwd=='')
	{
		$pwd = $row['pwd'];
	}
	else
	{
		$pwd = md5($userpwd);
		$pwd2 = substr(md5($userpwd),5,20);
	}
	
	//修改Email
	if($email != $row['email'])
	{
			if(!eregi("^[0-9a-z][a-z0-9\._-]{1,}@[a-z0-9-]{1,}[a-z0-9]\.[a-z\.]{1,}[a-z]$",$email))
			{
				ShowMsg( GetLang('err_email'),'-1');
				exit();
			}
			else
			{
				$addupquery .= ",email='$email'";
			}
	}
	
	$query1 = "Update `#@__member` set pwd='$pwd',sex='$sex',email='$email',lang='$slang',uname='$uname',
	        company='$company',mobile='$mobile',tel='$tel',fax='$fax',address='$address' where mid='".$cfg_ml->M_ID."' ";
	$dsql->ExecuteNoneQuery($query1);

	//如果是管理员，修改其后台密码
	if($cfg_ml->fields['matt']==10 && $pwd2!='')
	{
		$query2 = "Update `#@__admin` set pwd='$pwd2' where id='".$cfg_ml->M_ID."' ";
		$dsql->ExecuteNoneQuery($query2);
	}
	ShowMsg( GetLang('msg_sucinfo'), 'myinfo.php', 0, 5000);
	exit();
}
include(DEDEMEMBER."/templets/myinfo.htm");
?>