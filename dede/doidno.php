<?php
require_once (dirname(__FILE__) . "/../include/common.inc.php");
$authorizationtxt = DEDEROOT.'/data/admin/authorizationtxt.txt';
$authorizationtype = DEDEROOT.'/data/admin/authorizationtype.txt';
$fp = fopen($authorizationtxt,'w');
	flock($fp,3);
	fwrite($fp,$idno);
	fclose($fp);
$fp = fopen($authorizationtype,'w');
	flock($fp,3);
	fwrite($fp,$typename);
	fclose($fp);
	ShowMsg("激活成功！","index_body.php?".time());
	exit();
?>