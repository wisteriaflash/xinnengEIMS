<?php
if(!defined('DEDEINC')) exit("Request Error!");
function lib_cattree(&$ctag,&$refObj)
{
	global $dsql,$cfg_df_lang;
	//属性处理
	//属性 showall 在空或不存在时，强制用产品模型id；如果是 yes 刚显示整个语言区栏目树；为其它数字则是这个数字的模型的id
	//typeid 指定顶级树 id ，指定后，前一个属性将无效
	$attlist="showall|,catid|0";
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	$revalue = '';
	$lang = (isset($refObj->dtp->tplLang) ? $refObj->dtp->tplLang : $cfg_df_lang);

	if(empty($typeid))
	{
		if( isset($refObj->TypeLink->TypeInfos['id']) ) {
			$typeid = $refObj->TypeLink->TypeInfos['id'];
			$reid = $refObj->TypeLink->TypeInfos['reid'];
			$topid = $refObj->TypeLink->TypeInfos['topid'];
			$lang = $refObj->TypeLink->TypeInfos['lang'];
			$channeltype = $refObj->TypeLink->TypeInfos['channeltype'];
			$ispart = $refObj->TypeLink->TypeInfos['ispart'];
			if($reid==0) $topid = $typeid;
		} else {
	  	$typeid = $reid = $topid = $channeltype = $ispart = 0;
		}
	}
	else
	{
		$row = $dsql->GetOne("Select reid,topid,lang,channeltype,ispart From `#@__arctype` where id='$typeid' ");
		if(!is_array($row)) {
			$typeid = $reid = $topid = $channeltype = $ispart = 0;
		} else {
			$reid = $row['reid'];
			$topid = $row['topid'];
			$lang = $row['lang'];
			$channeltype = $row['channeltype'];
			$ispart = $row['ispart'];
		}
	}
	if( !empty($catid) ) {
		$topQuery = "Select id,typename,typedir,isdefault,defaultname,ispart,namerule2 From `#@__arctype` where reid='$catid' And ishidden<>1 ";
	} else
	{
		if($showall == "yes" ) {
			$topQuery = "Select id,typename,typedir,isdefault,defaultname,ispart,namerule2 From `#@__arctype` where reid='$topid' ";
		}
		else
		{
		   if($showall=='')
		   {
		   		if( $ispart < 2 && !empty($channeltype) ) $showall = $channeltype;
		   		else $showall = 6;
		   }
		   $topQuery = "Select id,typename,typedir,isdefault,defaultname,ispart,namerule2 From `#@__arctype` where reid='{$topid}' And channeltype='{$showall}' And ispart<2 And ishidden<>1 ";
		}
	}
  $dsql->Execute('t', $topQuery);
  while($row = $dsql->GetArray('t'))
  {
  	$row['typelink'] = GetTypeUrl($row['id'],MfTypedir($row['typedir']),$row['isdefault'],$row['defaultname'],$row['ispart'],$row['namerule2']);
	
	$revalue .= "<li>\n";
	$revalue .= "<h4 class='sort'><a href='{$row['typelink']}'>{$row['typename']}</a></h4>";
	$revalue .= "<ul class='menu'>";
	cattreeListSon($row['id'], $revalue);
	$revalue .= "</ul></li>\n";
  }
	return $revalue;
}

function cattreeListSon($id, &$revalue)
{
	global $dsql;
	$query = "Select id,typename,typedir,isdefault,defaultname,ispart,namerule2 From `#@__arctype` where reid='{$id}' And ishidden<>1 ";
	$dsql->Execute($id, $query);
	$thisv = '';
  while($row = $dsql->GetArray($id))
  {
  	$row['typelink'] = GetTypeUrl($row['id'],MfTypedir($row['typedir']),$row['isdefault'],$row['defaultname'],$row['ispart'],$row['namerule2']);
	$revalue .= "<li>\n";
	$revalue .= "<h4 class='sort'><a href='{$row['typelink']}'>{$row['typename']}</a></h4>";
	$revalue .= "<ul class='menu invisible'>";
	cattreeListSon($row['id'], $revalue);
	
	$revalue .= "</ul></li>\n";
  }
  if($thisv!='') $revalue .= "	<dd>\n$thisv	</dd>\n";
}


?>