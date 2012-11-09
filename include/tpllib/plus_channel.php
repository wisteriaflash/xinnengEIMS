<?php
if(!defined('DEDEINC'))
{
   exit("Request Error!");
}
require_once(DEDEINC.'/channelunit.func.php'); 

function plus_channel(&$atts,&$refObj,&$fields)
{
	global $dsql,$_vars,$cfg_df_lang,$userLang;
    $attlist = "typeid=0,reid=0,row=100,col=1,type=top,get=all,currentstyle=";
    FillAtts($atts,$attlist);
    FillFields($atts,$fields,$refObj);
	extract($atts, EXTR_OVERWRITE);

	$lang = (isset($userLang) ? $userLang : $cfg_df_lang);
	//$lang = "en";
	
	$reid = 0;
	$topid = 0;	
	
	if(empty($topid))
	{
		$row3 = $dsql->GetOne("Select * From `#@__arctype` where reid=0 And lang ='$lang' ");
		$topid = ($row3['topid']==0 ? $row3['id'] : $row3['topid']);
	}
    $rearray = array();
	//echo $type;
	if($get=='index')
	{
		$sql = "Select * From `#@__arctype` where id='$topid' ";
	}
	else if($type=='top')
	{
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  From `#@__arctype` where reid=$topid And ishidden<>1 order by sortrank asc limit 0,$row";
	}
	$dsql->Execute('mb',$sql);
	while($row = $dsql->GetArray('mb'))
    {
		 $row['typelink'] = GetOneTypeUrlA($row);
		 $row['indexname'] = GetLang('indexname');
		 $rearray[] = $row;
	}
	return $rearray;	
}
?>