<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_User');
require_once(DEDEINC."/typelink.class.php");
if(empty($dopost))
{
	$dopost='';
}
if($dopost=='add')
{
	if(ereg("[^0-9a-zA-Z_@!\.-]",$pwd) || ereg("[^0-9a-zA-Z_@!\.-]",$userid))
	{
		ShowMsg("密码或或用户名不合法，<br />请使用[0-9a-zA-Z_@!.-]内的字符！","-1",0,3000);
		exit();
	}
	$safecodeok = substr(md5($cfg_cookie_encode.$randcode),0,24);
	if($safecode != $safecodeok )
	{
		ShowMsg('请填写安全验证串！','-1',0,3000);
		exit();
	}
	$row = $dsql->GetOne("Select count(*) as dd from `#@__member` where userid like '$userid' ");
	if($row['dd']>0)
	{
		ShowMsg('用户名已存在！','-1');
		exit();
	}
	$mpwd = md5($pwd);
	$pwd = substr(md5($pwd),5,20);

	//关连前台会员帐号
  $jointime = $logintime = time();
  
  $adminquery = "INSERT INTO `#@__member` (`userid`,`pwd`,`uname`,`sex`,`rank` ,`email` ,`company`,`mobile`,`tel`,`fax`, `address` ,`matt` ,`face` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
             VALUES ('$userid','$mpwd','$uname','1','10','$email','webmaster', '0', '0', '0', '','10','','$jointime','','$logintime',''); ";           
	$dsql->ExecuteNoneQuery($adminquery);
	
	$mid = $dsql->GetLastID();
	if($mid <= 0 )
	{
		die($dsql->GetError().' 数据库出错！');
	}
	
	$inquery = "Insert Into `#@__admin`(id,usertype,userid,pwd,uname,typeid,tname,email) values('$mid','$usertype','$userid','$pwd','$uname',$typeid,'$tname','$email')";
	$dsql->ExecuteNoneQuery($inquery);
	
	ShowMsg('成功增加一个用户！','sys_admin_user.php');
	exit();
}
$randcode = mt_rand(10000,99999);
$safecode = substr(md5($cfg_cookie_encode.$randcode),0,24);
$typeOptions = '';
$dsql->SetQuery("Select id,typename From #@__arctype where reid=0 And (ispart=0 Or ispart=1)");
$dsql->Execute('op');
while($row = $dsql->GetObject('op'))
{
	$typeOptions .= "<option value='{$row->id}'>{$row->typename}</option>\r\n";
}
include DedeInclude('templets/sys_admin_user_add.htm');

?>