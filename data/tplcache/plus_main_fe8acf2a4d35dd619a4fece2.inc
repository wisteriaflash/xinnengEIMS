<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>插件管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">插件管理</div>
</div>
<table class="tform">
  <tbody>
    <tr>
      <td align="left"></td>
      <td align="right">[<a href="http://www.dedeeims.com/download/plus/" target="_blank"><u>下载更多插件..</u></a>] [<a href="plus_add.php"><u>安装新插件</u></a>]</td>
    </tr>
  </tbody>
</table>
<table _dlist="line" class="tlist">
  <tr align="center" bgcolor="#F9FCEF" height="24"> 
      <td width="10%">ID号</td>
      <td width="40%">插件名称</td>
      <td width="20%">插件作者</td>
      <td width="30%">管理</td>
   </tr>
   <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
    <tr align="center" bgcolor="#FFFFFF" height="24" onMouseMove="javascript:this.bgColor='#FCFDEE';" 
    	onMouseOut="javascript:this.bgColor='#FFFFFF';"> 
        <td><?php echo $fields['aid']; ?></td>
        <td><?php echo $fields['plusname']; ?></td>
        <td><?php echo $fields['writer']; ?></td>
        <td><?php echo GetSta($fields['isshow'],$fields['aid'],$fields['plusname']); ?></td>
     </tr>
		<?php
}
?>
     <tr align="center" bgcolor="#F9FCEF" height="36"> 
      <td colspan="5">
      	<?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '6';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>

      </td>
     </tr>
   </table>
   </td>
</tr>
</table>
</body>
</html>