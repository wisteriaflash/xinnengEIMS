<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文档管理</title>
<link href="main.css" rel="stylesheet" type="text/css" />
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>
<script language="javascript">
function moveArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=return&qstr="+qstr;
}
function delArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?qstr="+qstr+"&aid="+aid+"&dopost=del";
}
//获得选中文件的文件名
function getCheckboxItem()
{
	var allSel="";
	if(document.form2.arcID.value) return document.form2.arcID.value;
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
			if(allSel=="")
				allSel=document.form2.arcID[i].value;
			else
				allSel=allSel+"`"+document.form2.arcID[i].value;
		}
	}
	return allSel;
}
//获得选中其中一个的id
function getOneItem()
{
	var allSel="";
	if(document.form2.arcID.value) return document.form2.arcID.value;
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
				allSel = document.form2.arcID[i].value;
				break;
		}
	}
	return allSel;
}
function selAll()
{
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(!document.form2.arcID[i].checked)
		{
			document.form2.arcID[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
			document.form2.arcID[i].checked=false;
		}
	}
}
</script>
</head>
<body>
<div class="mtitle">
  <div class="lbox">文档回收站</div>
</div>



<form name="form2">
<table _dlist="line check" class="tlist">
    <thead>
      <tr>
        <td colspan="7"><div class="title"><strong>列表 - 自定义资料</strong></div></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th width="6%" align="left">ID</td>
<th width="4%" align="left">选择</td>
<th width="28%" align="left">文章标题</td>
<th width="10%" align="left">录入时间</td>
<th width="10%" align="left">类目</td>
<th width="8%" align="left">点击</td>
<th width="10%" align="left">操作</td>
      </tr>
    <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
    <tr onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';"
	height="22" oncontextmenu="ShowMenu(event,this,<?php echo $fields['id']; ?>,'<?php echo urlencode($fields['title']); ?>')">
<td><?php echo $fields['id']; ?></td>
<td><input name="arcID" type="checkbox" id="arcID" value="<?php echo $fields['id']; ?>" class="np"></td>
<td align='left'>
	<a href='archives_do.php?aid=<?php echo $fields['id']; ?>&dopost=editArchives'><u><?php echo $fields['title']; ?></u></a>
</td>
<td><?php echo GetDateMk($fields['senddate']); ?></td>
<td><?php echo $fields['typename']; ?></td>
<td><?php echo $fields['click']; ?></td>
<td><a href="javascript:moveArc(<?php echo $fields['id']; ?>)">还原</a> | <a href="javascript:delArc(<?php echo $fields['id']; ?>)">删除</a></td>
    </tr>
    <?php
}
?>
    <tr class=""> </tr>
    </tbody>
    
    <tfoot>
      <tr>
        <td colspan="7"><button type="button" class="btn1s" _click="allSelect">全选</button>
          <button type="button" class="btn1s" _click="unSelect">反选</button>
          <button class="btn1s" type="button" onclick='javascript:moveArc(0)'>还原</button>
          <button class="btn1s" type="button" onclick='javascript:delArc(0)'>删除</button>     
          </td>
      </tr>
    </tfoot>
  </table>
</form>
<div class="pagelist"><?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '6';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
</div>

</body>
</html>