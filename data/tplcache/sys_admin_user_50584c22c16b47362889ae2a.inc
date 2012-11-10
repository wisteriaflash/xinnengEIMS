<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>管理员帐号</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">管理员帐号</div>
</div>
<table class="tform">
  <tbody>
    <tr>
      <td align="left"></td>
      <td align="right"><b>
      		<a href="sys_admin_user_tj.php"><u>编辑绩效统计</u></a>
      		|
          	<a href="sys_admin_user_add.php"><u>增加管理员</u></a>
          	|
          	<a href="sys_group.php"><u>用户组管理</u></a>
          	</b></td>
    </tr>
  </tbody>
</table>

<table _dlist="line" class="tlist">
  <tbody>
  <tr bgcolor="#FFFFFF">
    <td height="24" colspan="5">　§为了防止错误操作，系统不允许在管理界面删除超级管理员，如果确实要删除，请自行在数据表dede_admin中删除。</td>
  </tr>
  <tr bgcolor="#F3FAE4">
    <td width="18%" height="24" align="center" bgcolor="#F3FAE4">登录ID</td>
    <td width="13%" align="center" bgcolor="#F3FAE4">笔名</td>
    <td width="22%" align="center" bgcolor="#F3FAE4">级别</td>
    <td width="22%" align="center" bgcolor="#F3FAE4">频道</td>
    <td width="23%" align="center" bgcolor="#F3FAE4">管理项</td>
  </tr>
  <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
  <tr bgcolor="#FFFFFF" align="center">
    <td rowspan="2"><?php echo $fields['userid']; ?></td>
    <td rowspan="2"><?php echo $fields['uname']; ?><br> </td>
    <td><?php echo GetUserType($fields['usertype']); ?></td>
    <td><?php echo GetChannel($fields['typename']); ?></td>
    <td rowspan="2">
	   <a href='sys_admin_user_edit.php?id=<?php echo $fields['id']; ?>&dopost=edit'><u>更改</u></a> |
     <a href='sys_admin_user_edit.php?id=<?php echo $fields['id']; ?>&userid=<?php echo $fields['userid']; ?>&dopost=delete'><u>删除</u></a> |
     <a href='content_list.php?mid=<?php echo $fields['id']; ?>'><u>文档</u></a>　
    </td>
  </tr>
  <tr>
  <td colspan="2" align="center" bgcolor="#FFFFFF">
	登录时间：<?php echo GetDateTimeMk($fields['logintime']); ?>　登录IP：<?php echo $fields['loginip']; ?>
	</td>
  </tr>
  <?php
}
?>
  <tr bgcolor="#F3FAE4">
    <td height="24" colspan="5" align="center" valign="top">
    	<?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '7';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>

    </td>
  </tr>
  </tbody>
</table>
</body>
</html>