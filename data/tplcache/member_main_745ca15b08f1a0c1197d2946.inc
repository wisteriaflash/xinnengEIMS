<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>网站会员管理</title>
<link type="text/css" rel="stylesheet" href="main.css"/>
<script src="jquery.js" language="javascript" type="text/javascript"></script>
<script src="base.js" language="javascript" type="text/javascript"></script>

<style type="text/css">
.rb{ border-right:1px solid #666666 }
.tb{ border-top:1px solid #666666 }
</style>
</head>
<body>
<div class="mtitle">
  <div class="lbox">网站会员管理</div>
</div>
<table _dlist="line" class="tlist" style="margin-bottom:6px">
  <tr>
    <td height="30" align="center" bgcolor="#FAFCEF"> 
  <form action="member_main.php" name="form1" method="get">
	  <table border="0" cellspacing="0" cellpadding="2">
      <tr>
          <td>关键字：</td>
          <td><input name="keyword" type="text" id="keyword" size="12" style="width:120px" value="<?php echo $GLOBALS['keyword']; ?>" /></td>
          <td>&nbsp;排序：</td>
          <td>
		        <select name="sortkey" id="sortkey" style="width:120px">
              <?php echo $GLOBALS['sortform']; ?>
				      <option value="mid">mid/注册时间</option>
              <option value="logintime">登录时间</option>
            </select>
          </td>
          <td>
		         <select name="sex" id="sex" style="width:60px">
               <?php echo ($GLOBALS['sexform']==1 ? '男' : '女'); ?>
               <option value="1">男</option>
               <option value="2">女</option>
             </select>
          </td>
         <td>
         <button class="btn1" type="submit">搜索</button>
          </td>
        </tr>
      </table>
	  </form>
    </td>
  </tr>
</table>
<table _dlist="line" class="tlist">
  <tr> 
  <td height="28" colspan="7">
   <table width="99%" border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td width="29%">&nbsp;<strong>注册会员列表</strong></td>
       <td width="71%" align="right">
          <a href="member_main.php">所有会员</a> |
          <a href="member_main.php?att=mb">非普通会员</a>
       </td>
     </tr>
  </table>
 </td>
</tr>
  <tr bgcolor="#FEFCEF" height="26" align="center"> 
    <td width="20%">登录名/称呼</td>
    <td width="23%">email/公司</td>
    <td width="5%">性别</td>
    <td width="18%">会员等级</td>
    <td width="14%">最后登录</td>
    <td>操作</td>
  </tr>
<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
foreach( $blockValue as $key=>$fields )
{
?> 
<tr height="26" align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#EDF7D0';"
  	 onMouseOut="javascript:this.bgColor='#FFFFFF';"> 
  <td>
	登录ID：<?php echo $fields['userid']; ?><br />
	称呼：<?php echo $fields['uname']; ?>
	</td>
	<td>
	  <?php echo $fields['email']; ?>
	  <br />
	  <?php echo $fields['company']; ?>
	</td>
  <td>
  	<?php echo ($fields['sex']==1 ? '男' : '女'); ?>
  </td>
  <td>
  	<?php echo GetMemberName($fields['rank'],'限制会员'); ?>
  </td>
  <td>
  	<?php echo MyDate('y-m-d H:i',$fields['logintime']); ?>
  	<br />
  	【<?php echo $fields['loginip']; ?>】
  </td>
  <td align="center">
    <a href="member_view.php?id=<?php echo $fields['mid']; ?>">详细资料</a> | 
    <a href="member_do.php?id=<?php echo $fields['mid']; ?>&dopost=delmember">删除</a> |
    <a href="member_do.php?id=<?php echo $fields['mid']; ?>&dopost=uprank&mtype=<?php echo $fields['rank']; ?>">升级</a>
  </td>
</tr>
<?php
}
?> 
<tr bgcolor="#F1FDE3"> 
 <td height="36" colspan="7" align="center">
	<?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '6';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>

 </td>
</tr>
</table>
</body>
</html>