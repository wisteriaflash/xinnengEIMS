<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('info_Lang');
if(empty($dopost)) $dopost = '';
$id = isset($id) ? ereg_replace('[^0-9]','',$id) : 0;
$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL']) ? "job_main.php" : $_COOKIE['ENV_GOBACK_URL'];

if($dopost=='delete')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__jobs` where id='$id' ");
	ShowMsg("成功删除一条招聘信息！",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=='delall')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__jobs` where id in($aids) ");
	ShowMsg("成功删除选定的招聘信息！",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=='saveedit')
{
	$sendtime = GetMkTime($sendtime);
	$exptime = GetMkTime($exptime);
	$query = "Update `#@__jobs`
	 set 
	 `jobname`='$jobname',
	 `neednum`='$neednum',
	 `needpart`='$needpart',
	 `linkman`='$linkman',
	 `linktel`='$linktel',
	 `email`='$email',
	 `address`='$address',
	 `sendtime`='$sendtime',
	 `exptime`='$exptime',
	 `jobneed`='$jobneed',
	 `msg`='$msg'
	 where id='$id' ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("成功更改一条招聘信息！",$ENV_GOBACK_URL);
	exit();
}
$row = $dsql->GetOne("Select * From `#@__jobs` where id='$id'");
include DedeInclude('templets/job_edit.htm');
?>