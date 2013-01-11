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
<script language="javascript" src="js/ieemu.js"></script>
<script language="javascript" src="js/context_menu.js"></script>
<script language="javascript" src="../include/dedeajax2.js"></script>
<script language="javascript">
if(moz) {
	extendEventObject();
	extendElementModel();
	emulateAttachEvent();
}
function viewArc(aid){
	if(aid==0) aid = getOneItem();
	window.open("archives_do.php?aid="+aid+"&dopost=viewArchives");
}
function editArc(aid){
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=editArchives";
}
function updateArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=makeArchives&qstr="+qstr;
}
function checkArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=checkArchives&qstr="+qstr;
}
function moveArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=moveArchives&qstr="+qstr;
}
function adArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=commendArchives&qstr="+qstr;
}
function delArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?qstr="+qstr+"&aid="+aid+"&dopost=delArchives";
}
//上下文菜单
function ShowMenu(evt,obj,aid,atitle)
{
  var popupoptions
  popupoptions = [
    new ContextItem("浏览文档",function(){ viewArc(aid); }),
    new ContextItem("编辑文档",function(){ editArc(aid); }),
    new ContextSeperator(),
    new ContextItem("更新HTML",function(){ updateArc(aid); }),
    new ContextItem("审核文档",function(){ checkArc(aid); }),
    new ContextItem("推荐文档",function(){ adArc(aid); }),
    new ContextSeperator(),
    new ContextItem("删除文档",function(){ delArc(aid); }),
    new ContextSeperator(),
    new ContextItem("全部选择",function(){ selAll(); }),
    new ContextItem("取消选择",function(){ noSelAll(); }),
    new ContextSeperator(),
    new ContextItem("关闭菜单",function(){})
  ]
  ContextMenu.display(evt,popupoptions);
  //location="catalog_main.php";
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
function editTitle(aid)
{
   var taget_obj = document.getElementById('arc'+aid);
   var myajax = new DedeAjax(taget_obj,false,false,"","","");
   myajax.SendGet2("ajax.php?action=show&aid="+aid);
   DedeXHTTP = null;
}
function postTitle(aid){
   obj = document.getElementById('arc'+aid);
   v = document.getElementById('v_'+aid).value;
   var myajax = new DedeAjax(obj,false,false,"","","");
   <?php
   if(strtolower(substr($cfg_soft_lang, 0, 3)) == 'utf') {
   ?>
   myajax.AddKeyUtf8('action','post');
   myajax.AddKeyUtf8('aid',aid);
   myajax.AddKeyUtf8('title',v);
   myajax.SendPost2('ajax.php');
   <?php
   }
   elseif (strtolower(substr($cfg_soft_lang, 0, 2)) == 'gb') {
   	 echo 'myajax.SendGet2("ajax.php?action=show&aid="+aid+"&action=post&title="+v);';
   }
   ?>
   DedeXHTTP = null;
}
</script>

</head>
<body onLoad="ContextMenu.intializeContextMenu()">
<div class="mtitle">
  <div class="lbox">文档管理</div>
  <div class="rbox"> 跳转:
    <select class="fs_12" name="123">
      <option>所有频道</option>
    </select>
  </div>
</div>
<!--  快速转换位置按钮  -->
<table class="tform">
  <tbody>
    <tr>
      <td align="left"></td>
      <td align="right"><button type="button" class="btn1" onClick="location='catalog_do.php?channelid=<?php echo $GLOBALS['channelid']; ?>&cid=<?php echo $GLOBALS['cid']; ?>&dopost=addArchives';" >添加文档</button>
        <?php if($adminid==$mid){ ?>
        <button type='button' class="btn1" onClick="location='content_list.php?cid=<?php echo $GLOBALS['cid']; ?>&mid=0';">全部文档</button>
        <?php
}
else{
?>
        <button type='button' class="btn1" onClick="location='content_list.php?cid=<?php echo $GLOBALS['cid']; ?>&mid=<?php echo $cuserLogin->getUserID(); ?>';">我的文档</button>
        <?php }  echo $GLOBALS['CheckUserSend']; ?>
        <button type='button' class="btn1" onClick="location='catalog_main.php';">栏目管理</button>
        <button type='button' class="btn1" name='bb1' onClick="location='makehtml_list.php?cid=<?php echo $GLOBALS['cid']; ?>';">更新列表</button>
        <button type='button' class="btn1" name='bb2' onClick="location='makehtml_archives.php?cid=<?php echo $GLOBALS['cid']; ?>';">更新文档</button>
        <button type='button' class="btn1" name='bb1' onClick="location='recycling.php?cid=<?php echo $GLOBALS['cid']; ?>';">文章回收站</button></td>
    </tr>
  </tbody>
</table>
<!--  内容列表   -->
<form name="form2">
  <table class="tlist" _dlist="line check">
    <thead>
      <tr>
        <td colspan="9"><div class="title"><strong><?php echo $GLOBALS['positionname']; ?>文档列表 &nbsp;(使用鼠标右键进行常用操作)</strong></div>
          </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th align="left">ID</th>
        <th align="left">标题</th>
        <th align="left">录入时间</th>
        <th align="left">类目</th>
        <th align="left">点击</th>
        <th align="left">HTML</th>
        <th align="left">权限</th>
        <th align="left">发布人</th>
        <th align="right">操作</th>
      </tr>
    <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
    <tr oncontextmenu="ShowMenu(event,this,<?php echo $fields['id']; ?>,'<?php echo urlencode($fields['title']); ?>')">
      <td class="fs_11"><?php echo $fields['id']; ?></td>
      <td onDblClick="editTitle(<?php echo $fields['id']; ?>);"><input name="arcID" type="checkbox" id="arcID" value="<?php echo $fields['id']; ?>" class="np">
        <span id="arc<?php echo $fields['id']; ?>"> <a href='archives_do.php?aid=<?php echo $fields['id']; ?>&dopost=editArchives'
	oncontextmenu="ShowMenu(event,this,<?php echo $fields['id']; ?>,'<?php echo urlencode($fields['title']); ?>')"> <u><?php echo $fields['title']; ?></u> </a></span><?php echo IsCommendArchives($fields['flag']); ?></td>
      <td class="fs_11"><?php echo GetDateMk($fields['senddate']); ?></td>
      <td><strong><?php echo $fields['typename']; ?></strong></td>
      <td class="fs_11"><?php echo $fields['click']; ?></td>
      <td><?php echo IsHtmlArchives($fields['ismake']); ?></td>
      <td><?php echo GetRankName($fields['arcrank']); ?></td>
      <td><?php echo $fields['adminname']; ?></td>
      <td class="ta_r"><a href="javascript:editArc(<?php echo $fields['id']; ?>)">编辑</a> | <a href="javascript:viewArc(<?php echo $fields['id']; ?>)">预览</a></td>
    </tr>
    <?php
}
?>
    </tbody>
    
    <tfoot>
      <tr>
        <td colspan="9"><button type="button" class="btn1s" _click="allSelect">全选</button>
          <button type="button" class="btn1s" _click="unSelect">取消</button>
          <button type="button" class="btn1s" onClick="javascript:updateArc(0)">更新</button>
            <button type="button" class="btn1s" onClick="javascript:checkArc(0)">审核</button>
            <button type="button" class="btn1s" onClick="javascript:adArc(0)">推荐</button>
            <button type="button" class="btn1s" onClick="javascript:moveArc(0)">移动</button>
            <button type="button" class="btn1s" onClick="javascript:delArc(0)">删除</button></td>
      </tr>
    </tfoot>
  </table>
  <div class="pagelist"><?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '3';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
</div>
</form>
<!--  搜索表单  -->
<form name='form3' action='content_list.php' method='get'>
<input type='hidden' name='dopost' value='listArchives' />
<table width='98%'  border='0' cellpadding='1' cellspacing='1' align="center" style="margin-top:8px">
  <tr bgcolor='#EEF4EA'>
    <td align='center'>
      <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
          <td width='90' align='center'>请选择类目：</td>
          <td width='160'>
          <select name='cid' class="txt" style='width:150'>
          <option value='0'>选择分类...</option>
          	<?php echo $GLOBALS['optionarr']; ?>
          </select>
        </td>
        <td width='70'>
          关键字：
        </td>
        <td width='160'>
          	<input name='keyword' type='text' class="txt" style='width:150px' value='<?php echo $GLOBALS['keyword']; ?>' />
        </td>
        <td width='110'>
    		<select name='orderby' class="txt" style='width:80px'>
            <option value='id'>排序...</option>
            <option value='pubdate'>发布时间</option>
            <option value='sortrank'>置顶权值</option>
            <option value='click'>点击量</option>
            <option value='postnum'>评论积分</option>
            <option value='lastpost'>最后评论</option>
      	</select>
      </td>
        <td>
          <button type="submit" class="btn1">搜索</button>
        </td>
       </tr>
      </table>
    </td>
  </tr>
</table>
</form>
</body>
</html>