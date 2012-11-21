<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>会员权限管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">订单管理</div>
</div>
<table _dlist="line" class="tlist" align="center">
	<form id="productOrdersList" action="product_orders.php" method="post">
    <input id="dopost" type="hidden" name="dopost" value="status">
    <thead>
       <tr height="28">
          <td>
            <strong>订单管理 &gt; 查询结果</strong>
          </td>
          <td align="right" colspan="5">订单编号：<input name="oid" type="text" id="oid" size="12" style="width:120px" value="<?php echo $GLOBALS['oid']; ?>" /> <button class="btn1" type="submit" onClick="setDopost('list');">搜索</button></td>
       </tr>
    </thead>
	<tbody>    
    <tr bgcolor="#F8FCF1" height="24">
      <td colspan="6">当前发生<strong><?php echo $total;?></strong>条订单记录</td>
    </tr>
    <tr bgcolor="#FDFEE9" height="24" align="center">
      <td width="30%" align="left">编号</td>
      <td width="10%">客户</td>
      <td width="10%">状态</td>
      <td width="10%">数量</td>
      <td width="18%">时间</td>
      <td width="8%">操作</td>
    </tr>
	<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
   <tr align="center" bgcolor="#FFFFFF" height="24">
     <td align="left">
		<input name="ids[]" type="checkbox" value="<?php echo $fields['id']; ?>" /> <?php echo $fields['oid']; ?>
	 </td>
     <td>
		<?php echo $fields['orgname']; ?>
     </td>
     <td>
		<?php echo _getStatus($fields['status']); ?>
     </td>
     <td>
		<?php echo $fields['prnums']; ?>
     </td>
	 <td>
		<?php echo GetDateMk($fields['dateline']); ?>
	 </td>
     <td>
        <a href="product_orders.php?id=<?php echo $fields['id']; ?>&amp;dopost=handle">[处理]</a>
     </td>
    </tr>
	<?php
}
?>
    <tr>
	 <td height="24" colspan="6" bgcolor="#F8FCF1">
        <button type="button" class="btn1s" _click="allSelect">全选</button>
        <button type="button" class="btn1s" _click="unSelect">取消</button>
        <select name="status" onChange="if($(this).val()==0||!confirm('确认要处理选中订单吗?')){ return false }else{setDopost('status');$('#productOrdersList').submit();}">
          <option value="0">改变状态</option>
          <option value="1">正在处理</option>
          <option value="2">处理完毕</option>
          <option value="999">订单结束</option>
        </select>
        <button type="submit" class="btn1s" onClick="if(!confirm('确认要删除选中订单吗?')){ return false }else{setDopost('del');}">删除新订单</button>
	 </td>
    </tr>
    <tr>
      <td height="24" colspan="6" bgcolor="#F8FCF1"><div class="pagelist"><?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '3';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
</div></td>
    </tr>
  </form>
  </tbody>
</table>
<script language="javascript">
function setDopost(_a)
{
	$('#dopost').val(_a);
}
</script>
</body>
</html>