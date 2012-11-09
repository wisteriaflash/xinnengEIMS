<?php
define('DEDEADMIN', ereg_replace("[/\\]{1,}",'/',dirname(__FILE__) ) );
require_once(DEDEADMIN."/../include/common.inc.php");
require_once(DEDEINC."/userlogin.class.php");
header("Cache-Control:private");
$dsql->safeCheck = false;
$dsql->SetLongLink();

//获得当前脚本名称，如果你的系统被禁用了$_SERVER变量，请自行更改这个选项
$dedeNowurl = $s_scriptName = '';
$isUrlOpen = @ini_get("allow_url_fopen");
$dedeNowurl = GetCurUrl();
$dedeNowurls = explode('?',$dedeNowurl);
$s_scriptName = $dedeNowurls[0];

//检验用户登录状态
$cuserLogin = new userLogin();
if($cuserLogin->getUserID()==-1)
{
	header("location:login.php?gotopage=".urlencode($dedeNowurl));
	exit();
}
if($cfg_dede_log=='Y')
{
	$s_nologfile = "_main|_list";
	$s_needlogfile = "sys_|file_";
	$s_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";
	$s_query = isset($dedeNowurls[1]) ? $dedeNowurls[1] : "";
	$s_scriptNames = explode('/',$s_scriptName);
	$s_scriptNames = $s_scriptNames[count($s_scriptNames)-1];
	$s_userip = GetIP();
	if( $s_method=='POST' || (!eregi($s_nologfile,$s_scriptNames) && $s_query!='') || eregi($s_needlogfile,$s_scriptNames) )
	{
		$inquery = "INSERT INTO `#@__log`(adminid,filename,method,query,cip,dtime)
             VALUES ('".$cuserLogin->getUserID()."','{$s_scriptNames}','{$s_method}','".addslashes($s_query)."','{$s_userip}','".time()."');";
		$dsql->ExecuteNoneQuery($inquery);
	}
}
$cache1 = DEDEDATA."/cache/inc_catalog_base.inc";
if(!file_exists($cache1))
{
	UpDateCatCache();
}

//更新栏目缓存
function UpDateCatCache()
{
	global $dsql,$cfg_multi_site;
	$cache1 = DEDEDATA."/cache/inc_catalog_base.inc";
	$cache2 = DEDEDATA."/cache/inc_catalog_lang.inc";
	$dsql->SetQuery("Select id,reid,channeltype From `#@__arctype`");
	$dsql->Execute();
	$fp1 = fopen($cache1,'w');
	$phph = '?';
	$fp1Header = "<{$phph}php\r\nglobal \$_Cs;\r\n\$_Cs=array();\r\n";
	fwrite($fp1,$fp1Header);
	while($row=$dsql->GetObject())
	{
		fwrite($fp1,"\$_Cs[{$row->id}]=array({$row->reid},{$row->channeltype});\r\n");
	}
	fwrite($fp1,"{$phph}>");
	fclose($fp1);
	//更新顶级栏目语言地图
	$dsql->SetQuery("Select typename,lang From `#@__arctype` where reid=0 ");
	$dsql->Execute();
	$fp1 = fopen($cache2,'w');
	$phph = '?';
	$fp1Header = "<{$phph}php\r\nglobal \$lang_has_arr;\r\n\$lang_has_arr = array();\r\n";
	fwrite($fp1,$fp1Header);
	while($row = $dsql->GetArray())
	{
		$arr = array();
		preg_match_all("/([^\(]*)/", $row['typename'], $arr);
		$langname = $arr[0][0];
		$lang = $row['lang'];
		fwrite($fp1, "\$lang_has_arr['{$lang}'] = '{$langname}';\n");
	}
	fwrite($fp1,"{$phph}>");
	fclose($fp1);
}

function DedeInclude($filename,$isabs=false)
{
	return $isabs ? $filename : DEDEADMIN.'/'.$filename;
}

//更新配置函数
function ReWriteConfig()
{
	global $dsql;
	$configfile = DEDEDATA.'/config.cache.inc.php';
	if(!is_writeable($configfile))
	{
		echo "配置文件'{$configfile}'不支持写入，无法修改系统配置参数！";
		exit();
	}
	$fp = fopen($configfile,'w');
	flock($fp,3);
	fwrite($fp,"<"."?php\r\n");
	$dsql->SetQuery("Select `varname`,`type`,`value`,`groupid` From `#@__sysconfig` order by aid asc ");
	$dsql->Execute();
	while($row = $dsql->GetArray())
	{
		if($row['type']=='number')
		{
			if($row['value']=='') $row['value'] = 0;
			fwrite($fp,"\${$row['varname']} = ".$row['value'].";\r\n");
		}
		else
		{
			fwrite($fp,"\${$row['varname']} = '".str_replace("'",'',$row['value'])."';\r\n");
		}
	}
	fwrite($fp,"?".">");
	fclose($fp);
}
?>