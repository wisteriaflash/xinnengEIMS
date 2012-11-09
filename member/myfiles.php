<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",GetCurUrl(),time()+3600,"/");
$type = isset($type) ? trim($type) : '';


$sql = "Select * From `#@__archives` where arcrank>0 And arcrank<=".$cfg_ml->M_Rank." order by id desc";

$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetTemplate(DEDEMEMBER."/templets/myfiles.htm");
$dlist->SetSource($sql);
$dlist->Display();
?>