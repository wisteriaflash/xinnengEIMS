<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('info_Lang');
if(empty($dopost)) $dopost = '';

if($dopost=='save')
{
	if(empty($copyall))
	{
		$query = "Insert into `#@__mylang`(`lang`,`cid`,`eid`,`langtxt`) Values('$lang','$cid','$eid','$langtxt')";
		$dsql->ExecuteNoneQuery($query);
	}
	else
	{
			foreach($lang_has_arr as $lang=>$lname)
    	{
       	$query = "Insert into `#@__mylang`(`lang`,`cid`,`eid`,`langtxt`) Values('$lang','$cid','$eid','$langtxt')";
				$dsql->ExecuteNoneQuery($query);
    	}
	}
	ShowMsg('成功增加一条自定义资料！', 'lang_main.php');
	exit();
}

include DedeInclude('templets/lang_add.htm');

?>