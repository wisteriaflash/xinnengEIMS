<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/typeunit.class.admin.php");
$userChannel = $cuserLogin->getUserChannel();
if(!isset($exallct)) $exallct = 'all';
include DedeInclude('templets/catalog_main.htm');

?>