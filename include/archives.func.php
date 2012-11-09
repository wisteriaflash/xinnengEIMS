<?php

if(!defined('DEDEINC'))
{
	exit("Request Error!");
}


function GetIndexKey($arcrank,$typeid,$sortrank=0,$channelid=1,$senddate=0,$mid=1)
{
	global $dsql,$senddate;
	if(empty($senddate)) $senddate = time();
	if(empty($sortrank)) $sortrank = $senddate;
	$iquery = "
	  INSERT INTO `#@__arctiny` (`arcrank`,`typeid`,`channel`,`senddate`, `sortrank`, `mid`)
	  VALUES ('$arcrank','$typeid', '$channelid','$senddate', '$sortrank', '$mid') ";
	$dsql->ExecuteNoneQuery($iquery);
	$aid = $dsql->GetLastID();
	return $aid;
}

// 更新微表key及Tag
function UpIndexKey($id,$arcrank,$typeid,$sortrank=0,$tags='')
{
	global $dsql;
	$addtime = time();
	$query = " Update `#@__arctiny` set `arcrank`='$arcrank', `typeid`='$typeid',`sortrank`='$sortrank' where id = '$id' ";
	$dsql->ExecuteNoneQuery($query);
}

?>