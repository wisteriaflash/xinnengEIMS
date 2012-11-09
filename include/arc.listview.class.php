<?php

if(!defined('DEDEINC')) exit('Request Error!');
require_once(DEDEINC."/arc.partview.class.php");

@set_time_limit(0);
class ListView
{
	var $dsql;
	var $dtp;
	var $dtp2;
	var $Lang;
	var $TypeID;
	var $TypeLink;
	var $PageNo;
	var $TotalPage;
	var $TotalResult;
	var $PageSize;
	var $ChannelUnit;
	var $ListType;
	var $Fields;
	var $PartView;
	var $upPageType;
	var $addSql;
	var $IsError;
	var $CrossID;
	var $IsReplace;
	//php5构造函数
	function __construct($typeid,$uppage=1)
	{
		global $dsql,$user_lang;
		$this->TypeID = $typeid;
		$this->dsql = $dsql;
		$this->Lang = '';
		$this->CrossID = '';
		$this->IsReplace = false;
		$this->IsError = false;
		$this->dtp = new DedeTagParse();
		$this->dtp->refObj = $this;
		$this->dtp->SetNameSpace("dede","{","}");
		$this->dtp2 = new DedeTagParse();
		$this->dtp2->SetNameSpace("field","[","]");
		$this->TypeLink = new TypeLink($typeid);
		$this->upPageType = $uppage;
		if(!is_array($this->TypeLink->TypeInfos))
		{
			$this->IsError = true;
		}
		if(!$this->IsError)
		{
			$this->Lang = $this->TypeLink->TypeInfos['lang'];
			$this->ChannelUnit = new ChannelUnit($this->TypeLink->TypeInfos['channeltype']);
			$this->Fields = $this->TypeLink->TypeInfos;
			$this->Fields['id'] = $typeid;
			$this->Fields['position'] = $this->TypeLink->GetPositionLink(true);
			$this->Fields['title'] = ereg_replace("[<>]"," / ",$this->TypeLink->GetPositionLink(false));

			//设置一些全局参数的值
			foreach($GLOBALS['PubFields'] as $k=>$v) $this->Fields[$k] = $v;
			$this->Fields['rsslink'] = $GLOBALS['cfg_cmsurl']."/data/rss/".$this->TypeID.".xml";

			//设置环境变量
			SetSysEnv($this->TypeID,$this->Fields['typename'],0,'','list');
			$this->Fields['typeid'] = $this->TypeID;

		}//!error

	}

	//php4构造函数
	function ListView($typeid,$uppage=0){
		$this->__construct($typeid,$uppage);
	}
	//关闭相关资源
	function Close()
	{

	}

	//统计列表里的记录
	function CountRecord()
	{
		global $cfg_list_son,$cfg_need_typeid2;
		if(empty($cfg_need_typeid2)) $cfg_need_typeid2 = 'N';
		
		//统计数据库记录
		$this->TotalResult = -1;
		if(isset($GLOBALS['TotalResult'])) $this->TotalResult = $GLOBALS['TotalResult'];
		if(isset($GLOBALS['PageNo'])) $this->PageNo = $GLOBALS['PageNo'];
		else $this->PageNo = 1;
		$this->addSql  = " arc.arcrank > -1 ";
		
		if($cfg_list_son=='N')
		{
			$this->addSql .= " And (arc.typeid='".$this->TypeID."') ";
		}
		else
		{
			$this->addSql .= " And ( arc.typeid in (".GetSonIds($this->TypeID,$this->Fields['channeltype']).") ) ";
		}
		
		if($this->TotalResult==-1)
		{
			$cquery = "Select count(*) as dd From `#@__arctiny` arc where ".$this->addSql;
			$row = $this->dsql->GetOne($cquery);
			if(is_array($row))
			{
				$this->TotalResult = $row['dd'];
			}
			else
			{
				$this->TotalResult = 0;
			}
		}

		//初始化列表模板，并统计页面总数
		$tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$this->TypeLink->TypeInfos['templist'];
		//echo $this->TypeLink->TypeInfos['templist']."<hr />";
		//echo $this->TypeLink->TypeInfos['templist'];
		$tempfile = str_replace("{tid}",$this->TypeID,$tempfile);
		$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
		if(!file_exists($tempfile))
		{
			$tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$GLOBALS['cfg_df_style']."/list_default.htm";
		}
		if(!file_exists($tempfile)||!is_file($tempfile))
		{
			$tempfile = ereg_replace("^[^/\\]*/", "/", $tempfile);
			echo "模板文件 {$tempfile} 不存在，无法解析文档！";
			exit();
		}
		$this->dtp->LoadTemplate($tempfile);
		$this->dtp->handLang = $this->Lang;
		$ctag = $this->dtp->GetTag("page");
		if(!is_object($ctag))
		{
			$ctag = $this->dtp->GetTag("list");
		}
		if(!is_object($ctag))
		{
			$this->PageSize = 20;
		}
		else
		{
			if($ctag->GetAtt("pagesize")!="")
			{
				$this->PageSize = $ctag->GetAtt("pagesize");
			}
			else
			{
				$this->PageSize = 20;
			}
		}
		$this->TotalPage = ceil($this->TotalResult/$this->PageSize);
	}

	//列表创建HTML
	function MakeHtml($startpage=1,$makepagesize=0)
	{
		if(empty($startpage))
		{
			$startpage = 1;
		}

		//创建封面模板文件
		if($this->TypeLink->TypeInfos['isdefault']==-1)
		{
			echo '这个类目是动态类目！';
			return '../plus/list.php?tid='.$this->TypeLink->TypeInfos['id'];
		}

		//单独页面
		else if($this->TypeLink->TypeInfos['ispart']>0)
		{
			$reurl = $this->MakePartTemplets();
			return $reurl;
		}

		$this->CountRecord();
		//初步给固定值的标记赋值
		$this->ParseTempletsFirst();
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage==0)
		{
			$totalpage = 1;
		}
		CreateDir(MfTypedir($this->Fields['typedir']));
		$murl = '';
		if($makepagesize > 0)
		{
			$endpage = $startpage+$makepagesize;
		}
		else
		{
			$endpage = ($totalpage+1);
		}
		if( $endpage >= $totalpage+1 )
		{
			$endpage = $totalpage+1;
		}
		if($endpage==1)
		{
			$endpage = 2;
		}
		for($this->PageNo=$startpage; $this->PageNo < $endpage; $this->PageNo++)
		{
			$this->ParseDMFields($this->PageNo,1);
			$makeFile = $this->GetMakeFileRule($this->Fields['id'],'list',$this->Fields['typedir'],'',$this->Fields['namerule2']);
			$makeFile = str_replace("{page}",$this->PageNo,$makeFile);
			$murl = $makeFile;
			if(!ereg("^/",$makeFile))
			{
				$makeFile = "/".$makeFile;
			}
			$makeFile = $this->GetTruePath().$makeFile;
			$makeFile = ereg_replace("/{1,}","/",$makeFile);
			$murl = $this->GetTrueUrl($murl);
			$this->dtp->SaveTo($makeFile);
		}
		if($startpage==1)
		{
			//如果列表启用封面文件，复制这个文件第一页
			if($this->TypeLink->TypeInfos['isdefault']==1
			&& $this->TypeLink->TypeInfos['ispart']==0)
			{
				$onlyrule = $this->GetMakeFileRule($this->Fields['id'],"list",$this->Fields['typedir'],'',$this->Fields['namerule2']);
				$onlyrule = str_replace("{page}","1",$onlyrule);
				$list_1 = $this->GetTruePath().$onlyrule;
				$murl = MfTypedir($this->Fields['typedir']).'/'.$this->Fields['defaultname'];
				$indexname = $this->GetTruePath().$murl;
				copy($list_1,$indexname);
			}
		}
		return $murl;
	}

	//显示列表
	function Display()
	{
		if($this->TypeLink->TypeInfos['ispart']>0)
		{
			$this->DisplayPartTemplets();
			return ;
		}
		$this->CountRecord();
		if((empty($this->PageNo) || $this->PageNo==1)
		&& $this->TypeLink->TypeInfos['ispart']==1)
		{
			$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			$tempfile = $tmpdir."/".$tempfile;
			if(!file_exists($tempfile))
			{
				$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/index_default.htm";
			}
			$this->dtp->LoadTemplate($tempfile);
			$this->dtp->handLang = $this->Lang;
		}
		$this->ParseTempletsFirst();
		$this->ParseDMFields($this->PageNo,0);
		$this->dtp->Display();
	}

	//创建单独模板页面
	function MakePartTemplets()
	{
		$this->PartView = new PartView($this->TypeID,false);
		$this->PartView->SetTypeLink($this->TypeLink);
		$this->PartView->Lang = $this->Lang;
		$nmfa = 0;
		$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
		if($this->Fields['ispart']==1)
		{
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			$tempfile = str_replace("{lang}",$this->Fields['lang'],$tempfile);
			$tempfile = $tmpdir."/".$tempfile;
			if(!file_exists($tempfile)) {
				$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/{$this->Fields['lang']}/index_article.htm";
				if(!file_exists($tempfile)) $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/en/index_article.htm";
			}
			$this->PartView->SetTemplet($tempfile);
		}
		//跳转网址
		else if($this->Fields['ispart']==2)
		{
			return $this->Fields['typedir'];
		}
		//单独页面
		else if($this->Fields['ispart']==3)
		{
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempsgpage']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			$tempfile = str_replace("{lang}",$this->Fields['lang'],$tempfile);
			$tempfile = $tmpdir."/".$tempfile;
			if(!file_exists($tempfile)) {
				$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/{$this->Fields['lang']}/catalog_sgpage.htm";
				if(!file_exists($tempfile)) $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/en/catalog_sgpage.htm";
			}
			$this->PartView->SetTemplet($tempfile);
		}
		CreateDir(MfTypedir($this->Fields['typedir']));
		$makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",MfTypedir($this->Fields['typedir']),
		                                                  $this->Fields['defaultname'],$this->Fields['namerule2']);
		$makeUrl = ereg_replace("/{1,}","/",$makeUrl);
		$makeFile = $this->GetTruePath().$makeUrl;
		if($nmfa==0)
		{
			$this->PartView->SaveToHtml($makeFile);
		} else
		{
			if(!file_exists($makeFile)) {
				$this->PartView->SaveToHtml($makeFile);
			}
		}
		return $this->GetTrueUrl($makeUrl);
	}

	//显示单独模板页面
	function DisplayPartTemplets()
	{
		$this->PartView = new PartView($this->TypeID,false);
		$this->PartView->SetTypeLink($this->TypeLink);
		$this->PartView->Lang = $this->Lang;
		$nmfa = 0;
		$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
		if($this->Fields['ispart']==1)
		{
			//封面模板
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			$tempfile = str_replace("{lang}",$this->Fields['lang'],$tempfile);
			$tempfile = $tmpdir."/".$tempfile;
			if(!file_exists($tempfile)) {
				$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/{$this->Fields['lang']}/index_article.htm";
				if(!file_exists($tempfile)) $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/en/index_article.htm";
			}
			$this->PartView->SetTemplet($tempfile);
		}
		else if($this->Fields['ispart']==2)
		{
			//跳转网址
			$gotourl = $this->Fields['typedir'];
			header("Location:$gotourl");
			exit();
		}
		else if($this->Fields['ispart']==3)
		{
			//封面模板
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempsgpage']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			$tempfile = str_replace("{lang}",$this->Fields['lang'],$tempfile);
			$tempfile = $tmpdir."/".$tempfile;
			if(!file_exists($tempfile)) {
				$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/{$this->Fields['lang']}/catalog_sgpage.htm";
				if(!file_exists($tempfile)) $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/en/catalog_sgpage.htm";
			}
			$this->PartView->SetTemplet($tempfile);
		}
		CreateDir(MfTypedir($this->Fields['typedir']));
		$makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",MfTypedir($this->Fields['typedir']),$this->Fields['defaultname'],$this->Fields['namerule2']);
		$makeFile = $this->GetTruePath().$makeUrl;
		if($nmfa==0)
		{
			$this->PartView->Display();
		}
		else
		{
			if(!file_exists($makeFile))
			{
				$this->PartView->Display();
			}
			else
			{
				include($makeFile);
			}
		}
	}

	//获得站点的真实根路径
	function GetTruePath()
	{
		$truepath = $GLOBALS["cfg_basedir"];
		return $truepath;
	}

	//获得真实连接路径
	function GetTrueUrl($nurl)
	{
		return $nurl;
	}

	//解析模板，对固定的标记进行初始给值
	function ParseTempletsFirst()
	{
		if(isset($this->TypeLink->TypeInfos['reid']))
		{
			$GLOBALS['envs']['reid'] = $this->TypeLink->TypeInfos['reid'];
		}
		$GLOBALS['envs']['typeid'] = $this->TypeID;
		$GLOBALS['envs']['topid'] = GetTopid($this->Fields['typeid']);
		$GLOBALS['envs']['cross'] = 1;
		MakeOneTag($this->dtp,$this);
	}

	//解析模板，对内容里的变动进行赋值
	function ParseDMFields($PageNo,$ismake=1)
	{
		//替换第二页后的内容
		if(($PageNo>1 || strlen($this->Fields['content'])<10 ) && !$this->IsReplace)
		{
			$this->dtp->SourceString = str_replace('[cmsreplace]','display:none',$this->dtp->SourceString);
			$this->IsReplace = true;
		}
		foreach($this->dtp->CTags as $tagid=>$ctag)
		{
			if($ctag->GetName()=="list")
			{
				$limitstart = ($this->PageNo-1) * $this->PageSize;
				$row = $this->PageSize;
				$InnerText = trim($ctag->GetInnerText());
				//print_r($ctag->CAttribute->Items);
				//echo $ctag->GetAtt("listtype");
				$this->dtp->Assign($tagid,
					$this->GetArcList(
						$limitstart,
						$row,
						$ctag->GetAtt("col"),
						$ctag->GetAtt("titlelen"),
						$ctag->GetAtt("infolen"),
						$ctag->GetAtt("imgwidth"),
						$ctag->GetAtt("imgheight"),
						$ctag->GetAtt("listtype"),
						$ctag->GetAtt("orderby"),
						$InnerText,
						$ctag->GetAtt("tablewidth"),
						$ismake,
						$ctag->GetAtt("orderway")
						)
				);
			}
			else if($ctag->GetName()=="pagelist")
			{
				$list_len = trim($ctag->GetAtt("listsize"));
				$ctag->GetAtt("listitem")=="" ? $listitem="index,pre,pageno,next,end,option" : $listitem=$ctag->GetAtt("listitem");
				if($list_len=="")
				{
					$list_len = 3;
				}
				if($ismake==0)
				{
					$this->dtp->Assign($tagid,$this->GetPageListDM($list_len,$listitem));
				}
				else
				{
					$this->dtp->Assign($tagid,$this->GetPageListST($list_len,$listitem));
				}
			}
			else if($PageNo!=1 && $ctag->GetName()=='field' && $ctag->GetAtt('display')!='')
			{
				$this->dtp->Assign($tagid,'');
			}
		}
	}

	//获得要创建的文件名称规则
	function GetMakeFileRule($typeid,$wname,$typedir,$defaultname,$namerule2)
	{
		$typedir = MfTypedir($typedir);
		if($wname=='index')
		{
			return $typedir.'/'.$defaultname;
		}
		else
		{
			$namerule2 = str_replace('{tid}',$typeid,$namerule2);
			$namerule2 = str_replace('{typedir}',$typedir,$namerule2);
			return $namerule2;
		}
	}

	//获得一个单列的文档列表
	function GetArcList($limitstart=0,$row=10,$col=1,$titlelen=30,$infolen=250,
	$imgwidth=120,$imgheight=90,$listtype="all",$orderby="default",$innertext="",$tablewidth="100",$ismake=1,$orderWay='desc')
	{
		global $cfg_list_son;
		
		$typeid=$this->TypeID;
		
		if($row=='') $row = 10;
		if($limitstart=='') $limitstart = 0;
		if($titlelen=='') $titlelen = 100;
		if($infolen=='') $infolen = 250;
		if($imgwidth=='') $imgwidth = 120;
		if($imgheight=='') $imgheight = 120;
		if($listtype=='') $listtype = 'all';
		if($orderWay=='') $orderWay = 'desc';
		
		if($orderby=='') {
			$orderby='default';
		}
		else {
			$orderby=strtolower($orderby);
		}
		
		$tablewidth = str_replace('%','',$tablewidth);
		if($tablewidth=='') $tablewidth=100;
		if($col=='') $col=1;
		$colWidth = ceil(100/$col);
		$tablewidth = $tablewidth.'%';
		$colWidth = $colWidth.'%';
		
		$innertext = trim($innertext);
		if($innertext=='') {
			if($listtype=='img' || $listtype=='image') $innertext = GetSysTemplets('list_fulllist_img.htm');
			else $innertext = GetSysTemplets('list_fulllist.htm');
		}

		//排序方式
		$ordersql = '';
		if($orderby=="senddate" || $orderby=="id") {
			$ordersql=" order by arc.id $orderWay";
		}
		else if($orderby=="hot" || $orderby=="click") {
			$ordersql = " order by arc.click $orderWay";
		}
		else if($orderby=="lastpost") {
			$ordersql = "  order by arc.lastpost $orderWay";
		}
		else {
			$ordersql=" order by arc.sortrank $orderWay";
		}

		//获得附加表的相关信息
		$addtable  = $this->ChannelUnit->ChannelInfos['addtable'];
		if($addtable!="")
		{
			$addJoin = " left join `$addtable` on arc.id = ".$addtable.'.aid ';
			$addField = '';
			$fields = explode(',',$this->ChannelUnit->ChannelInfos['listfields']);
			foreach($fields as $k=>$v)
			{
				$nfields[$v] = $k;
			}
			if(is_array($this->ChannelUnit->ChannelFields) && !empty($this->ChannelUnit->ChannelFields))
			{
				foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
				{
					if(isset($nfields[$k]))
					{
						if(!empty($arr['rename'])) {
							$addField .= ','.$addtable.'.'.$k.' as '.$arr['rename'];
						}
						else {
							$addField .= ','.$addtable.'.'.$k;
						}
					}
				}
			}
		}
		else
		{
			$addField = '';
			$addJoin = '';
		}

		//如果不用默认的sortrank或id排序，使用联合查询（数据量大时非常缓慢）
		if(ereg('hot|click|lastpost',$orderby))
		{
			$query = "Select arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,
		   tp.namerule,tp.namerule2,tp.ispart
		   $addField
		   from `#@__archives` arc
		   left join `#@__arctype` tp on arc.typeid=tp.id
		   $addJoin
		   where {$this->addSql} $ordersql limit $limitstart,$row";
		}
		//普通情况先从arctiny表查出ID，然后按ID查询（速度非常快）
		else
		{
			$t1 = ExecTime();
			$ids = array();
			$query = "Select id From `#@__arctiny` arc where {$this->addSql} $ordersql limit $limitstart,$row ";
			$this->dsql->SetQuery($query);
			$this->dsql->Execute();
			while($arr=$this->dsql->GetArray())
			{
				$ids[] = $arr['id'];
			}
			$idstr = join(',',$ids);
			if($idstr=='')
			{
				return '';
			}
			else
			{
				$query = "Select arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,
		   			tp.namerule,tp.namerule2,tp.ispart
		   			$addField
		   			from `#@__archives` arc left join `#@__arctype` tp on arc.typeid=tp.id
		   			$addJoin
		   			where arc.id in($idstr) $ordersql ";
			}
			$t2 = ExecTime();
			//echo $t2-$t1;

		}
		$this->dsql->SetQuery($query);
		$this->dsql->Execute('al');
		$t2 = ExecTime();

		//echo $t2-$t1;
		$artlist = '';
		$this->dtp2->LoadSource($innertext);
		$this->dtp2->handLang = $this->Lang;
		$GLOBALS['autoindex'] = 0;
		for($i=0;$i<$row;$i++)
		{
			if($col>1)
			{
				$artlist .= "<div>\r\n";
			}
			for($j=0;$j<$col;$j++)
			{
				if($row = $this->dsql->GetArray("al"))
				{
					$GLOBALS['autoindex']++;
					$ids[$row['id']] = $row['id'];

					//处理一些特殊字段
					$row['infos'] = cn_substr($row['description'],$infolen);
					$row['id'] =  $row['id'];

					$row['filename'] = $row['arcurl'] = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
					$row['arcrank'],$row['namerule'],$row['typedir'],$row['filename']);
					$row['typeurl'] = GetTypeUrl($row['typeid'],MfTypedir($row['typedir']),$row['isdefault'],$row['defaultname'],
					$row['ispart'],$row['namerule2']);
					
					//没缩略图
					if($row['litpic'] == '-' || $row['litpic'] == '')
					{
						$row['litpic'] = $GLOBALS['cfg_templeturl'].'/images/defaultpic.gif';
					}
					//没权限文档不显示缩略图
					if($row['arcrank'] > 0)
					{
						$row['litpic'] = $GLOBALS['cfg_templeturl'].'/images/nopurview.gif';
					}
					
					if(!eregi("^http://",$row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
					{
						$row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
					}
					$row['picname'] = $row['litpic'];
					$row['stime'] = GetDateMK($row['pubdate']);
					$row['typelink'] = "<a href='".$row['typeurl']."'>".$row['typename']."</a>";
					$row['image'] = "<img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".ereg_replace("['><]","",$row['title'])."'>";
					$row['imglink'] = "<a href='".$row['filename']."'>".$row['image']."</a>";
					$row['fulltitle'] = $row['title'];
					$row['title'] = cn_substr($row['title'],$titlelen);
					if($row['color']!='')
					{
						$row['title'] = "<font color='".$row['color']."'>".$row['title']."</font>";
					}
					if(ereg('c',$row['flag']))
					{
						$row['title'] = "<b>".$row['title']."</b>";
					}
					$row['textlink'] = "<a href='".$row['filename']."'>".$row['title']."</a>";
					$row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
					$row['memberurl'] = $GLOBALS['cfg_memberurl'];
					$row['templeturl'] = $GLOBALS['cfg_templeturl'];

					//编译附加表里的数据
					foreach($row as $k=>$v)
					{
						$row[strtolower($k)] = $v;
					}
					foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
					{
						if(isset($row[$k]))
						{
							$row[$k] = $this->ChannelUnit->MakeField($k,$row[$k]);
						}
					}
					if(is_array($this->dtp2->CTags))
					{
						foreach($this->dtp2->CTags as $k=>$ctag)
						{
							if($ctag->GetName()=='array')
							{
								//传递整个数组，在runphp模式中有特殊作用
								$this->dtp2->Assign($k,$row);
							}
							else
							{
								if(isset($row[$ctag->GetName()]))
								{
									$this->dtp2->Assign($k,$row[$ctag->GetName()]);
								}
								else
								{
									$this->dtp2->Assign($k,'');
								}
							}
						}
					}
					$artlist .= $this->dtp2->GetResult();
				}//if hasRow

			}//Loop Col

			if($col>1)
			{
				$i += $col - 1;
				$artlist .= "	</div>\r\n";
			}
		}//Loop Line

		$t3 = ExecTime();

		//echo ($t3-$t2);
		$this->dsql->FreeResult('al');
		return $artlist;
	}

	//获取静态的分页列表
	function GetPageListST($list_len,$listitem="index,end,pre,next,pageno")
	{
		$GLOBALS['userLang'] = $this->Lang;
		$prepage="";
		$nextpage="";
		$prepagenum = $this->PageNo-1;
		$nextpagenum = $this->PageNo+1;
		if($list_len==""||ereg("[^0-9]",$list_len))
		{
			$list_len=3;
		}
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage<=1 && $this->TotalResult>0)
		{

			return " <span class=\"pageinfo\">".GetLang('total')." <strong>1</strong> ".GetLang('page')." <strong>".$this->TotalResult."</strong> ".GetLang('records')."</span> ";
		}
		if($this->TotalResult == 0)
		{
			return " <span class=\"pageinfo\">".GetLang('total')." <strong>0</strong> ".GetLang('page')." <strong>".$this->TotalResult."</strong> ".GetLang('records')."</span> ";
		}
		$purl = $this->GetCurUrl();
		$maininfo = "<span class=\"pageinfo\">".GetLang('total')." <strong>{$totalpage}</strong> ".GetLang('page')." <strong>".$this->TotalResult."</strong> ".GetLang('item')."</span> ";
		$tnamerule = $this->GetMakeFileRule($this->Fields['id'],"list",$this->Fields['typedir'],$this->Fields['defaultname'],$this->Fields['namerule2']);
		$tnamerule = ereg_replace('^(.*)/','',$tnamerule);

		//获得上一页和主页的链接
		if($this->PageNo != 1)
		{
			$prepage.=" <a href='".str_replace("{page}",$prepagenum,$tnamerule)."'>".GetLang('prepage')."</a> \r\n";
			$indexpage=" <a href='".str_replace("{page}",1,$tnamerule)."'>".GetLang('firstpage')."</a> \r\n";
		}
		else
		{
			$indexpage=" ".GetLang('firstpage')." \r\n";
		}

		//下一页,未页的链接
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.=" <a href='".str_replace("{page}",$nextpagenum,$tnamerule)."'>".GetLang('nextpage')."</a> \r\n";
			$endpage=" <a href='".str_replace("{page}",$totalpage,$tnamerule)."'>".GetLang('lastpage')."</a> \r\n";
		}
		else
		{
			$endpage=" ".GetLang('lastpage')." ";
		}

		//获得数字链接
		$listdd = '';
		$total_list = $list_len * 2 + 1;
		if($this->PageNo >= $total_list)
		{
			$j = $this->PageNo-$list_len;
			$total_list = $this->PageNo+$list_len;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		else
		{
			$j=1;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		for($j;$j<=$total_list;$j++)
		{
			if($j==$this->PageNo)
			{
				$listdd.= " $j \r\n";
			}
			else
			{
				$listdd.=" <a href='".str_replace("{page}",$j,$tnamerule)."'>[".$j."]</a> \r\n";
			}
		}
		$optionlist = '';
		$plist = "";
		if(eregi('info',$listitem))
		{
			$plist .= $maininfo.' ';
		}
		if(eregi('index',$listitem))
		{
			$plist .= $indexpage.' ';
		}
		if(eregi('pre',$listitem))
		{
			$plist .= $prepage.' ';
		}
		if(eregi('pageno',$listitem))
		{
			$plist .= $listdd.' ';
		}
		if(eregi('next',$listitem))
		{
			$plist .= $nextpage.' ';
		}
		if(eregi('end',$listitem))
		{
			$plist .= $endpage.' ';
		}
		if(eregi('option',$listitem))
		{
			$plist .= $optionlist;
		}
		return $plist;
	}

	//获取动态的分页列表
	function GetPageListDM($list_len,$listitem="index,end,pre,next,pageno")
	{
		$GLOBALS['userLang'] = $this->Lang;
		$prepage="";
		$nextpage="";
		$prepagenum = $this->PageNo-1;
		$nextpagenum = $this->PageNo+1;
		if($list_len==""||ereg("[^0-9]",$list_len))
		{
			$list_len=3;
		}
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage<=1 && $this->TotalResult>0)
		{
			return " <span class=\"pageinfo\">".GetLang('total').' 1 '.GetLang('page')."/".$this->TotalResult.' '.GetLang('records')."</span> ";
		}
		if($this->TotalResult == 0)
		{
			return " <span class=\"pageinfo\">".GetLang('total').' 0 '.GetLang('page')."/".$this->TotalResult.' '.GetLang('records')."</span> ";
		}

		$purl = $this->GetCurUrl();

		$geturl = "tid=".$this->TypeID."&TotalResult=".$this->TotalResult."&";
		$hidenform = "<input type='hidden' name='tid' value='".$this->TypeID."'>\r\n";
		$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";
		$purl .= "?".$geturl;

		//获得上一页和下一页的链接
		if($this->PageNo != 1)
		{
			$prepage.=" <a href='".$purl."PageNo=$prepagenum'>".GetLang('prepage')."</a> \r\n";
			$indexpage=" <a href='".$purl."PageNo=1'>".GetLang('firstpage')."</a> \r\n";
		}
		else
		{
			$indexpage=" <a>".GetLang('firstpage')."</a> \r\n";
		}
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.=" <a href='".$purl."PageNo=$nextpagenum'>".GetLang('nextpage')."</a> \r\n";
			$endpage=" <a href='".$purl."PageNo=$totalpage'>".GetLang('lastpage')."</a> \r\n";
		}
		else
		{
			$endpage=" <a>".GetLang('lastpage')."</a> ";
		}


		//获得数字链接
		$listdd="";
		$total_list = $list_len * 2 + 1;
		if($this->PageNo >= $total_list)
		{
			$j = $this->PageNo-$list_len;
			$total_list = $this->PageNo+$list_len;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		else
		{
			$j=1;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		for($j;$j<=$total_list;$j++)
		{
			if($j==$this->PageNo)
			{
				$listdd.= " <a>$j</a> \r\n";
			}
			else
			{
				$listdd.=" <a href='".$purl."PageNo=$j'>[".$j."]</a> \r\n";
			}
		}

		$plist = $indexpage.$prepage.$listdd.$nextpage.$endpage;

		return $plist;
	}

	//获得当前的页面文件的url
	function GetCurUrl()
	{
		if(!empty($_SERVER["REQUEST_URI"]))
		{
			$nowurl = $_SERVER["REQUEST_URI"];
			$nowurls = explode("?",$nowurl);
			$nowurl = $nowurls[0];
		}
		else
		{
			$nowurl = $_SERVER["PHP_SELF"];
		}
		return $nowurl;
	}
}//End Class
?>