<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>招聘信息管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>
<script language='javascript'>
function Sel(stype)
{
	  for(var i=0;i < document.getElementsByName('aids').length;i++)
	  {
		  if(stype=='all') document.getElementsByName('aids')[i].checked=true;
		  else document.getElementsByName('aids')[i].checked=false;
	  }
}
function checkSelect(){
  var str = '';
  for(var i=0;i < document.getElementsByName('aids').length;i++){
      if(document.getElementsByName('aids')[i].checked){
        if(str=='') str += document.getElementsByName('aids')[i].value;
        else str += ',' + document.getElementsByName('aids')[i].value;
      }
  }
  if(str=='')
  {
    alert('你没选择任何内容！');
    return false;
  }
  return str;
}
function selectDelete(){
	var str = checkSelect();
	if(str){
		location="job_edit.php?aids="+str+"&dopost=delall";
	}
}

function closeTip(){
  $('#jobTip').css('display','none');
}
function showTip(value){
  var idstr= value;
  var tip = $('#jobTip');
  tip.find('.item').css('display','none');
  if(idstr=="btnLinkman"){
    tip.find('.update_linkman').css('display','block');
    tip.find('button').click(function(){
      updateLinkman();
    })
  }else if(idstr=="btnLinktel"){
    tip.find('.update_linktel').css('display','block');
    tip.find('button').click(function(){
      updateLinktel();
    })
  }
  tip.css('display','block');
}

function updateLinkman(){
  var content = $('#linkman').attr('value');
  if(content==''){
    alert('内容不能为空');
    return
  }
  var str = checkSelect();
  if(str && content){
    location="job_edit.php?aids="+str+"&content="+content+"&dopost=saveedit&type=linkman";
  }
  $('#jobTip').css('display','none');
}
function updateLinktel(){
  var content = $('#linktel').attr('value');
  if(content==''){
    alert('内容不能为空');
    return
  }
  var str = checkSelect();
  if(str && content){
    location="job_edit.php?aids="+str+"&content="+content+"&dopost=saveedit&type=linktel";
  }
  $('#jobTip').css('display','none');
}
</script>

</head>
<body>
<div class="mtitle">
  <div class="lbox">招聘信息管理 </div>
</div>
<table class="tform">
  <tbody>
    <tr>
      <td></td>
      <td align="right"><a href="job_add.php">增加新招聘信息</a></td>
    </tr>
  </tbody>
</table>
<form class="mainform" name='form1' method='post' action='job_edit.php' onSubmit='return checkSubmit();'>
  <input type='hidden' name='dopost' value='delall'/>
  <table _dlist="line check" class="tlist">
    <thead>
      <tr>
        <td colspan="7"><div class="title"><strong>文档列表 - 所有招聘信息</strong></div></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th align="left">选择</th>
        <th align="left">职位名称</th>
        <th align="left">部门</th>
        <th align="left">语言</th>
        <!-- <th align="left">发布时间</th> -->
        <!-- <th align="left">过期时间</th> -->
        <th align="right">管理</th>
      </tr>
    <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?>
    <tr class="">
      <td><input type='checkbox' name='aids' value='<?php echo $fields['id']; ?>' class='np' /></td>
      <td><a href='job_edit.php?id=<?php echo $fields['id']; ?>&dopost=getedit'><?php echo $fields['jobname']; ?></a></td>
      <td><?php echo $fields['needpart']; ?></td>
      <td><strong><?php echo $lang_map_arr[$fields['lang']]; ?>(<?php echo $fields['lang']; ?>) </strong></td>
      <!-- <td class="fs_11"><?php echo MyDate('Y-m-d', $fields['sendtime']); ?></td> -->
      <!-- <td class="fs_11"> <?php echo MyDate('Y-m-d', $fields['exptime']); ?></td> -->
      <td class="ta_r"><a href='job_edit.php?id=<?php echo $fields['id']; ?>&dopost=getedit'>[更改]</a> <a href='job_edit.php?id=<?php echo $fields['id']; ?>&dopost=delete'>[删除]</a></td>
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
          <button class="btn1s" type="button" onclick='selectDelete()'>批量删除</button>
          <button class="btn1s btn_update" onclick="showTip('btnLinkman')" type="button">批量修改联系人</button>
          <button class="btn1s btn_update" onclick="showTip('btnLinktel')" type="button">批量修改联系电话</button>
        </td>
      </tr>
    </tfoot>
  </table>
  <div class="jobUpdate" id="jobTip">
    <div class="wrapper">
      <span class="close" onclick="closeTip()">关闭</span>
      <ul>
        <li class="item update_linkman"><em>联系人：</em><input name="linkman" type="text" class="txt" id="linkman" /></li>
        <li class="item update_linktel"><em>联系电话：</em><input name="linktel" type="text" class="txt" id="linktel" /></li>
        <li><button class="btn1s" type="button">确定</button></li>
      </ul>
    </div>
  </div>
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