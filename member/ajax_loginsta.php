<?php
require_once(dirname(__FILE__)."/config.php");
AjaxHead();
if($myurl == '')
{
	exit('');
}
$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__member_stow` WHERE mid='{$cfg_ml->M_ID}'");
$uid  = $cfg_ml->M_LoginID;
$face = $cfg_ml->fields['face'] == '' ? $GLOBALS['cfg_memberurl'].'/images/nopic.gif' : $cfg_ml->fields['face'];
if(!empty($cfg_ml->fields['lang']))
{
	$lang = $cfg_ml->fields['lang'];
}
?>
<span class="fLeft"><?php echo GetLang('log_hello');?>，<i class="aBlack mR5"><?php echo $cfg_ml->M_UserName; ?></i><?php echo GetLang('log_welcome');?></span>
    <ul id="userMenu" class="fLeft fLeftChild">
      <li><a class="person" href="<?php echo $cfg_memberurl; ?>/index.php" title="<?php echo GetLang('log_ucenter');?>"><?php echo GetLang('log_ucenter');?></a></li>
      <li><a class="favorite" href="<?php echo $cfg_memberurl; ?>/mystow.php" title="<?php echo GetLang('log_fav');?>"><?php echo GetLang('log_fav');?>(<?php echo $row['nums'];?>)</a></li>
      <li><a class="exit"  href="<?php echo $cfg_memberurl; ?>/index_do.php?fmdo=login&dopost=exit" style="padding-left: 0px;"><?php echo GetLang('log_exit');?></a> </li>
    </ul>