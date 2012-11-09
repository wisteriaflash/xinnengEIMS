<?php
function lib_langlist(&$ctag,&$refObj)
{
	global $dsql;

	$attlist = "row|100,col|1,currentstyle|";
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	$innertext = $ctag->GetInnerText();

	
	if($innertext=='') $innertext = GetSysTemplets("lang_list.htm");

	$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  From `#@__arctype` where reid=0 order by sortrank asc limit 0,$row";
		  
	//And id<>'$typeid'
	$dtp2 = new DedeTagParse();
	$dtp2->SetNameSpace('field','[',']');
	$dtp2->LoadSource($innertext);
	
	$dsql->SetQuery($sql);
	$dsql->Execute();
	$line = $row;
	
	$lang = (isset($refObj->dtp->tplLang) ? $refObj->dtp->tplLang : '');
	
	$totalRow = $dsql->GetTotalRow();
	$likeType = '';
	
	$GLOBALS['autoindex'] = 0;
	for($i=0;$i < $line;$i++)
	{
		if($col>1) $likeType .= "<dl>\r\n";
		for($j=0; $j<$col; $j++)
		{
			if($col>1) $likeType .= "<dd>\r\n";
			if($row=$dsql->GetArray())
			{
				
				$regArr = array();
				preg_match_all("/(.*)\(([a-zA-Z0-9-]*)\)/", $row['typename'], $regArr);
				$row['langname'] = $regArr[1][0];
				$tlang = $regArr[2][0];
				
				//处理同级栏目中，当前栏目的样式
				if( $tlang==$lang && $currentstyle!='' )
				{
					$linkOkstr = $currentstyle;
					$row['typelink'] = GetOneTypeUrlA($row);
					$linkOkstr = str_replace("~typelink~",$row['typelink'],$linkOkstr);
					$linkOkstr = str_replace("~typename~",$row['typename'],$linkOkstr);
					$linkOkstr = str_replace("~langname~",$row['langname'],$linkOkstr);
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