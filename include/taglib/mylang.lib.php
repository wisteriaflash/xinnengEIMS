<?php
function lib_mylang(&$ctag, &$refObj)
{
	global $dsql,$cfg_df_lang;
	$attlist='eid|,id|,name|';
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	$sellang = $revalue = '';
	if(!empty($refObj->Lang)) $sellang = $refObj->Lang;
	else if(isset($refObj->dtp->tplLang)) $sellang = $refObj->dtp->tplLang;
	else $sellang = $cfg_df_lang;
	
	if(!empty($id)) {
		$query = "Select * From `#@__mylang` where id='$id' ";
	}
	if(!empty($name)) {
		$query = "Select * From `#@__mylang` where lang='$sellang' And eid='$name' ";
	}
	else {
		$query = "Select * From `#@__mylang` where lang='$sellang' And eid='$eid' ";
	}
	
	$row = $dsql->GetOne($query);
	if(!is_array($row)) {
		return $eid;
	}
	else {
		return $row['langtxt'];
	}
	
}
?>