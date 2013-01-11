<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('plus_投票模块');
if(empty($dopost))
{
	$dopost = "";
}

if($dopost=="save")
{
	$starttime = GetMkTime($starttime);
	$endtime = GetMkTime($endtime);
	$voteitems = "";
	$j=0;
	for($i=1;$i<=15;$i++)
	{
		if(!empty(${"voteitem".$i}))
		{
			$j++;
			$voteitems .= "<v:note id=\\'$j\\' count=\\'0\\'>".${"voteitem".$i}."</v:note>\r\n";
		}
	}
	if(empty($copyall))
	{
		$inQuery = "Insert into #@__vote(lang,votename,starttime,endtime,totalcount,ismore,votenote)
			Values('$lang','$votename','$starttime','$endtime','0','$ismore','$voteitems'); ";
		if(!$dsql->ExecuteNoneQuery($inQuery))
		{
			ShowMsg("增加投票失败，请检查数据是否非法！","-1");
			exit();
		}
	}
	else
	{
		foreach($lang_has_arr as $lang=>$lname)
    {
    		$inQuery = "Insert into #@__vote(lang,votename,starttime,endtime,totalcount,ismore,votenote)
						Values('$lang','$votename','$starttime','$endtime','0','$ismore','$voteitems'); ";
				$dsql->ExecuteNoneQuery($inQuery);
  	}
	}
	ShowMsg("成功增加一组投票！","vote_main.php");
	exit();
}
$startDay = time();
$endDay = AddDay($startDay,30);
$startDay = GetDateTimeMk($startDay);
$endDay = GetDateTimeMk($endDay);
include DedeInclude('templets/vote_add.htm');

?>