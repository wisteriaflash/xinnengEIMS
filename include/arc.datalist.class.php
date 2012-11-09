<?php
if(!defined('DEDEINC')) exit('Request Error!');
require_once(DEDEINC."/arc.partview.class.php");
@set_time_limit(0);
class DataList
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
	var $SourceSql;
	var $Template;
	var $QueryTime;
	var $GetValues;
	//php5���캯��
	function __construct($sql, $template, $typeid=0)
	{
		global $dsql;
		$this->TypeID = $typeid;
		$this->dsql = $dsql;
		$this->Lang = '';
		$this->Template = $template;
		$this->SourceSql = $sql;
		$this->GetValues = array();
		$this->dtp = new DedeTagParse();
		$this->dtp->refObj = $this;
		$this->dtp->SetNameSpace('dede', '{', '}');
		$this->dtp2 = new DedeTagParse();
		$this->dtp2->SetNameSpace('field', '[', ']');
		
		//�����Ҫ�� �����Ŀ��Ϣ
		if(!empty($typeid))
		{
			$this->TypeLink = new TypeLink($typeid);
			$this->Lang = $this->TypeLink->TypeInfos['lang'];
			$this->Fields = $this->TypeLink->TypeInfos;
			$this->Fields['id'] = $typeid;
			$this->Fields['position'] = $this->TypeLink->GetPositionLink(true);
			$this->Fields['title'] = ereg_replace("[<>]"," / ",$this->TypeLink->GetPositionLink(false));
			$this->Fields['rsslink'] = $GLOBALS['cfg_cmsurl']."/data/rss/".$this->TypeID.".xml";
			//���û�������
			SetSysEnv($this->TypeID,$this->Fields['typename'],0,'','list');
			$this->Fields['typeid'] = $this->TypeID;
		}
		//����һЩȫ�ֲ�����ֵ
		foreach($GLOBALS['PubFields'] as $k=>$v) $this->Fields[$k] = $v;
	}

	//php4���캯��
	function DataList($sql, $template, $typeid=0)
	{
		$this->__construct($sql, $template, $typeid);
	}
	//�ر������Դ
	function Close()
	{

	}

	//ͳ���б���ļ�¼
	function CountRecord()
	{
		global $lang,$cfg_df_lang;
		//ͳ�����ݿ��¼
		$this->TotalResult = -1;
		if(isset($GLOBALS['TotalResult'])) $this->TotalResult = $GLOBALS['TotalResult'];
		if(isset($GLOBALS['PageNo'])) $this->PageNo = $GLOBALS['PageNo'];
		else $this->PageNo = 1;
		
		if($this->TotalResult==-1)
		{
			$countQuery = eregi_replace("select[ \r\n\t](.*)[ \r\n\t]from","Select count(*) as dd From", $this->SourceSql);
			$countQuery = eregi_replace('order[ \r\n\t]{1,}by(.*)', '', $countQuery);
			$row = $this->dsql->GetOne($countQuery);
			if(is_array($row)) {
				$this->TotalResult = $row['dd'];
			}
			else {
				$this->TotalResult = 0;
			}
		}

		//��ʼ���б�ģ�壬��ͳ��ҳ������
		$tempfile = $this->Template;
		if(!file_exists($tempfile) || !is_file($tempfile))
		{
			$tempfile = ereg_replace("^[^/\\]*/", "/", $tempfile);
			echo "ģ���ļ� {$tempfile} �����ڣ��޷������ĵ���";
			exit();
		}
		
		$this->dtp->LoadTemplate($tempfile);
		
		if(!empty($lang)) $this->Lang = $this->dtp->handLang = $lang;
		else $this->Lang = $this->dtp->handLang = $cfg_df_lang;
		
		$ctag = $this->dtp->GetTag('page');
		if(!is_object($ctag))
		{
			$ctag = $this->dtp->GetTag('list');
		}
		if(!is_object($ctag))
		{
			$this->PageSize = 20;
		}
		else
		{
			if($ctag->GetAtt('pagesize')!='') $this->PageSize = $ctag->GetAtt('pagesize');
			else $this->PageSize = 20;
		}
		$this->TotalPage = ceil($this->TotalResult/$this->PageSize);
	}

	//��ʾ�б�
	function Display()
	{
		$this->CountRecord();
		$this->ParseTempletsFirst();
		$this->ParseDMFields($this->PageNo,0);
		$this->dtp->Display();
	}

	//����ģ�壬�Թ̶��ı�ǽ��г�ʼ��ֵ
	function ParseTempletsFirst()
	{
		if(isset($this->TypeLink->TypeInfos['reid']))
		{
			$GLOBALS['envs']['reid'] = $this->TypeLink->TypeInfos['reid'];
		}
		$GLOBALS['envs']['typeid'] = $this->TypeID;
		$GLOBALS['envs']['topid'] = GetTopid($this->TypeID);
		$GLOBALS['envs']['cross'] = 1;
		MakeOneTag($this->dtp,$this);
	}

	//����ģ�壬��������ı䶯���и�ֵ
	function ParseDMFields($PageNo,$ismake=1)
	{
		foreach($this->dtp->CTags as $tagid=>$ctag)
		{
			if($ctag->GetName() == 'list')
			{
				$row = $this->PageSize;
				$this->dtp->Assign($tagid, $this->GetList($row, $ctag->GetInnerText()));
			}
			else if($ctag->GetName() == 'pagelist')
			{
				$list_len = trim($ctag->GetAtt('listsize'));
				$ctag->GetAtt('listitem')=='' ? $listitem='index,pre,pageno,next,end,option' : $listitem=$ctag->GetAtt('listitem');
				if($list_len=='')
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

	//���ְλ�б�
	function GetList($line, $innertext)
	{
		$rsvalue = '';
		$t1 = Exectime();
		$limitstart = ($this->PageNo-1) * $this->PageSize;
		$oksql = $this->SourceSql." limit $limitstart, $line";
		$this->dsql->Execute('dlist', $oksql);
		$this->dtp2->LoadSource($innertext);
		$this->dtp2->handLang = $this->Lang;
		while($arr=$this->dsql->GetArray('dlist'))
		{
			if(is_array($this->dtp2->CTags))
			{
					foreach($this->dtp2->CTags as $k=>$ctag)
					{
							if($ctag->GetName()=='array')
							{
								$this->dtp2->Assign($k,$arr); //�����������飬��runphpģʽ������������
							}
							else
							{
								if(isset($arr[$ctag->GetName()])) $this->dtp2->Assign($k, $arr[$ctag->GetName()]);
								else $this->dtp2->Assign($k,'');
							}
					}
			}
			$rsvalue .= $this->dtp2->GetResult();
		}
		$this->dsql->FreeResult('dlist');
		$this->QueryTime = (Exectime() - $t1);
		return $rsvalue;
	}
	
	//������ַ��Get������ֵ
	function SetParameter($key,$value)
	{
		$this->GetValues[$key] = $value;
	}

	//��ȡ��̬�ķ�ҳ�б�
	function GetPageListDM($list_len,$listitem="index,end,pre,next,pageno")
	{
		$GLOBALS['userLang'] = $this->Lang;
		$prepage = '';
		$nextpage = '';
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

		$geturl = "TotalResult=".$this->TotalResult."&";
		$hidenform = '';
		if(count($this->GetValues)>0)
		{
			foreach($this->GetValues as $key=>$value)
			{
				$value = urlencode($value);
				$geturl .= "$key=$value"."&";
				$hidenform .= "<input type='hidden' name='$key' value='$value' />\n";
			}
		}
		
		$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";
		$purl .= "?".$geturl;

		//�����һҳ����һҳ������
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


		//�����������
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

	//��õ�ǰ��ҳ���ļ���url
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