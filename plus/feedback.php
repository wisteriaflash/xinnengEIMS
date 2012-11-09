<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/filter.inc.php");
require_once(DEDEINC.'/userlang.inc.php');

if(!isset($action)) $action = '';
$ischeck = $cfg_feedbackcheck=='Y' ? 0 : 1;
$aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
$fid = (isset($fid) && is_numeric($fid)) ? $fid : 0;
if(empty($aid) && empty($fid))
{
	ShowMsg( GetLang('msg_errdoc'), '-1');
	exit();
}

include_once(DEDEINC."/memberlogin.class.php");
$cfg_ml = new MemberLogin();

//查看评论
/*
function __ViewFeedback(){ }
*/
//-----------------------------------
if($action=='' || $action=='show')
{
	//读取文档信息
	$arcRow = GetOneArchive($aid);
	if(empty($arcRow['aid']))
	{
		ShowMsg( GetLang('err_docerr'), '-1');
		exit();
	}
	extract($arcRow, EXTR_SKIP);
	include_once(DEDEINC.'/datalistcp.class.php');
	$dlist = new DataListCP();
	$dlist->pageSize = 20;

	if(empty($ftype) || ($ftype!='good' && $ftype!='bad' && $ftype!='feedback'))
	{
		$ftype = '';
	}
	$wquery = $ftype!='' ? " And ftype like '$ftype' " : '';

	//评论内容列表
	$querystring = "select fb.*,mb.userid,mb.face as mface,mb.scores from `#@__feedback` fb
                 left join `#@__member` mb on mb.mid = fb.mid
                 where fb.aid='$aid' and fb.ischeck='1' $wquery order by fb.id desc";
	$dlist->SetParameter('aid',$aid);
	$dlist->SetParameter('action','show');
	$dlist->SetTemplate(DEDETEMPDIR.'/plus/feedback_templet.htm');
	$dlist->SetSource($querystring);
	$dlist->Display();
	exit();
}

//引用评论
//------------------------------------
/*
function __Quote(){ }
*/
else if($action=='quote')
{
	$row = $dsql->GetOne("Select * from `#@__feedback` where id ='$fid'");
	require_once(DEDEINC.'/dedetemplate.class.php');
	$dtp = new DedeTemplate();
	$dtp->LoadTemplate(DEDETEMPDIR.'/plus/feedback_quote.htm');
	$dtp->Display();
	exit();
}
//发表评论
//------------------------------------
/*
function __SendFeedback(){ }
*/
else if($action=='send')
{
	//读取文档信息
	$arcRow = GetOneArchive($aid);
	if((empty($arcRow['aid']) || $arcRow['notpost']=='1')&&empty($fid))
	{
		ShowMsg( GetLang('err_docerr'), '-1');
		exit();
	}

	//是否加验证码重确认
	if(empty($isconfirm))
	{
		$isconfirm = '';
	}
	if($isconfirm!='yes' && $cfg_feedback_ck=='Y')
	{
		extract($arcRow, EXTR_SKIP);
		require_once(DEDEINC.'/dedetemplate.class.php');
		$dtp = new DedeTemplate();
		$dtp->LoadTemplate(DEDETEMPDIR.'/plus/feedback_confirm.htm');
		$dtp->Display();
		exit();
	}
	//检查验证码
	if($cfg_feedback_ck=='Y')
	{
		$validate = isset($validate) ? strtolower(trim($validate)) : '';
		$svali = strtolower(trim(GetCkVdValue()));
		if($validate != $svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg( GetLang('err_vdcode'), '-1');
			exit();
		}
	}

	//检查用户登录
	if(empty($notuser))
	{
		$notuser=0;
	}

	//匿名发表评论
	if($notuser==1)
	{
		$username = $cfg_ml->M_ID > 0 ? GetLang('anonymous') : 'Guest';
	}

	//已登录的用户
	else if($cfg_ml->M_ID > 0)
	{
		$username = $cfg_ml->M_UserName;
	}

	//用户身份验证
	else
	{
		if($username!='' && $pwd!='')
		{
			$rs = $cfg_ml->CheckUser($username,$pwd);
			if($rs==1)
			{
				$dsql->ExecuteNoneQuery("Update `#@__member` set logintime='".time()."',loginip='".GetIP()."' where mid='{$cfg_ml->M_ID}'; ");
			}
			else
			{
				$username = 'Guest';
			}
		}
		else
		{
			$username = 'Guest';
		}
	}
	$ip = GetIP();
	$dtime = time();
	
	//检查评论间隔时间；
	if(!empty($cfg_feedback_time))
	{
		//检查最后发表评论时间，如果未登陆判断当前IP最后评论时间
		if($cfg_ml->M_ID > 0)
		{
			$where = "WHERE `mid` = '$cfg_ml->M_ID'";
		}
		else
		{
			$where = "WHERE `ip` = '$ip'";
		}
		$row = $dsql->GetOne("SELECT dtime FROM `#@__feedback` $where ORDER BY `id` DESC ");
		if( is_array($row) && ($dtime - $row['dtime'] < $cfg_feedback_time) )
		{
			ResetVdValue();
			ShowMsg( GetLang('err_fbtime'), '-1');
			exit();
		}
	}

	if(empty($face))
	{
		$face = 0;
	}
	$face = intval($face);
	extract($arcRow, EXTR_SKIP);
	$msg = cn_substrR(TrimMsg($msg),1000);
	$username = cn_substrR(HtmlReplace($username,2),20);
	if($feedbacktype!='good' && $feedbacktype!='bad')
	{
		$feedbacktype = 'feedback';
	}
	//保存评论内容
	if($comtype == 'comments')
	{
		$arctitle = addslashes($title);
		if($msg!='')
		{
			$inquery = "INSERT INTO `#@__feedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`, `mid`,`bad`,`good`,`ftype`,`face`,`msg`)
	               VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime', '{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg'); ";
			$rs = $dsql->ExecuteNoneQuery($inquery);
			if(!$rs)
			{
				echo $dsql->GetError();
				exit();
			}
		}
	}
	//引用回复
	elseif ($comtype == 'reply')
	{
		$row = $dsql->GetOne("Select * from `#@__feedback` where id ='$fid'");
		$arctitle = $row['arctitle'];
		$aid =$row['aid'];
		$msg = $quotemsg.$msg;
		$msg = HtmlReplace($msg,2);
		$inquery = "INSERT INTO `#@__feedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`,`mid`,`bad`,`good`,`ftype`,`face`,`msg`)
				VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime','{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg')";
		$dsql->ExecuteNoneQuery($inquery);
	}

	$dsql->ExecuteNoneQuery("Update `#@__archives` set scores=scores+1,lastpost='$dtime' where id='$aid' ");

	if($cfg_ml->M_ID > 0)
	{
		$dsql->ExecuteNoneQuery("Update `#@__member` set scores=scores+{$cfg_sendfb_scores} where mid='{$cfg_ml->M_ID}' ");
	}
	
	$_SESSION['sedtime'] = time();
	if($ischeck==0)
	{
		ShowMsg( GetLang('msg_fbok1'), "feedback.php?aid=$aid");
	}elseif($ischeck==1)
	{
		ShowMsg( GetLang('msg_fbok2'), "feedback.php?aid=$aid");
	}
	exit();
}
?>