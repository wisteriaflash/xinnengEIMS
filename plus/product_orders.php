<?php
include_once (dirname(__FILE__) . "/../include/common.inc.php");
define('PLUS_TPL_', DEDEROOT.'/templets/plus');
require_once(DEDEINC.'/userlang.inc.php');
include_once(DEDEINC."/memberlogin.class.php");
$cfg_ml = new MemberLogin();

$aid = !isset($aid) || !is_numeric($aid) ? 0 : $aid;
$dopost = isset($dopost) && in_array($dopost, array('post', 'query')) ? $dopost : 'form';

class product_orders
{
	var $arcRow;
	var $ordRow;
	var $arcID;
	var $userLang;
	function __construct($aid=0)
	{
		$this->userLang = $GLOBALS['userLang'];
		if($aid)
		{
			require_once(DEDEINC.'/arc.archives.class.php');
			$arc = new Archives($aid);
			$this->arcRow = $arc->Fields;
			$addFieds = $arc->ChannelUnit->ChannelFields;
			$row = $arc->dsql->GetOne($arc->dsql->queryString);
			$addRows = array();
			foreach($addFieds as $k => $val)
			{
				if($k == 'body')
				{
					$this->arcRow[$k] = $row[$k]; continue;
				}
				$addRows[$k]['itemName'] = $val['itemname'];
				$addRows[$k]['itemFied'] = $val['tagname'];
				$addRows[$k]['itemValue'] = $row[$k];
			}
			unset($row, $addFieds);
			$GLOBALS['addRows'] = $addRows;
		}
		$this->ordRow = array();
		$this->arcID = $aid;
	}
	
	function product_orders($aid=0)
	{
		$this->__construct($aid);	
	}
		
	function ___interface($dopost)
	{
		$this->{'call_'.$dopost}();
	}
	
	function call_query()
	{
		$no = isset($_GET['no']) ? $_GET['no'] : 0;
		$no = eregi_replace("[^-a-z0-9]", "", $no);
		if(!$no)
		{
			showMsg('请输入合法订单！', -1); exit;
		}
		//以订单编号为条件查询
		$ordersInfo = $GLOBALS['dsql']->GetOne("SELECT * FROM #@__product_orders WHERE oid='$no'");
		if(!isset($ordersInfo['id']))
		{
			showMsg('无效的订单编号！', -1); exit;
		}
		//查出相应的订单记录
		$GLOBALS['ordersLogs'] = array();
		$GLOBALS['dsql']->Execute('me', "SELECT `dateline`, `message`, `operator` FROM #@__product_orders_logs WHERE ordersid='$ordersInfo[id]'");
		while($row = $GLOBALS['dsql']->GetArray('me'))
		{
			$row['dateline'] = MyDate('Y-m-d H:i', $row['dateline']);
			$GLOBALS['ordersLogs'][] = $row;
		}
		$GLOBALS['dsql']->FreeResult('me');
		//把数据输出到模板中
		include_once(DEDEINC.'/dedetemplate.class.php');
		$dtp = new DedeTemplate();
		$dtp->Assign('arcRow', $this->arcRow);
		$dtp->Assign('userLang', $this->userLang);
		$dtp->Assign('ordersInfo', $ordersInfo);
		$dtp->LoadTemplate(PLUS_TPL_.'/product_orders_query.htm');
		$dtp->Display();
	}
	
	function call_form()
	{
		include_once(DEDEINC.'/dedetemplate.class.php');
		$dtp = new DedeTemplate();
		$dtp->Assign('arcRow', $this->arcRow);
		$dtp->Assign('userLang', $this->userLang);
		$dtp->Assign('formCode', md5(DEDEINC.$GLOBALS['cfg_cookie_encode']));
		$dtp->LoadTemplate(PLUS_TPL_.'/product_orders_form.htm');
		$dtp->Display();
	}
	
	function call_post()
	{
		extract($_POST, EXTR_SKIP);
		if(!isset($rvar_formcode) || md5(DEDEINC.$GLOBALS['cfg_cookie_encode'])!=$rvar_formcode)
		{
			showMsg('表单验证无效！', -1); exit;
		}
		
		if(isset($rvar_telphone)) $rvar_telphone = ereg_replace("[^0-9]", "", $rvar_telphone);
		if(isset($rvar_nums)) $rvar_nums = intval(ereg_replace("[^0-9]", "", $rvar_nums));
		
		if($rvar_nums < 1)
		{
			showMsg('请填写要订购的数量！', -1); exit;		
		}
		
		if(empty($rvar_telphone))
		{
			showMsg('请填写电话号码！', -1); exit;
		}
		
		if(!isset($rvar_orgname) || strlen($rvar_orgname) < 2 || strlen($rvar_orgname) > 200)
		{
			showMsg('请填写合法的组织名称和个人！', -1); exit;
		}
		
		if(!isset($rvar_address) || empty($rvar_address))
		{
			showMsg('请填写联系地址！', -1); exit;
		}
		
		if(!isset($rvar_postcode) || empty($rvar_postcode))
		{
			showMsg('请填写邮政编码！', -1); exit;
		}
		
		if(isset($rvar_content)) $rvar_content = addslashes($rvar_content);
		
		$ordersNo = date("Ymdhis")."-".$rvar_telphone."-".$GLOBALS['cfg_ml']->M_ID;
		
		$result = $GLOBALS['dsql']->ExecuteNoneQuery("INSERT INTO #@__product_orders (`oid`, `aid`, `status`, `mid`, `dateline`, `uptime`, `userip`, `telphone`, `prnums`, `orgname`, `address`, `postcode`, `content`) 
		VALUES('$ordersNo', '".$this->arcID."', 0, '".$GLOBALS['cfg_ml']->M_ID."', '".time()."', 0, '".getIP()."', '$rvar_telphone', '$rvar_nums', '$rvar_orgname', '$rvar_address', '$rvar_postcode', '$rvar_content');");

		if($result)
		{
			//创建递一笔操作记录
			$row = $GLOBALS['dsql']->GetOne("SELECT last_insert_id() as id");
			$result = $GLOBALS['dsql']->ExecuteNoneQuery("INSERT INTO #@__product_orders_logs (`ordersid`, `dateline`, `message`, `operator`) VALUES('$row[id]', '".time()."', '创建订单！', '---');");
			showMsg('您的订单已经提交，谢谢！', 'product_orders.php?dopost=query&no='.$ordersNo);
			exit;
		}
		else
		{
			echo mysql_error(); exit;
		}
	}
	function free_result()
	{
		$this->ordRow = array();
		$this->arcRow = array();
	}
}

$pr_Order = new product_orders($aid);
$pr_Order->___interface($dopost); $pr_Order->free_result();
?>