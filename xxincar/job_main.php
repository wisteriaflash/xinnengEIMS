<?php
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie('ENV_GOBACK_URL',$dedeNowurl,time()+3600,'/');

$sql = "Select id,jobname,lang,neednum,needpart,linkman,linktel,msg,sendtime,exptime From `#@__jobs` order by id desc";

$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/job_main.htm");
$dlist->SetSource($sql);
$dlist->display();

?>