<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>评论管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>
<script language='javascript'>
//获得选中文件的文件名
function getCheckboxItem()
{
	var allSel="";
	if(document.feedback.fid.value) return document.feedback.fid.value;
	for(i=0;i<document.feedback.fid.length;i++)
	{
		if(document.feedback.fid[i].checked)
		{
			if(allSel=="")
				allSel=document.feedback.fid[i].value;
			else
				allSel=allSel+","+document.feedback.fid[i].value;
		}
	}
	return allSel;	
}
function selAll()
{
	for(i=0;i<document.feedback.fid.length;i++)
	{
		document.feedback.fid[i].checked=true;
	}
}
function selNone()
{
	for(i=0;i<document.feedback.fid.length;i++)
	{
		document.feedback.fid[i].checked=false;
	}
}
function selNor()
{
	for(i=0;i<document.feedback.fid.length;i++)
	{
		if(document.feedback.fid[i].checked==false)
			document.feedback.fid[i].checked=true;
		else
			document.feedback.fid[i].checked=false;
		
	}
}
function delFeedback()
{
	var qstr=getCheckboxItem();
	if(qstr=="") alert("你没选中任何内容！");
	else if(window.confirm('你确定要删除这些评论吗?')) location.href="feedback_main.php?job=del&fid="+qstr;
}
function delFeedbackIP()
{
	var qstr=getCheckboxItem();
	if(qstr=="") alert("你没选中任何内容！");
	else if(window.confirm('你确定要删除这些评论吗?')) location.href="feedback_main.php?job=delall&fid="+qstr;
}
function checkFeedback()
{
	var qstr=getCheckboxItem();
	if(qstr=="") alert("你没选中任何内容！");
	else location.href="feedback_main.php?job=check&fid="+qstr;
}
function editFeedback()
{
	var qstr=getCheckboxItem();
	if(qstr=="") alert("你没选中任何内容！");
	else location.href="feedback_edit.php?job=edit&fid="+qstr;
}
</script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">评论管理</div>
</div>
<table class="tform">
  <tbody>
    <tr>
      <td></td>
      <td align="right"><form name='form1'>
          <strong>关键字:</strong>
          <input name='keyword' type='text' class="txt" style='width:150' size='15'>
          <select name="typeid" style="width:180">
            <option value="0">--请选择--</option>
            
 		<?php echo $GLOBALS['openarray']; ?>
	
          </select>
          <button type="submit" class="btn1">搜索</button>
      </form></td>
    </tr>
  </tbody>
</table>
<form name='feedback'>
<table _dlist="line check" class="tlist">
<thead>
  <tr>
    <td colspan="6"><div class="title"><strong>列表 - 评论</strong></div></td>
  </tr>
</thead>
<tbody>

<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
<tr>
  <th align="left">选择
    <input name="fid" type="checkbox" class="np" id="fid" value="<?php echo $fields['id']; ?>"></th>
  <th align="left">&nbsp;用户： <?php echo $fields['username']; ?>
    &nbsp;文档：<a href="<?php echo $GLOBALS['cfg_phpurl']; ?>/view.php?aid=<?php echo $fields['aid']; ?>" target="_blank"><?php echo $fields['arctitle']; ?></th>
  <th align="left">IP地址： <?php echo $fields['ip']; ?> </th>
  <th align="left"> &nbsp;时间： <?php echo GetDateTimeMK($fields['dtime']); ?> </th>
  <th align="left"><a href='feedback_edit.php?id=<?php echo $fields['id']; ?>'>[更改]</a></th>
</tr>
<tr class="">
  <td colspan="5"> 
    <?php echo IsCheck($fields['ischeck']);  echo jstrim($fields['msg'],100); ?>  
</td>
  </tr>
<?php
}
?>
</tbody>
<tfoot>
  <tr>
    <td colspan="6"><table class="tform">
      <tbody>
        <tr>
          <td align="left"><button type="button" class="btn1s" onClick="selAll()">全选</button>
          <button class="btn1s" type="button" onClick="selNone()">取消</button>
          <button type="button" class="btn1s" onClick="selNor()">反选</button>
          </td>
          <td align="right">
          <button type="button" class="btn1s" onClick="delFeedbackIP()">删除相同IP的所有评论</button>
          <button type="button" class="btn1s" onClick="delFeedback()">删除评论</button>
          <button type="button" class="btn1s" onClick="checkFeedback()">审核评论</button>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tfoot>
</table>
</form>
<div class="pagelist"> <?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
</div>
</body>
</html>