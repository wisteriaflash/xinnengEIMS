<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_ordersList');
require_once(DEDEINC."/datalistcp.class.php");
$dopost = isset($dopost) ? trim($dopost) : 'list';

$ordersStatus = array(0 => '新建订单', 1 => '正在处理', 2 => '处理完毕', 999 => '订单结束');

if($dopost == 'status' && isset($ids) && $status)
{
	if(is_array($ids) && is_numeric($status) && isset($ordersStatus[$status]))
	{
		$ids = implode(",", $ids);
		$dsql->ExecuteNoneQuery("UPDATE #@__product_orders SET `status`='$status' WHERE id IN($ids)");
	}
	if(is_numeric($ids) && is_numeric($status) && isset($ordersStatus[$status]))
	{
		$dsql->ExecuteNoneQuery("UPDATE #@__product_orders SET `status`='$status' WHERE id='$ids'");
	}
	showMsg('选中订单状态已经改变！', -1); exit;
}

if($dopost == 'del')
{
	if(is_array($ids))
	{
		$ids = implode(",", $ids);
		$dsql->ExecuteNoneQuery("DELETE FROM #@__product_orders WHERE id IN($ids) AND `status`=0");
	}
	if(is_numeric($ids))
	{
		$dsql->ExecuteNoneQuery("DELETE FROM #@__product_orders WHERE id='$ids' AND `status`=0");
	}
	showMsg('选中订单已经删除！', -1); exit;
}

if($dopost == 'handle')
{
	$id = isset($id) && is_numeric($id) ? $id : 0;
	if(!$id)
	{
		showMsg('未定义的操作！', -1); exit;
	}
	
	$ordersInfo = $dsql->GetOne("SELECT * FROM #@__product_orders WHERE id='$id'");
	
	if(!is_array($ordersInfo))
	{
		showMsg('订单不存在！', -1); exit;
	}
	
	$userInfo = $dsql->GetOne("SELECT uname FROM #@__member WHERE mid='$ordersInfo[mid]'");
	$userInfo['uname'] = isset($userInfo['uname']) ? $userInfo['uname'] : '游客';
	$userInfo['mid'] = $ordersInfo['mid'];
		
	$action = isset($_GET['action']) ? $_GET['action'] : '';
	if($action == 'operate')
	{
		extract($_POST, EXTR_SKIP);
		$userName = $cuserLogin->userName;
		if(!isset($message) || empty($message))
		{
			showMsg('请填写处理说明！', -1); exit;
		}
		$message = addslashes(stripslashes($message));
		$dsql->ExecuteNoneQuery("INSERT INTO #@__product_orders_logs(ordersid,dateline,message,operator) VALUES('$id', '".time()."', '$message', '$userName');");
		showMsg('成功更新操作记录！', "product_orders.php?id={$id}&dopost=handle"); exit;
	}
	
	if($ordersInfo['aid'])
	{
		require_once(DEDEINC.'/arc.archives.class.php');
		$arc = new Archives($ordersInfo['aid']);
		$arcRow = $arc->Fields;
		$addFieds = $arc->ChannelUnit->ChannelFields;
		$row = $arc->dsql->GetOne($arc->dsql->queryString);
		$addRows = array();
		foreach($addFieds as $k => $val)
		{
			if($k == 'body')
			{
				$arcRow[$k] = $row[$k]; continue;
			}
			$addRows[$k]['itemName'] = $val['itemname'];
			$addRows[$k]['itemFied'] = $val['tagname'];
			$addRows[$k]['itemValue'] = $row[$k];
		}
		unset($row, $addFieds);
	}

	$whereSql = "WHERE ordersid='$id'";
	
	$query = "SELECT * FROM #@__product_orders_logs $whereSql ORDER BY dateline DESC";
	
	$dlist = new DataListCP();
	$dlist->pageSize = 30;
	$dlist->SetParameter('dopost', $dopost);
	$dlist->SetParameter('id', $id);
	$dlist->SetTemplate(DEDEADMIN."/templets/product_orders_handle.htm");
	$dlist->SetSource($query);
	$dlist->Display();
	$dlist->Close(); exit;
}

if($dopost == 'list')
{
	$whereSql = 'WHERE id>0';
	$oid = isset($oid) ? $oid : '';
	if($oid) $whereSql .= " AND oid LIKE '".$oid."'";
	
	$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM #@__product_orders $whereSql");
	$total = $row['nums'];
	
	$query = "SELECT * FROM #@__product_orders $whereSql ORDER BY id DESC";	
	
	function _getStatus($status=0)
	{
		global $ordersStatus;
		return isset($ordersStatus[$status]) ? $ordersStatus[$status] : '火星订单';
	}
	
	$dlist = new DataListCP();
	$dlist->pageSize = 30;
	$dlist->SetParameter('dopost', $dopost);
	if($oid) $dlist->SetParameter('oid', $oid);
	$dlist->SetTemplate(DEDEADMIN."/templets/product_orders.htm");
	$dlist->SetSource($query);
	$dlist->Display();
	$dlist->Close();
}
?>