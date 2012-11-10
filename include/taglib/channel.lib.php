<?php
function lib_channel(&$ctag,&$refObj)
{
	global $dsql,$cfg_df_lang;

	$attlist = "typeid|0,reid|0,row|100,col|1,type|son,get|all,currentstyle|,noself|";
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	$innertext = $ctag->GetInnerText();
	
	$lang = (isset($refObj->Lang) ? $refObj->Lang : '');
	if(empty($lang))
	{
		$lang = (isset($refObj->dtp->tplLang) ? $refObj->dtp->tplLang : $cfg_df_lang);
	}

	$reid = 0;
	$topid = 0;
	//如果属性里没指定栏目id，从引用类里获取栏目信息
	if(empty($typeid))
	{
		if( isset($refObj->TypeLink->TypeInfos['id']) )
		{
			$typeid = $refObj->TypeLink->TypeInfos['id'];
			$reid = $refObj->TypeLink->TypeInfos['reid'];
			$topid = $refObj->TypeLink->TypeInfos['topid'];
			if($reid==0) $topid = $typeid;
		}
		else {
	  	$typeid = 0;
	  }
	}
	//如果指定了栏目id，从数据库获取栏目信息
	else
	{
		$row2 = $dsql->GetOne("Select * From `#@__arctype` where id='$typeid' ");
		$typeid = $row2['id'];
		$reid = $row2['reid'];
		$topid = $row2['topid'];
		$issetInfos = true;
	}
	
	if(empty($topid))
	{
		$row3 = $dsql->GetOne("Select * From `#@__arctype` where reid=0 And lang ='$lang' ");
		$topid = ($row3['topid']==0 ? $row3['id'] : $row3['topid']);
	}
	
	if($type=='' || $type=='sun') $type='son';
	if($innertext=='') $innertext = GetSysTemplets("channel_list.htm");

	$likeType = '';
	if($get=='index')
	{
		$sql = "Select * From `#@__arctype` where id='$topid' ";
	}
	else if($get=='page')
	{
		//echo "== $topid  ==";
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  From `#@__arctype` where reid=$topid And ishidden<>1 And ispart=3 order by sortrank asc limit 0,$row";
	}
	else if($type=='top')
	{
		//echo "== $topid  ==";
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  From `#@__arctype` where reid=$topid And ishidden<>1 order by sortrank asc limit 0,$row";
	}
	else if($type=='son')
	{
		if($typeid==0) return '';
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  From `#@__arctype` where reid='$typeid' And ishidden<>1 order by sortrank asc limit 0,$row";
	}
	else if($type=='self')
	{
		if($reid==0) return '';
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
			From `#@__arctype` where reid='$reid' And ishidden<>1 order by sortrank asc limit 0,$row";
	}
	//And id<>'$typeid'
	$dtp2 = new DedeTagParse();
	$dtp2->SetNameSpace('field','[',']');
	$dtp2->LoadSource($innertext);
	$dtp2->tplLang = $lang;
	if(!empty($refObj->Lang)) $dtp2->handLang = $refObj->Lang;
	//检查是否有子栏目，并返回rel提示（用于二级菜单）
	$needRel = false;
	if(ereg(':rel', $innertext)) $needRel = true;
	
	//echo $sql."<hr />";
	$dsql->SetQuery($sql);
	$dsql->Execute();
	$line = $row;
	
	//如果用子栏目模式，当没有子栏目时显示同级栏目
	$totalRow = $dsql->GetTotalRow();
	if($type=='son' && $reid!=0 && $totalRow==0 && $noself=='')
	{
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
			From `#@__arctype` where reid='$reid' And ishidden<>1 order by sortrank asc limit 0,$row";
		$dsql->SetQuery($sql);
	  $dsql->Execute();
	}
	
	//echo $line.' - '.$col.' - '.$type.'|'.$totalRow.';';
	$GLOBALS['autoindex'] = 0;
	for($i=0;$i < $line;$i++)
	{
		if($col>1) $likeType .= "<dl>\r\n";
		for($j=0; $j<$col; $j++)
		{
			if($col>1) $likeType .= "<dd>\r\n";
			if($row=$dsql->GetArray())
			{
				$row['sonids'] = $row['rel'] = '';
				if($needRel)
				{
					$row['sonids'] = GetSonIds($row['id'], 0, false);
					if($row['sonids']=='') $row['rel'] = '';
					else $row['rel'] = " rel='dropmenu{$row['id']}'";
				}
				//处理同级栏目中，当前栏目的样式
				// if( ($row['id']==$typeid || ($topid==$row['id'] && $type=='top') ) && $currentstyle!='' )
				if( ($row['id']==$typeid || ($reid==$row['id'] && $type=='top') ) && $currentstyle!='' )
				{
					$linkOkstr = $currentstyle;
					$row['typelink'] = GetOneTypeUrlA($row);
					$linkOkstr = str_replace("~rel~",$row['rel'],$linkOkstr);
					$linkOkstr = str_replace("~typelink~",$row['typelink'],$linkOkstr);
					$linkOkstr = str_replace("~typename~",$row['typename'],$linkOkstr);
					$likeType .= $linkOkstr;
				}
				else
				{
					$row['typelink'] = $row['typeurl'] = GetOneTypeUrlA($row);
					if(is_array($dtp2->CTags))
					{
						foreach($dtp2->CTags as $tagid=>$ctag)
						{
							if(isset($row[$ctag->GetName()])) $dtp2->Assign($tagid,$row[$ctag->GetName()]);
						}
					}
					$likeType .= $dtp2->GetResult();
				}
			}
			if($col>1) $likeType .= "</dd>\r\n";
			$GLOBALS['autoindex']++;
		}
		//Loop Col
		if($col>1)
		{
			$i += $col - 1;
			$likeType .= "	</dl>\r\n";
		}
	}
	//Loop for $i
	$dsql->FreeResult();
	return $likeType;
}
?>