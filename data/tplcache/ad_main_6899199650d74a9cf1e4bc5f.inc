<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>广告管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">广告管理</div>
</div>
<table class="tform">
  <tbody>
    <tr>
      <td align="left"></td>
      <td align="right"><a href="ad_add.php"><u>增加一个新广告</u></a></td>
    </tr>
  </tbody>
</table>
<table _dlist="line" class="tlist">
  <tr align="center" bgcolor="#F9FCEF" height="24"> 
      <td width="8%">编号</td>
      <td width="20%">广告名称</td>
      <td width="12%">投放范围</td>
      <td width="11%">是否限时</td>
      <td width="11%">结束时间</td>
      <td width="20%">广告代码</td>
      <td width="18%">管理</td>
   </tr>
   <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
    <tr align="center" bgcolor="#FFFFFF" height="24" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';"> 
        <td><?php echo $fields['aid']; ?></td>
        <td><?php echo $fields['adname']; ?></td>
        <td><?php echo TestType($fields['typename']); ?></td>
        <td><?php echo TimeSetValue($fields['timeset']); ?></td>
        <td><?php echo GetDateMk($fields['endtime']); ?></td>
        <td><?php echo '{'; ?>dede:myad name='<?php echo $fields['tagname']; ?>'/<?php echo '}'; ?></td>
        <td>
         <a href='ad_edit.php?aid=<?php echo $fields['aid']; ?>&dopost=getjs'>[JS]</a>
         <a href='ad_edit.php?aid=<?php echo $fields['aid']; ?>&dopost=edit'>[更改]</a>
		     <a href='ad_edit.php?aid=<?php echo $fields['aid']; ?>&dopost=delete'>[删除]</a>
		    </td>
     </tr>
		<?php
}
?>
     <tr align="center" bgcolor="#F9FCEF" height="24"> 
      <td colspan="7">
      	<?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '5';
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