<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
include_once(DEDEINC.'/arc.datalist.class.php');
require_once(DEDEINC.'/userlang.inc.php');

$cfg_df_style = ereg_replace("[/:\\]", '', $cfg_df_style);
$tmpfile = DEDETEMPDIR.'/'.$cfg_df_style.'/'.$lang.'/jobs_list.htm';
$tmpfiledf = DEDETEMPDIR.'/'.$cfg_df_style.'/en/jobs_list.htm';
if(!file_exists($tmpfile))
{
	if(file_exists($tmpfiledf)) $tmpfile = $tmpfiledf;
	else exit(' Template not found! ');
}

$querystring = "select * From `#@__jobs` where lang='$lang' order by id desc";
$dlist = new DataList($querystring, $tmpfile);
$dlist->SetParameter('lang', $lang);
$dlist->Display();
exit();
?>