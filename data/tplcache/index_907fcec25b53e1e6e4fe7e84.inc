<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo GetLang('membercenter'); ?>-<?php echo $cfg_webname; ?></title>
<?php include(DEDEMEMBER."/templets/head_inc.htm"); ?>
<script type="text/javascript">
$(function() {
	$("#navigation .sort").click(function(){
		$(this).next(".menu").toggleClass("invisible");
		$(this).toggleClass("hover");
	})
})
</script>
</head>
<body>
<?php include(DEDEMEMBER."/templets/head.htm"); ?>
<div class="wrapper mT10">
  <div class="west">
    <?php include(DEDEMEMBER."/templets/menu.htm"); ?>
  </div>
  <div class="east mL10">
    <div class="central">
      <dl class="ccc">
        <dt class="caption"><strong class="mL5"><?php echo GetLang('basicinformation');?></strong></dt>
        <dd class="body pB5" style="line-height:26px">
          <h4><?php echo GetLang('welcomeinfo');?></h4>
          <p><?php echo GetLang('regdate');?>：<?php echo MyDate('Y-m-d H:i',$cfg_ml->fields['jointime']);?></p>
          <p class="redA"><?php echo GetLang('statistics');?>：<?php echo GetLang('product');?> (<font color="red"><?php echo $products['nums'];?></font>)   <?php echo GetLang('shortmessage');?> (<font color="red"><?php echo $pms['nums'];?></font>)    <?php echo GetLang('myorders');?> (<font color="red"><?php echo $orders['nums'];?></font>)</p>
          <p class="blackA"><?php echo GetLang('shortcuts');?>：<a class="icon mail" href="pm.php" title="<?php echo GetLang('shortmessage'); ?>"><?php echo GetLang('shortmessage'); ?></a> <a class="icon fav mL10 mR10" href="mystow.php" title="<?php echo GetLang('myfavorites'); ?>"><?php echo GetLang('myfavorites'); ?></a> <a class="icon info" href="myfiles.php" title="<?php echo GetLang('mydocuments'); ?>"><?php echo GetLang('mydocuments'); ?></a></p>
        </dd>
      </dl>
      <dl class="ccc mT10">
        <dt class="caption"><strong class="mL5"><?php echo GetLang('newproduct'); ?></strong></dt>
        <dd class="body blackA">
          <ul class="w20 textCenter">
            <?php
foreach ($archives as $archive)
{
?>
            <li><a href="<?php echo $archive['htmlurl'];?>" title="<?php echo $archive['title'];?>" target="_blank"><img src="<?php echo !empty($archive['litpic']) ? $archive['litpic'] : "templets/images/defaultpic.gif";?>" width="100" height="100" alt="图片" /></a>
              <p><a href="<?php echo $archive['htmlurl'];?>" title="<?php echo $archive['title'];?>" target="_blank"><?php echo $archive['title'];?></a></p>
            </li>
            <?php
}
?>
          </ul>
          <div class="clearfix"></div>
        </dd>
      </dl>
      <dl class="ccc mT10 clear">
        <dt class="caption"><strong class="mL5"><?php echo GetLang('myfavorites'); ?></strong></dt>
        <dd class="body pB5">
          <ul class="list">
            <?php
foreach ($favorites as $favorite)
{
?>
            <li><span class="fRight"><?php echo MyDate('m-d H:i', $favorite['addtime']);?></span><a href="archives_do.php?dopost=viewArchives&aid=<?php echo $favorite['aid'];?>" title="<?php echo $favorite['title'];?>" target="_blank"><?php echo $favorite['title'];?></a></li>
            <?php
}
?>
          </ul>
          <div class="clearfix"></div>
        </dd>
      </dl>
    </div>
    <div class="blackA fRight" style="width:160px;">
      <dl class="ccc pd10">
        <dt class="textCenter mB10"><a href="/" title="标题" target="_blank"><img src="<?php echo (!empty($cfg_ml->fields['face']) ? $cfg_ml->fields['face'] : "templets/images/nopic.gif"); ?>" width="100" height="100" alt="图片" /></a>
          <h5><?php echo $cfg_ml->M_LoginID;?></h5>
        </dt>
        <dd><?php echo $cfg_ml->GetSta($dsql); ?></dd>
        <dd><?php echo GetLang('sex');  echo ($cfg_ml->fields['face']==0)? GetLang('man') : GetLang('woman');?></dd>
        <dd>IP:<?php echo $cfg_ml->fields['loginip']?></dd>
        <dd class="mT10"><a class="icon infoEdit" href="myinfo.php" title="<?php echo GetLang('personaldata'); ?>"><?php echo GetLang('information'); ?></a></dd>
        <dd><a class="icon faceSet" href="myface.php" title="<?php echo GetLang('faceset'); ?>"><?php echo GetLang('faceset'); ?></a></dd>
      </dl>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<?php include(DEDEMEMBER."/templets/foot.htm"); ?>
</body>
</html>