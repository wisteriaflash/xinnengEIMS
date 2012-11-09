<?php
function lib_handlang(&$ctag,&$refObj)
{
	global $cfg_df_lang;
	if(!empty($refObj->Lang)) return $refObj->Lang;
	else if(isset($refObj->dtp->tplLang)) return $refObj->dtp->tplLang;
	else return $cfg_df_lang;
}
?>