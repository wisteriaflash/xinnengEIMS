<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC.'/arc.archives.class.php');

$t1 = ExecTime();

if(empty($okview))
{
	$okview = '';
}

if(isset($arcID))
{
	$aid = $arcID;
}

$arcID = $aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
if($aid==0)
{
	die(" Request Error! ");
}

$arc = new Archives($aid);
if($arc->IsError)
{
	ParamError();
}

//检查阅读权限
$needRank = $arc->Fields['arcrank'];

//设置了权限限制的文章
//arctitle msgtitle moremsg
if($needRank>1)
{
	$userLang = $arc->Lang;
	require_once(DEDEINC.'/memberlogin.class.php');
	$ml = new MemberLogin();
	
	$arctitle = $arc->Fields['title'];
	
	$arclink = GetFileUrl($arc->ArcID,$arc->Fields['typeid'],$arc->Fields['senddate'], 
	                      $arc->Fields['title'],$arc->Fields['ismake'],$arc->Fields['arcrank']);
	
	$description =  $arc->Fields['description'];
	
	$pubdate = GetDateTimeMk($arc->Fields['pubdate']);
	
	//会员级别不足
	if(($needRank>1 && $ml->M_Rank < $needRank && $arc->Fields['mid']!=$ml->M_ID))
	{
		$dsql->Execute('me' , 'Select * From `#@__arcrank` ');
		while($row = $dsql->GetObject('me'))
		{
			$memberTypes[$row->rank] = $row->membername;
		}
		$memberTypes[0] = GetLang('regmember');
		$msgtitle = GetLang('notpurview');
		$moremsg = GetLang('tdnta')." [<font color='red'>".$memberTypes[$needRank]."</font>] , ".GetLang('youating')."<font color='red'>".$memberTypes[$ml->M_Rank]."</font>";
		include_once(DEDETEMPDIR.'/plus/view_msg.htm');
		exit();
	}
}
$arc->Display();

?>