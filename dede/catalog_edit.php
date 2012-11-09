<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/typelink.class.php");
if(empty($dopost))
{
	$dopost = '';
}
$id = isset($id) ? intval($id) : 0;

//检查权限许可
CheckPurview('t_Edit,t_AccEdit');

//检查栏目操作许可
CheckCatalog($id,"你无权更改本栏目！");

/*-----------------------
function action_save()
----------------------*/
if($dopost=="save")
{
	$description = Html2Text($description,1);
	$keywords = Html2Text($keywords,1);
	$upquery = "Update `#@__arctype` set
     sortrank='$sortrank',
     typename='$typename',
     typedir='$typedir',
     defaultname='$defaultname',
     channeltype='$channeltype',
     ispart='$ispart',
     description='$description',
     keywords='$keywords',
     `content`='$content'
	where id='$id' ";
	if(!$dsql->ExecuteNoneQuery($upquery))
	{
		ShowMsg("保存当前栏目更改时失败，请检查你的输入资料是否存在问题！","-1");
		exit();
	}
	UpDateCatCache();
	PutCookie('lastCid', $topid, 3600*24, "/");
	ShowMsg("成功更改一个分类！","catalog_main.php");
	exit();
}
/*-----------------------
function action_savetop()
----------------------*/
else if($dopost=="savetop")
{
	$description = Html2Text($description,1);
	$keywords = Html2Text($keywords,1);
	$upquery = "Update `#@__arctype` set
     sortrank='$sortrank',
     typedir='$typedir',
     isdefault='$isdefault',
     tempindex='$tempindex',
     description='$description',
     keywords='$keywords',
     `content`='$content'
	where id='$id' ";

	if(!$dsql->ExecuteNoneQuery($upquery)) {
		ShowMsg("保存当前栏目更改时失败，请检查你的输入资料是否存在问题！","-1");
		exit();
	}

	//更改子栏目属性
	$slinks = " id in (".GetSonIds($id).")";
	if(!empty($upnext))
	{
		$upquery = "Update `#@__arctype` set isdefault='$isdefault' where $slinks ";
		if(!$dsql->ExecuteNoneQuery($upquery))
		{
			ShowMsg("更改当前栏目成功，但更改下级栏目属性时失败！","-1");
			exit();
		}
	}
	UpDateCatCache();
	
	//更改默认语言入口
	if($olddflang != $typelang && isset($defultlang))
	{
		$dsql->ExecuteNoneQuery("Update `#@__sysconfig` set `value`='$langName' where `varname`='cfg_df_lang' ");
		ReWriteConfig();
	}
	
	PutCookie('lastCid', $id, 3600*24, "/");
	
	ShowMsg("成功更改语言栏目设置！","catalog_main.php");
	exit();
}

//读取栏目信息
$dsql->SetQuery("Select tp.*,ch.typename as ctypename From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id=$id");
$myrow = $dsql->GetOne();
$topid = $myrow['topid'];
$typelang = $myrow['lang'];
PutCookie('lastCid', $topid, 3600*24, "/");

//读取频道模型信息
$channelid = $myrow['channeltype'];
$dsql->SetQuery("select id,typename,nid from `#@__channeltype` where id<>-1 And isshow=1 order by id");
$dsql->Execute();
while($row=$dsql->GetObject())
{
	$channelArray[$row->id]['typename'] = $row->typename;
	$channelArray[$row->id]['nid'] = $row->nid;
	if($row->id==$channelid)
	{
		$nid = $row->nid;
	}
}

if($myrow['reid']==0)
{
	include DedeInclude('templets/catalog_edit_top.htm');
}
else
{
	if($topid==0) $topid = GetTopid($id);
	include DedeInclude('templets/catalog_edit.htm');
}

?>