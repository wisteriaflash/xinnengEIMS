<?php
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie('ENV_GOBACK_URL',$dedeNowurl,time()+3600,'/');

$sql = "Select id,lang,cid,eid From `#@__mylang` order by id desc";

$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/lang_main.htm");
$dlist->SetSource($sql);
$dlist->display();

?>