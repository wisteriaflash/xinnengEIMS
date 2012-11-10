<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>内容模型管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">内容模型管理 </div>
</div>
<table class="tform">
  <tbody>
    <tr>
      <td></td>
      <td align="right"> <a href="mychannel_add.php" class='np coolbg'>增加新模型</a>
    	  <a href="mychannel_edit.php?dopost=exportin" class='np coolbg'>导入模型</a>
    	  <a href="stepselect_main.php" class='np coolbg'>联动类别管理</a></td>
    </tr>
  </tbody>
</table>

<table _dlist="line check" class="tlist">
    <thead>
      <tr>
        <td colspan="7"><div class="title"><strong>列表 - 内容模型</strong></div></td>
      </tr>
    </thead>
    <tbody>
  <tr align="center" bgcolor="#F9FCEF" height="28"> 
      <td width="6%">id号</td>
      <td width="16%">频道名称</td>
      <td width="12%">识别id</td>
      <td width="20%">附加表</td>
      <td width="12%">状态</td>
      <td width="8%">模型</td>
      <td>管理</td>
   </tr>
   <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
    <tr align="center" bgcolor="#FFFFFF" height="24" 
    	onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';"> 
        <td><?php echo $fields['id']; ?></td>
        <td>
        	<a href='mychannel_edit.php?id=<?php echo $fields['id']; ?>&dopost=edit'><u><?php echo $fields['typename']; ?></u></a>
        </td>
        <td><?php echo $fields['nid']; ?></td>
        <td><?php echo str_replace('#@__',$cfg_dbprefix,$fields['addtable']); ?></td>
        <td><?php echo GetSta($fields['isshow'],$fields['id']); ?></td>
        <td><?php echo IsSystem($fields['issystem']); ?></td>
        <td>
         <a href='mychannel_edit.php?id=<?php echo $fields['id']; ?>&dopost=copystart'><img src='img/gtk-copy.png' alt='复制' border="0" title='复制' /></a> 
         <a href='mychannel_edit.php?id=<?php echo $fields['id']; ?>&dopost=gettemplets'><img src='img/gtk-tmp.png' alt='模板' border="0" title='模板' /></a> 
         <a href='mychannel_edit.php?id=<?php echo $fields['id']; ?>&dopost=export'><img src='img/gtk-ex.png' alt='导出' border="0" title='导出' /></a>  
         <a href='mychannel_edit.php?id=<?php echo $fields['id']; ?>&dopost=edit'><img src='img/gtk-edit.png' alt='更改' border="0" title='更改' /></a>  
         <!--a href='mychannel_edit.php?mid=<?php echo $fields['id']; ?>&dopost=modifysearch'><img src='img/gtk-search.png' alt='自定义搜索' title='自定义搜索' /></a--> 
         <a href='mychannel_edit.php?id=<?php echo $fields['id']; ?>&dopost=delete'><img src='img/gtk-del.png' alt='删除' border="0" title='删除' /></a>
		    </td>
     </tr>
		<?php
}
?>
     <tr align="center" bgcolor="#F9FCEF" height="28"> 
      <td colspan="7">
      	<?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>

      </td>
     </tr>
     <tr align="center" bgcolor="#F9FCEF" height="32"> 
      <td colspan="7">
      	图标说明：
      	<img src='img/gtk-copy.png' title='复制' alt='复制' /> 复制
        <img src='img/gtk-tmp.png' title='模板' alt='模板' /> 模板
        <img src='img/gtk-ex.png' title='导出' alt='导出' /> 导出
        <img src='img/gtk-edit.png' alt='更改' title='更改' /> 修改
        <img src='img/gtk-search.png' alt='自定义搜索' title='自定义搜索' /> 自定义搜索
        <img src='img/gtk-del.png' alt='删除' title='删除' /> 删除
      </td>
     </tr>
   </table>
   </td>
</tr>
</tbody>
</table>
</body>
</html>