<?php
if(!defined('DEDEINC'))
{
   exit("Request Error!");
}
require_once(DEDEINC.'/channelunit.func.php'); 

function plus_langlist(&$atts,&$refObj,&$fields)
{
	global $dsql,$_vars,$cfg_df_lang,$userLang;
    $attlist = "row=100,col=1,currentstyle=";
    FillAtts($atts,$attlist);
    FillFields($atts,$fields,$refObj);
	extract($atts, EXTR_OVERWRITE);

	$lang = (isset($userLang) ? $userLang : $cfg_df_lang);
	//$lang = "en";
	$rearray = array();
	
	$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  From `#@__arctype` where reid=0 order by sortrank asc limit 0,$row";
	
	$dsql->Execute('mb',$sql);
	while($row = $dsql->GetArray('mb'))
    {
		 preg_match_all("/(.*)\(([a-zA-Z0-9-]*)\)/", $row['typename'], $regArr);
		 $row['langname'] = $regArr[1][0];
		 $row['typelink'] = GetOneTypeUrlA($row);
		 $rearray[] = $row;
	}
	return $rearray;	
}
?>