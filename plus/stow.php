<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC.'/userlang.inc.php');

$aid = ( isset($aid) && is_numeric($aid) ) ? $aid : 0;
if($aid==0)
{
	ShowMsg( GetLang('msg_errdoc'), 'javascript:window.close();');
	exit();
}

require_once(DEDEINC."/memberlogin.class.php");
$ml = new MemberLogin();

if($ml->M_ID==0)
{
	ShowMsg( GetLang('msg_onlymember'), 'javascript:window.close();');
	exit();
}


//读取文档信息
$arcRow = GetOneArchive($aid);
if($arcRow['aid']=='')
{
	ShowMsg( GetLang('msg_errdoc'), 'javascript:window.close();');
	exit();
}
extract($arcRow, EXTR_SKIP);

$addtime = time();
$row = $dsql->GetOne("Select * From `#@__member_stow` where aid='$aid' And mid='{$ml->M_ID}' ");

if(!is_array($row))
{
	$dsql->ExecuteNoneQuery(" INSERT INTO `#@__member_stow`(mid,aid,title,addtime) VALUES ('".$ml->M_ID."','$aid','".addslashes($arctitle)."','$addtime'); ");
}

//更新用户统计
$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__member_stow` WHERE `mid`='{$ml->M_ID}' ");
$dsql->ExecuteNoneQuery("UPDATE #@__member_tj SET `stow`='$row[nums]' WHERE `mid`='".$ml->M_ID."'");

ShowMsg( GetLang('msg_succaddstow'), 'javascript:window.close();');
?>