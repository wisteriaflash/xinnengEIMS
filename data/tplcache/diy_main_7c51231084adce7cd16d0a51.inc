<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();

$atts = array();
$atts['tagname'] = 'page';
$atts['pagesize'] = '50';
$blockValue = MakePublicTag($atts,$this->refObj,$fields); 
if(is_array($blockValue) && count($blockValue) > 0){
foreach( $blockValue as $key=>$fields )
{

}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>自定义表单管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">自定义表单管理</div>
</div>
<table _dlist="line" class="tlist" align="center">
		<tr>
				<td class="tbtitletxt">
				  <table width='96%' border="0" cellpadding='0' cellspacing='0'>
                  <tr>
                    <td width='30%' class="tbtitletxt">[<a href="diy_add.php"><u>增加新的自定义表单</u></a>]</td>
                    <td align='right'>
						[图片说明：
						<img src='img/manage1.gif' alt='更改' />更改
						<img src='img/file_del.gif' alt='删除' />删除
						]
                   </td>
                  </tr>
         </table>
      </td>
  </tr>
</table>

<table  _dlist="line" class="tlist" align="center">
		<tr align="center" bgcolor='#FBFCE2'>
      <td>diyid</td>
		  <td>名称</td>
		  <td>表名</td>
		  <td>管理</td>
  </tr>
		   <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
		  <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFEDA';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
        <td><?php echo $fields['diyid']; ?></td>
        <td><a href="diy_list.php?action=list&diyid=<?php echo $fields['diyid']; ?>"><u><?php echo $fields['name']; ?></u></a></td>
        <td><?php echo $fields['table']; ?></td>
        <td>
         <a href='diy_edit.php?diyid=<?php echo $fields['diyid']; ?>&dopost=edit'><img src='img/manage1.gif' alt='更改' /></a> |
		     <a href='diy_edit.php?diyid=<?php echo $fields['diyid']; ?>&dopost=delete'><img src='img/file_del.gif' alt='删除' /></a> |
		     <a href="../plus/diy.php?action=list&diyid=<?php echo $fields['diyid']; ?>" target="_blank"><img src='img/gtk-tmp.png' title='预览' alt='预览' />前台预览</a>
		    </td>
     </tr>
		   <?php
}
?>
</table>


<table _dlist="line" class="tlist" align="center">
		<tr>
			<td height="35" align="center" style="background:#F9FFE6;padding:7px 6px 6px 10px;" ><?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '6';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
</td>
  </tr>
</table>
</body>
</html>