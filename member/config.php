<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/filter.inc.php");
require_once(DEDEINC."/memberlogin.class.php");
require_once(DEDEINC."/dedetemplate.class.php");

//获得当前脚本名称，如果你的系统被禁用了$_SERVER变量，请自行更改这个选项
$dedeNowurl = $s_scriptName = '';
$dedeNowurl = GetCurUrl();
$dedeNowurls = explode('?',$dedeNowurl);
$s_scriptName = $dedeNowurls[0];

//检查是否开放会员功能
if($cfg_mb_open=='N')
{
	ShowMsg("系统关闭了会员功能，因此你无法访问此页面！","javascript:;");
	exit();
}
$keeptime = isset($keeptime) && is_numeric($keeptime) ? $keeptime : -1;
$cfg_ml = new MemberLogin($keeptime);

//语言选择
if(!empty($cfg_ml->fields['lang']))
{
	$lang = $cfg_ml->fields['lang'];
}
if(empty($lang))
{
	if(isset($_COOKIE['sellang'])) $lang = $_COOKIE['sellang'];
	else $lang = $cfg_df_lang;
}
$lang = eregi_replace('[^a-z0-9]', '', $lang);
if(empty($lang) || !file_exists(DEDEINC."/lang/{$lang}.member.utf8.php"))
{
	$lang = $cfg_df_lang;
	$lang = eregi_replace('[^a-z0-9]', '', $lang);
	require_once(DEDEINC."/lang/{$cfg_df_lang}.member.utf8.php");
}
else
{
	setcookie('sellang', $lang, time()+(3600 * 24 * 30), '/');
	require_once(DEDEINC."/lang/{$lang}.member.utf8.php");
}
$userLang = $lang;

//判断用户是否登录
$myurl = '';
if($cfg_ml->IsLogin())
{
	$myurl = $cfg_memberurl."/index.php?uid=".urlencode($cfg_ml->M_LoginID);
	if(!ereg('^http:',$myurl))
	{
		$myurl = $cfg_basehost.$myurl;
	}
}

//检查用户是否有权限进行某个操作
function CheckRank($rank=0)
{
	global $cfg_ml,$cfg_memberurl;
	if(!$cfg_ml->IsLogin())
	{
		header("Location:{$cfg_memberurl}/login.php?gourl=".urlencode(GetCurUrl()));
		exit();
	}
	else
	{
		if($cfg_ml->M_Rank < $rank)
		{
			$needname = "";
			if($cfg_ml->M_Rank==0)
			{
				$row = $dsql->GetOne("Select membername From #@__arcrank where rank='$rank'");
				$myname = GetLang('normalmember');
				$needname = $row['membername'];
			}
			else
			{
				$dsql->SetQuery("Select membername From #@__arcrank where rank='$rank' Or rank='".$cfg_ml->M_Rank."' order by rank desc");
				$dsql->Execute();
				$row = $dsql->GetObject();
				$needname = $row->membername;
				if($row = $dsql->GetObject())
				{
					$myname = $row->membername;
				}
				else
				{
					$myname = GetLang('normalmember');
				}
			}
			ShowMsg(GetLang('err_purview'), '-1',0,5000);
			exit();
		}
	}
}

?>