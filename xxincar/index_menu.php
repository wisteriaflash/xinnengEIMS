<?php
//require(dirname(__FILE__).'/config.php');
require(DEDEADMIN.'/inc/inc_menu_func.php');
$openitem = (empty($openitem) ? 1 : $openitem);
if($cuserLogin->userType < 10)
{
	require(DEDEADMIN.'/inc/inc_menu_public.php');
	include DedeInclude('templets/index_menu1.htm');
}
else
{
	require(DEDEADMIN.'/inc/inc_menu.php');
	include DedeInclude('templets/index_menu2.htm');
}
?>
