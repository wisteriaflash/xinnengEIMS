<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/channelunit.func.php");

class TypeLink
{
	var $typeDir;
	var $dsql;
	var $TypeID;
	var $baseDir;
	var $modDir;
	var $Lang;
	var $TopID;
	var $LangName;
	var $IndexLink;
	var $indexUrl;
	var $indexName;
	var $TypeInfos;
	var $SplitSymbol;
	var $valuePosition;
	var $valuePositionName;
	var $OptionArrayList;

	//构造函数///////
	//php5构造函数
	function __construct($typeid)
	{
		global $user_lang;
		$this->indexUrl = $GLOBALS['cfg_basehost'].$GLOBALS['cfg_indexurl'];
		$this->indexName = $GLOBALS['cfg_indexname'];
		$this->baseDir = $GLOBALS['cfg_basedir'];
		$this->modDir = $GLOBALS['cfg_templets_dir'];
		$this->SplitSymbol = $GLOBALS['cfg_list_symbol'];
		$this->dsql = $GLOBALS['dsql'];
		$this->TypeID = $typeid;
		$this->TopID = 0;
		$this->Lang = '';
		$this->LangName = '';
		$this->IndexLink = '';
		$this->valuePosition = '';
		$this->valuePositionName = '';
		$this->typeDir = '';
		$this->OptionArrayList = '';

		//载入类目信息
		$query = "Select tp.*,ch.typename as ctypename,ch.addtable,ch.issystem From `#@__arctype` tp left join `#@__channeltype` ch
		on ch.id=tp.channeltype  where tp.id='$typeid' ";
		if($typeid > 0)
		{
			$this->TypeInfos = $this->dsql->GetOne($query);
			if(is_array($this->TypeInfos))
			{
				$user_lang = $this->GetLang();
				$this->TypeInfos['tempindex'] = MfTemplet($this->TypeInfos['tempindex'], $user_lang);
				$this->TypeInfos['templist'] = MfTemplet($this->TypeInfos['templist'], $user_lang);
				$this->TypeInfos['temparticle'] = MfTemplet($this->TypeInfos['temparticle'], $user_lang);
			}
		}
	}

	//对于使用默认构造函数的情况
	//GetPositionLink()将不可用
	function TypeLink($typeid)
	{
		$this->__construct($typeid);
	}

	//关闭数据库连接，析放资源
	function Close()
	{
	}
	
	function GetLang()
	{
		if($this->Lang !='' ) return $this->Lang;
		if(!is_array($this->TypeInfos) || $this->TypeID=='0') {
			return '';
		}
		if($this->TypeInfos['reid'] > 0)
		{
			$topid = ($this->TypeInfos['topid']>0 ? $this->TypeInfos['topid'] : GetTopid($this->TypeID));
			$row = $this->dsql->GetOne("Select id,typename,typedir,isdefault,defaultname,ispart,namerule2 From `#@__arctype` where id='$topid' ");
			$regArr = array();
			preg_match_all("/(.*)\(([a-zA-Z0-9-]*)\)/", $row['typename'], $regArr);
			$this->LangName = $regArr[1][0];
			$this->Lang = $regArr[2][0];
			$this->TopID = $row['id'];
			$turl = GetTypeUrl($row['id'],$row['typedir'],$row['isdefault'],$row['defaultname'],$row['ispart'],$row['namerule2']);
			$this->IndexLink = "<a href='{$turl}'>{$this->LangName}</a>";
		}
		else
		{
			$regArr = array();
			preg_match_all("/(.*)\(([a-zA-Z0-9-]*)\)/", $this->TypeInfos['typename'], $regArr);
			$this->LangName = $regArr[1][0];
			$this->Lang = $regArr[2][0];
			$this->TopID = $this->TypeID;
			$turl = GetTypeUrl($this->TypeInfos['id'],$this->TypeInfos['typedir'],$this->TypeInfos['isdefault'],$this->TypeInfos['defaultname'],$this->TypeInfos['ispart'],$this->TypeInfos['namerule2']);
			$this->IndexLink = "<a href='{$turl}'>{$this->LangName}</a>";
		}
		return $this->Lang;
	}

	//重设类目ID
	function SetTypeID($typeid)
	{
		$this->TypeID = $typeid;
		$this->valuePosition = '';
		$this->valuePositionName = '';
		$this->typeDir = '';
		$this->OptionArrayList = '';

		//载入类目信息
		$query = "Select #@__arctype.*,#@__channeltype.typename as ctypename
		From #@__arctype left join #@__channeltype
		on #@__channeltype.id=#@__arctype.channeltype where #@__arctype.id='$typeid' ";
		$this->dsql->SetQuery($query);
		$this->TypeInfos = $this->dsql->GetOne();
	}

	//获得这个类目的路径
	function GetTypeDir()
	{
		if(empty($this->TypeInfos['typedir']))
		{
			return $GLOBALS['cfg_cmspath'].$GLOBALS['cfg_arcdir'];
		}
		else
		{
			return $this->TypeInfos['typedir'];
		}
	}

	//获得某类目的链接列表 如：类目一>>类目二>> 这样的形式
	//islink 表示返回的列表是否带连接
	function GetPositionLink($islink=true)
	{
		$this->GetLang();
		$indexpage = $this->IndexLink;
		if($this->valuePosition!='' && $islink)
		{
			return $this->valuePosition;
		}
		else if($this->valuePositionName!='' && !$islink)
		{
			return $this->valuePositionName;
		}
		else if($this->TypeID==0)
		{
			if($islink)
			{
				return $indexpage;
			}
			else
			{
				return "No Catalog Selected";
			}
		}
		else
		{
			if($islink)
			{
				$this->valuePosition = $this->GetOneTypeLink($this->TypeInfos);
				if($this->TypeInfos['reid']!=0)
				{
					//调用递归逻辑
					$this->LogicGetPosition($this->TypeInfos['reid'],true);
				}
				if($this->TypeInfos['reid']!=0) $this->valuePosition = $indexpage.$this->SplitSymbol.$this->valuePosition;
				else $this->valuePosition = $indexpage;
				return $this->valuePosition.$this->SplitSymbol;
			}
			else
			{
				$this->valuePositionName = $this->TypeInfos['typename'];
				if($this->TypeInfos['reid']!=0) $this->valuePositionName = $this->TypeInfos['typename'];
				else $this->valuePosition = $this->valuePositionName = $this->LangName;
				if($this->TypeInfos['reid']!=0)
				{
					//调用递归逻辑
					$this->LogicGetPosition($this->TypeInfos['reid'],false);
				}
				return $this->valuePositionName;
			}
		}
	}

	//获得名字列表
	function GetPositionName()
	{
		return $this->GetPositionLink(false);
	}

	//获得某类目的链接列表，递归逻辑部分
	function LogicGetPosition($id,$islink)
	{
		$this->dsql->SetQuery("Select id,reid,topid,typename,typedir,isdefault,ispart,defaultname,namerule2 From #@__arctype where id='".$id."'");
		$tinfos = $this->dsql->GetOne();
		if($tinfos['reid'] == 0)
		{
			return 0;
		}
		if($islink)
		{
			$this->valuePosition = $this->GetOneTypeLink($tinfos).$this->SplitSymbol.$this->valuePosition;
		}
		else
		{
			$this->valuePositionName = $tinfos['typename'].$this->SplitSymbol.$this->valuePositionName;
		}
		if($tinfos['reid']>0)
		{
			$this->LogicGetPosition($tinfos['reid'],$islink);
		}
		else
		{
			return 0;
		}

	}

	//获得某个类目的超链接信息
	function GetOneTypeLink($typeinfos)
	{
		$typepage = $this->GetOneTypeUrl($typeinfos);
		$typelink = "<a href='".$typepage."'>".$typeinfos['typename']."</a>";
		return $typelink;
	}

	//获得某分类连接的URL
	function GetOneTypeUrl($typeinfos)
	{
		return GetTypeUrl($typeinfos['id'],MfTypedir($typeinfos['typedir']),$typeinfos['isdefault'],
		                                 $typeinfos['defaultname'],$typeinfos['ispart'],$typeinfos['namerule2']);
	}

	//获得类别列表
	//hid 是指默认选中类目，0 表示“请选择类目”或“不限类目”
	//oper 是用户允许管理的类目，0 表示所有类目
	//channeltype 是指类目的内容类型，0 表示不限频道
	function GetOptionArray($hid=0,$oper=0,$channeltype=0,$usersg=0)
	{
		return $this->GetOptionList($hid,$oper,$channeltype,$usersg);
	}

	function GetOptionList($hid=0,$oper=0,$channeltype=0,$usersg=0)
	{
		if(!$this->dsql) $this->dsql = $GLOBALS['dsql'];
		$this->OptionArrayList = '';
		
		if($hid>0)
		{
			$row = $this->dsql->GetOne("Select id,typename,ispart,channeltype From #@__arctype where id='$hid'");
			$channeltype = $row['channeltype'];
			if($row['ispart']==1) {
				$this->OptionArrayList .= "<option value='".$row['id']."' style='background-color:#DFDFDB;color:#888888' selected>".$row['typename']."</option>\r\n";
			}
			else {
				$this->OptionArrayList .= "<option value='".$row['id']."' selected>".$row['typename']."</option>\r\n";
			}
		}
		
		if($channeltype==0) $ctsql = '';
		else $ctsql=" And channeltype='$channeltype' ";
		
		if($oper!=0) $query = "Select id,typename,ispart From #@__arctype where ispart<>2 And id='$oper' $ctsql";
		else $query = "Select id,typename,ispart From #@__arctype where ispart<>2 And reid=0 $ctsql order by sortrank asc";

		$this->dsql->SetQuery($query);
		$this->dsql->Execute();
		while($row=$this->dsql->GetObject())
		{
			if($row->id!=$hid)
			{
				if($row->ispart==1) {
					$this->OptionArrayList .= "<option value='".$row->id."' style='background-color:#EFEFEF;color:#666666'>".$row->typename."</option>\r\n";
				}
				else {
					$this->OptionArrayList .= "<option value='".$row->id."'>".$row->typename."</option>\r\n";
				}
			}
			$this->LogicGetOptionArray($row->id,"─");
		}
		return $this->OptionArrayList;
	}

	function LogicGetOptionArray($id,$step)
	{
		$this->dsql->SetQuery("Select id,typename,ispart From #@__arctype where reid='".$id."' And ispart<>2 order by sortrank asc");
		$this->dsql->Execute($id);
		while($row=$this->dsql->GetObject($id))
		{
			if($row->ispart==1) {
				$this->OptionArrayList .= "<option value='".$row->id."' style='background-color:#EFEFEF;color:#666666'>$step".$row->typename."</option>\r\n";
			}
			else {
				$this->OptionArrayList .= "<option value='".$row->id."'>$step".$row->typename."</option>\r\n";
			}
			$this->LogicGetOptionArray($row->id,$step."─");
		}
	}

	//获得与该类相关的类目，本函数应用于模板标记{dede:channel}{/dede:channel}中
	//$typetype 的值为： sun 下级分类 self 同级分类 top 顶级分类
	function GetChannelList($typeid=0,$reid=0,$row=8,$typetype='sun',$innertext='',
	$col=1,$tablewidth=100,$myinnertext='')
	{
		if($typeid==0)
		{
			$typeid = $this->TypeID;
		}
		if($row=="")
		{
			$row = 8;
		}
		if($reid=="")
		{
			$reid = 0;
		}
		if($col=="")
		{
			$col = 1;
		}
		$tablewidth = str_replace("%","",$tablewidth);
		if($tablewidth=="")
		{
			$tablewidth=100;
		}
		if($col=="")
		{
			$col = 1;
		}
		$colWidth = ceil(100/$col);
		$tablewidth = $tablewidth."%";
		$colWidth = $colWidth."%";
		if($typetype=="")
		{
			$typetype="sun";
		}
		if($innertext=="")
		{
			$innertext = GetSysTemplets("channel_list.htm");
		}
		if($reID==0 && $typeid>0)
		{
			$dbrow = $this->dsql->GetOne("Select reid From #@__arctype where id='$typeid' ");
			if(is_array($dbrow))
			{
				$reid = $dbrow['reid'];
			}
		}
		$likeType = "";
		if($typetype=="top")
		{
			$sql = "select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  from #@__arctype where reid=0 and ishidden<>1 order by sortrank asc limit 0,$row";
		}
		else if($typetype=="sun"||$typetype=="son")
		{
			$sql = "select id,typename,typedir,isdefault,ispart,defaultname,namerule2
		  from #@__arctype where reid='$typeid' and ishidden<>1 order by sortrank asc limit 0,$row";
		}
		else if($typetype=="self")
		{
			$sql = "select id,typename,typedir,isdefault,ispart,defaultname,namerule2
			from #@__arctype where reid='$reid' and ishidden<>1 order by sortrank asc limit 0,$row";
		}

		//And ID<>'$typeid'
		$dtp2 = new DedeTagParse();
		$dtp2->SetNameSpace("field","[","]");
		$dtp2->LoadSource($innertext);
		$this->dsql->SetQuery($sql);
		$this->dsql->Execute();
		$line = $row;
		$GLOBALS['autoindex'] = 0;
		if($col>1)
		{
			$likeType = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
		}
		for($i=0;$i<$line;$i++)
		{
			if($col>1)
			{
				$likeType .= "<tr>\r\n";
			}
			for($j=0;$j<$col;$j++)
			{
				if($col>1) $likeType .= "	<td width='$colWidth'>\r\n";
				if($row=$this->dsql->GetArray())
				{
					//处理当前栏目的样式
					if($row['id']=="$typeid" && $myinnertext != '')
					{

						$linkOkstr = $myinnertext;
						$row['typelink'] = $this->GetOneTypeUrl($row);
						$linkOkstr = str_replace("~typelink~",$row['typelink'],$linkOkstr);
						$linkOkstr = str_replace("~typename~",$row['typename'],$linkOkstr);
						$likeType .= $linkOkstr;
					}
					else
					{
						//非当前栏目
						$row['typelink'] = $this->GetOneTypeUrl($row);
						if(is_array($dtp2->CTags))
						{
							foreach($dtp2->CTags as $tagid=>$ctag)
							{
								if(isset($row[$ctag->GetName()]))
								{
									$dtp2->Assign($tagid,$row[$ctag->GetName()]);
								}
							}
						}
						$likeType .= $dtp2->GetResult();
					}
				}
				if($col>1)
				{
					$likeType .= "	</td>\r\n";
				}
				$GLOBALS['autoindex']++;
			}//Loop Col

			if($col>1)
			{
				$i += $col - 1;
			}
			if($col>1)
			{
				$likeType .= "	</tr>\r\n";
			}
		}//Loop for $i

		if($col>1)
		{
			$likeType .= "	</table>\r\n";
		}
		$this->dsql->FreeResult();
		return $likeType;
	}//GetChannel

}//End Class
?>