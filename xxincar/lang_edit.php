<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('info_Lang');
if(empty($dopost)) $dopost = '';
$id = isset($id) ? ereg_replace('[^0-9]','',$id) : 0;
$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL']) ? "lang_main.php" : $_COOKIE['ENV_GOBACK_URL'];

if($dopost=='delete')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__mylang` where id='$id' ");
	ShowMsg("成功删除一条自定义资料！",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=='delall')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__mylang` where id in($aids) ");
	ShowMsg("成功删除选定的自定义资料！",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=='saveedit')
{
	$query = "Update `#@__mylang`
	 set cid='$cid',
	 eid='$eid',
	 langtxt='$langtxt'
	 where id='$id' ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("成功更改一条自定义资料！",$ENV_GOBACK_URL);
	exit();
}
$row = $dsql->GetOne("Select * From `#@__mylang` where id='$id'");
include DedeInclude('templets/lang_edit.htm');
?>