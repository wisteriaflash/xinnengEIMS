<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?>-<?php echo GetLang('guestbook'); ?></title>
<link href="<?php echo $cfg_templeturl; ?>/<?php echo $cfg_df_style;?>/css/greenstyle.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/include/dedeajax2.js"></script>
<script type="text/javascript">
function CheckLogin(){
	  var taget_obj = document.getElementById('_userlogin');
	  myajax = new DedeAjax(taget_obj,false,false,'','','');
	  myajax.SendGet2("/member/ajax_loginsta.php");
	  DedeXHTTP = null;
}
</script>
<style>
table,td,th{border-collapse:collapse;}
</style>
</head>
<body>
<div id="topBar">
  <div class="wrapper">
    <div class="login">
      <div id="_userlogin">
        <form name="userlogin" action="/member/index_do.php" method="POST">
          <input type="hidden" name="fmdo" value="login" />
          <input type="hidden" name="dopost" value="login" />
          <input type="hidden" name="keeptime" value="604800" />
          <ul class="fLeftChild">
            <li>
              <label><?php echo GetLang('username'); ?></label>
              <input type="text" name="userid" class="text" style="width:80px;" />
            </li>
            <li>
              <label><?php echo GetLang('password'); ?></label>
              <input type="password" name="pwd" class="text" style="width:80px;" />
            </li>
            <li>
              <label><?php echo GetLang('validatecode'); ?></label>
              <input type="text" name="vdcode" class="text" style="width:50px;"/>
              <img src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/include/vdimgck.php" alt="<?php echo GetLang('validatecode'); ?>" align="absmiddle"/></li>
            <li>
              <input class="mL5 mR5" name="submit" type="submit" value="<?php echo GetLang('login'); ?>" />
            </li>
            <li><a href="/member/index_do.php?fmdo=user&dopost=regnew" target="_blank"><?php echo GetLang('register'); ?></a></li>
          </ul>
        </form>
      </div>
    </div>
    <script language="javascript" type="text/javascript">CheckLogin();</script>
    <div class="fRight"> 
      <ol class="fRight fLeftChild overflow mT5">
        <?php
$atts = array();
$atts['tagname'] = 'langlist';
$blockValue = MakePublicTag($atts,$this->refObj,$fields); 
if(is_array($blockValue) && count($blockValue) > 0){
foreach( $blockValue as $key=>$fields )
{
?>
        <li><a href="<?php echo $fields['typelink']; ?>" title="<?php echo $fields['langname']; ?>"><?php echo $fields['langname']; ?></a></li>
        <?php
}
}
?>
      </ol>
      <i class="fRight"><?php echo GetLang('langent')?></i>
    </div>
  </div>
</div>
<div class="wrapper">
  <div class="fLeft"> <img src="<?php echo $cfg_templeturl; ?>/default/images/logo.jpg" width="199" height="33" vspace="14" class="fLeft" /> </div>
  <form id="search" class="fRight" action="<?php echo $GLOBALS['cfg_phpurl']; ?>/search.php" name="formsearch">
    <input type='hidden' name='lang' value='<?php
$atts = array();
$atts['tagname'] = 'handlang';
$blockValue = MakePublicTag($atts,$this->refObj,$fields); 
if(is_array($blockValue) && count($blockValue) > 0){
foreach( $blockValue as $key=>$fields )
{

}
}
?>' />
    <input name='keyword' type='text' class="text" />
    <button name="searchbt" type="submit">&nbsp;</button>
  </form>
  <div class="clear"></div>
  <div class="module green wrapper mT10">
    <div class="top">
      <div class="t_l"></div>
      <div class="t_r"></div>
      <ul id="navMenu" class="fLeftChild overflow fLeft">
        <?php
$atts = array();
$atts['tagname'] = 'channel';
$atts['get'] = 'index';
$blockValue = MakePublicTag($atts,$this->refObj,$fields); 
if(is_array($blockValue) && count($blockValue) > 0){
foreach( $blockValue as $key=>$fields )
{
?>
        <li><a href="<?php echo $fields['typelink']; ?>"><?php echo $fields['indexname']; ?></a></li>
        <?php
}
}

$atts = array();
$atts['tagname'] = 'channel';
$blockValue = MakePublicTag($atts,$this->refObj,$fields); 
if(is_array($blockValue) && count($blockValue) > 0){
foreach( $blockValue as $key=>$fields )
{
?>
        <li><a href=" <?php echo $fields['typelink']; ?>"> <?php echo $fields['typename']; ?></a></li>
        <?php
}
}
?>
      </ul>
      <span class="mT5 mR5"><a id="res" href="<?php echo $GLOBALS['cfg_clihost']; ?>/data/rssmap.html"></a></span> </div>
  </div>
  <div class="module silver wrapper mT10">
    <div class="top">
      <div class="t_l"></div>
      <div class="t_r"></div>
      <ul id="position" class="fLeftChild overflow">
        <?php
$atts = array();
$atts['tagname'] = 'channel';
$atts['get'] = 'index';
$blockValue = MakePublicTag($atts,$this->refObj,$fields); 
if(is_array($blockValue) && count($blockValue) > 0){
foreach( $blockValue as $key=>$fields )
{
?>
        <li class="home"><a href="<?php echo $fields['typelink']; ?>"><?php echo $fields['indexname']; ?></a></li>
        <?php
}
}
?>
        <li><a href="#"><?php echo GetLang('guestbook'); ?></a></li>
      </ul>
    </div>
  </div>
  <div class="wrapper">
    <div class="west">
      <dl class="e2e2e2 mT10">
        <dt class="caption"><strong class="mL5"><?php echo GetLang('contactus'); ?></strong></dt>
        <dd class="body pB5">
          <ol class="list">
            <?php
              $query = "Select * From `#@__mylang` where lang='$lang' And eid='contacttype' ";
              $row = $dsql->GetOne($query);
              if(!is_array($row)) {
                  echo $eid;
              }
              else {
                  echo $row['langtxt'];
              }            	       
            ?>
          </ol>
        </dd>
      </dl>
    </div>
    <div class="east">
      <dl class="e2e2e2 mT10">
        <dt class="heading"><strong class="mL5"><?php echo GetLang('guestbook'); ?></strong></dt>
        <dd class="body pB5"> <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
          <table width='100%' border='0' align="center" cellpadding='3' cellspacing='1' bgcolor="#CCCCCC">
            <tr>
              <td width='160' rowspan='3' align="center" bgcolor='#FFFFFF'><table width='160' border='0' align="center" cellpadding='1' cellspacing='2'>
                  <tr>
                    <td height="120" align=center><img src='guestbook/images/<?php echo $fields['face']?>.gif' border=0 /> <?php echo GetIsCheck($fields['ischeck'],$fields['id'])?></td>
                  </tr>
                  <tr>
                    <td height="20" align=left>&nbsp;<?php echo GetLang('username'); ?>：<?php echo $fields['uname']?></td>
                  </tr>
                  <tr>
                    <td height="20" align=left>&nbsp;<?php echo GetLang('from'); ?>：<?php echo $fields['ip']?></td>
                  </tr>
                  <tr>
                    <td height="20" align=left>&nbsp;<?php echo GetLang('qq'); ?>：<?php echo $fields['qq']?></td>
                  </tr>
                </table></td>
              <td height="20" bgcolor='#FFFFFF'><img height='16' src='guestbook/images/time.gif' width='16' /> <?php echo GetLang('pubdate');  echo MyDate('Y-m-d H:i:s',$fields['dtime']); ?></td>
            </tr>
            <tr>
              <td height="160" bgcolor="#FFFFFF"><div class="pubBody" style="white-space:normal; word-break:break-all; overflow:auto; width:495px" ><?php echo $fields['msg']; ?></div></td>
            </tr>
            <tr>
              <td height="20" bgcolor='#FFFFFF'><a href='mailto:<?php echo $fields["email"]?>'><img src='guestbook/images/mail.gif' border=0 width='16' height='16' />[<?php echo GetLang('email')?>]</a> <a href='http://<?php echo $fields['homepage']?>' target='_blank'><img src='guestbook/images/home.gif' border=0 width='16' height='16' />[<?php echo GetLang('indexname')?>]</a> <a href='guestbook.php?action=admin&id=<?php echo $fields['id']; ?>'><img src='guestbook/images/quote.gif' border=0 height=16 width=16 />[<?php echo GetLang('reply')?>/<?php echo GetLang('edit')?>]</a> <a href='guestbook.php?action=admin&id=<?php echo $fields['id']; ?>&job=del'><img src='guestbook/images/del.gif' border=0 height=16 width=16 />[<?php echo GetLang('del')?>]</a></td>
            </tr>
          </table>
          <br />
          <?php
}
?>
          <table border='0' cellpadding='0' cellspacing='4' width='80%' align='center'>
            <tr>
              <td align="right"> <?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
 </td>
            </tr>
            <tr>
              <td></td>
            </tr>
          </table>
          <table width="100%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#CCCCCC">
            <form method="post" action="guestbook.php" name="form1">
              <input type='hidden' name='action' value='save' />
              <tr bgcolor="#ffffff">
                <td width="10%" height="30" align="center" nowrap><?php echo GetLang('title')?>：</td>
                <td width="40%"><input type="text" maxlength="10" name="title" size="30" class="text" id="title"/></td>
                <td width="10%" align="center" nowrap><font color="#FF0000">*</font><?php echo GetLang('youname')?>：</td>
                <td width="40%"><input type="text" maxlength="10" name="uname" size="30" class="text"/></td>
              </tr>
              <tr bgcolor="#ffffff">
                <td height="30" align="center" nowrap><?php echo GetLang('qq')?>：</td>
                <td><input maxlength="20" type="text" name="qq" size="20" class="text" /></td>
                <td align="center" nowrap height="12">&nbsp;<?php echo GetLang('email')?>：</td>
                <td height="12"><input maxlength="80" type="text" name="email" size="30" class="text"/></td>
              </tr>
              <tr bgcolor="#ffffff">
                <td width="10%" height="30" align="center" nowrap><?php echo GetLang('homepage')?>：</td>
                <td height="12"><input maxlength="80" type="text" name="homepage" size="20" class="text" /></td>
                <td height="12" align="center"><font color="#FF0000">*</font><?php echo GetLang('validatecode')?>：</td>
                <td height="12"><table width="200" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="84"><input name="validate" type="text" id="vdcode2" class="text" style="width:40px" /></td>
                      <td width="116"><img src='../include/vdimgck.php' width='50' height='20' /></td>
                    </tr>
                </table></td>
              </tr>
              <tr bgcolor="#ffffff">
                <td align="center" nowrap width="10%"><font color="#FF0000">*</font><?php echo GetLang('fbcontent')?>：<br />
                  (1000字内) </td>
                <td align="left" width="40%"><textarea name="msg" cols="30" rows="8" class="text"></textarea></td>
                <td align="center" nowrap height="2" width="10%"><?php echo GetLang('faceico')?>：</td>
                <td nowrap height="2" width="40%"><input type="radio" name="img" value="01" checked />
                  <img src="guestbook/images/01.gif" width="25" height="25" />
                  <input type="radio" name="img" value="02" />
                  <img src="guestbook/images/02.gif" width="25" height="25" />
                  <input type="radio" name="img" value="03" />
                  <img src="guestbook/images/03.gif" width="25" height="25" /> <br />
                  <input type="radio" name="img" value="04" />
                  <img src="guestbook/images/04.gif" width="25" height="25" />
                  <input type="radio" name="img" value="05" />
                  <img src="guestbook/images/05.gif" width="25" height="25" />
                  <input type="radio" name="img" value="06" />
                  <img src="guestbook/images/06.gif" width="25" height="25" /> <br />
                  <input type="radio" name="img" value="07" />
                  <img src="guestbook/images/07.gif" width="25" height="25" />
                  <input type="radio" name="img" value="08" />
                  <img src="guestbook/images/08.gif" width="25" height="25" />
                  <input type="radio" name="img" value="09" />
                  <img src="guestbook/images/09.gif" width="25" height="25" /> <br />
                  <input type="radio" name="img" value="10" />
                  <img src="guestbook/images/10.gif" width="25" height="25" />
                  <input type="radio" name="img" value="11" />
                  <img src="guestbook/images/11.gif" width="25" height="25" />
                  <input type="radio" name="img" value="12" />
                  <img src="guestbook/images/12.gif" width="25" height="25" /></td>
              </tr>
              <tr bgcolor="#ffffff">
                <td height="30" colspan="4" align="center" nowrap><input maxlength="1000" type="submit" name="Submit" value="提 交" class="btn-2" />
                  &nbsp;&nbsp;
                  <input type="reset" name="Submit2" value="取 消" class="btn-2" /></td>
              </tr>
            </form>
          </table>
        </dd>
      </dl>
    </div>
  </div>
  <div class="module green wrapper mT10 mB10">
    <div class="bottom aWhite">
      <div class="b_l"></div>
      <div class="b_r"></div>
      <em class="fLeft">Copyright &copy; 2004-2009 DedeEIMS. 织梦科技 版权所有</em> <strong class="fRight">Powered by <a href="http://www.dedeeims.com/" target="_blank" style="color:#FFF">DedeEIMS</a> © 2004-2008 <a href="http://www.desdev.cn" style="color:#FFF">DesDev Inc.</a></strong> </div>
  </div>
</div>
</body>
</html>