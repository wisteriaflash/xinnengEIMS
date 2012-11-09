<?php
//语言选择
if(empty($lang))
{
	if(isset($cfg_ml) && !empty($cfg_ml->fields['lang']))
  {
		$lang = $cfg_ml->fields['lang'];
	}
	else
	{
		if(isset($_COOKIE['sellang'])) $lang = $_COOKIE['sellang'];
		else $lang = $cfg_df_lang;
	}
}
$lang = eregi_replace('[^a-z0-9]', '', $lang);
if(empty($lang)) $lang = $cfg_df_lang;
else setcookie('sellang', $lang, time()+(3600 * 24 * 30), '/');
$userLang = $lang;
?>