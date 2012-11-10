<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>自定义标记</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">自定义标记管理</div>
</div>
<table class="tform">
  <tbody>
    <tr>
      <td align="left"></td>
      <td align="right">  &nbsp;[<a href="mytag_add.php"><u>增加一个标记</u></a>]
     &nbsp;[<a href="mytag_tag_guide.php"><u>智能标记向导</u></a>]</td>
    </tr>
  </tbody>
</table>
<table _dlist="line" class="tlist">
  <tr align="center" bgcolor="#F9FCEF" height="24"> 
      <td width="10%">编号</td>
      <td width="20%">标记名称</td>
      <td width="15%">所属栏目</td>
      <td width="15%">时间限制</td>
      <td width="20%">结束时间</td>
      <td width="20%">管理</td>
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
        <td><?php echo $fields['tagname']; ?></td>
        <td><?php echo TestType($fields['typename']); ?></td>
        <td><?php echo TimeSetValue($fields['timeset']); ?></td>
        <td><?php echo GetDateMk($fields['endtime']); ?></td>
        <td>
          	<a href='mytag_edit.php?aid=<?php echo $fields['aid']; ?>&dopost=edit'>更改</a> | 
		        <a href='mytag_edit.php?aid=<?php echo $fields['aid']; ?>&dopost=delete'>删除</a> | 
		        <a href='mytag_edit.php?aid=<?php echo $fields['aid']; ?>&dopost=getjs'>JS调用</a>
		    </td>
     </tr>
		<?php
}
?>
     <tr align="center" bgcolor="#F9FCEF" height="36"> 
      <td colspan="7">
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