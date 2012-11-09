<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/typelink.class.php");
if(empty($listtype)) $listtype='';
if(empty($dopost)) $dopost = '';
if(empty($upinyin)) $upinyin = 0;
if(empty($channelid)) $channelid = 1;
if(isset($channeltype)) $channelid = $channeltype;
$myrow = array();
$id = empty($id) ? 0 :intval($id);
$nid = 'article';
if($id==0) {
	CheckPurview('t_New');
} else {
	CheckPurview('t_AccNew');
	CheckCatalog($id, "你无权在本栏目下创建子类！");
}
/*---------------------
function action_savetop(){ }
---------------------*/
if($dopost=='savetop')
{
	if($typename=="") {
		ShowMsg("必须选择一个语言入口！", "-1");
		exit();
	}
	$row = $dsql->GetOne("Select * From #@__arctype where typename like '$typename' ");
	if(is_array($row)) {
		ShowMsg("已经存在这个语言入口！", "-1");
		exit();
	}
	$arr = array();
	preg_match_all("/\((.*)\)/", $typename, $arr);
	$langName = $arr[1][0];
	$typedir = $pardir.$typedir;
	$templist = "{style}/{lang}/list_article.htm";
	$temparticle = "{style}/{lang}/article_article.htm";
	$query = "insert into `#@__arctype`(reid,topid,sortrank,typename,typedir,lang,isdefault,defaultname,channeltype,
	tempindex,templist,temparticle,namerule,namerule2,ispart,description,keywords,ishidden,`content`)
    Values('0','0','$sortrank','$typename','$typedir','$langName','$isdefault','index.html','1',
    '$tempindex','$templist','$temparticle','{$cfg_df_namerule}','{typedir}/index-{tid}-{page}.html','1','$description','$keywords','0','$content')";
	
	if(!$dsql->ExecuteNoneQuery($query))
	{
		echo "catalog_add.php 错误提示：<br />";
		echo $dsql->GetError();
		exit();
	}
	
	UpDateCatCache();
	
	//设为默认语言入口
	if(isset($defultlang)) {
		$dsql->ExecuteNoneQuery("Update `#@__sysconfig` set `value`='$langName' where `varname`='cfg_df_lang' ");
		ReWriteConfig();
	}
	
	ShowMsg('成功增加语言入口，请为其增加子栏目！','catalog_main.php');
	exit();
}
/*---------------------
function action_save(){ }
---------------------*/
else if($dopost=='save')
{
	$description = Html2Text($description,1);
	$keywords = Html2Text($keywords,1);
	
	//用拼音命名作目录名
	if( ($upinyin==1 || $typedir=='') && $ispart!=2 )
	{
		$typedir = GetPinyin(stripslashes($typename));
	}
	
	if($ispart != 2)
	{
		$typedir = $nextdir.'/'.$typedir;
		$typedir = ereg_replace("/{1,}","/",$typedir);
	}
		
	//创建目录
	if($isdefault > 0 && $ispart != 2)
	{
		$true_typedir = str_replace("{cmspath}",$cfg_cmspath,$typedir);
		$true_typedir = ereg_replace("/{1,}","/",$true_typedir);
		if(!CreateDir($true_typedir)) {
				ShowMsg("创建目录 {$true_typedir} 失败，请检查你的路径是否存在问题！","-1");
				exit();
		}
	}
	
	$row = $dsql->GetOne("Select nid From `#@__channeltype` where id='$channeltype' ");
	$nid = $row['nid'];
	$df_tempindex = str_replace("{nid}", $nid, $cfg_df_tempindex);
	$df_templist = str_replace("{nid}", $nid, $cfg_df_templist);
	$df_temparticle = str_replace("{nid}", $nid, $cfg_df_temparticle);
	
	$in_query = "insert into `#@__arctype`(reid,topid,sortrank,typename,typedir,lang,isdefault,defaultname,channeltype,
    tempsgpage,tempindex,templist,temparticle,namerule,namerule2,ispart,description,keywords,`content`)
    Values('$reid','$topid','$sortrank','$typename','$typedir','$lang','$isdefault','$defaultname','$channeltype',
    '{$cfg_df_tempsgpage}','{$df_tempindex}','{$df_templist}','{$df_temparticle}','{$cfg_df_namerule}','{$cfg_df_namerule2}','$ispart','$description','$keywords','$content')";

	if(!$dsql->ExecuteNoneQuery($in_query))
	{
		ShowMsg("保存目录数据时失败，请检查你的输入资料是否存在问题！","-1");
		exit();
	}
	UpDateCatCache();
	PutCookie('lastCid', $topid, 3600*24,'/');
	ShowMsg("成功创建一个分类！","catalog_main.php");
	exit();

}

//增加顶级语言栏目
if($listtype=='all')
{
	include DedeInclude('templets/catalog_add_top.htm');
}
//增加子栏目
else
{
	//读取频道模型信息
	$dsql->SetQuery("select id,typename,nid from `#@__channeltype` where id<>-1 And isshow=1 order by id");
	$dsql->Execute();
	while($row=$dsql->GetObject())
	{
		$channelArray[$row->id]['typename'] = $row->typename;
		$channelArray[$row->id]['nid'] = $row->nid;
		if($row->id==$channelid) $nid = $row->nid;
	}
	//获取从父目录继承的默认参数
	$myrow = $dsql->GetOne(" Select tp.*,ch.typename as ctypename From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id=$id ");
	$channelid = $myrow['channeltype'];
	$topid = $myrow['topid'];
	$typedir = $myrow['typedir'];
	$lang = $myrow['lang'];
	$isdefault = $myrow['isdefault'];
	include DedeInclude('templets/catalog_add.htm');
}

?>