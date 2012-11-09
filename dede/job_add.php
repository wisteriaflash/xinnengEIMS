<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('info_Lang');
if(empty($dopost)) $dopost = '';

if($dopost=='save')
{
	$sendtime = GetMkTime($sendtime);
	$exptime = GetMkTime($exptime);
	if(empty($copyall))
	{
		$query = "Insert into `#@__jobs`(`jobname`,`lang`,`neednum`,`needpart`,`linkman`,`linktel`,`email`,`address`,`sendtime`,`exptime`,`jobneed`,`msg`) 
				Values('$jobname','$lang','$neednum','$needpart','$linkman','$linktel','$email','$address','$sendtime','$exptime','$jobneed','$msg')";
		$dsql->ExecuteNoneQuery($query);
	}
	else
	{
			foreach($lang_has_arr as $lang=>$lname)
    	{
       	$query = "Insert into `#@__jobs`(`jobname`,`lang`,`neednum`,`needpart`,`linkman`,`linktel`,`email`,`address`,`sendtime`,`exptime`,`jobneed`,`msg`) 
						Values('$jobname','$lang','$neednum','$needpart','$linkman','$linktel','$email','$address','$sendtime','$exptime','$jobneed','$msg')";
				$dsql->ExecuteNoneQuery($query);
    	}
	}
	ShowMsg('成功增加一条招聘信息！', 'job_main.php');
	exit();
}

$nowdate = MyDate('Y-m-d', time());
$monthdate = MyDate('Y-m-d', AddDay(time(), 30));
include DedeInclude('templets/job_add.htm');

?>