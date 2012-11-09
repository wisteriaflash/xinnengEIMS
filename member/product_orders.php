<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);

require_once(DEDEINC."/datalistcp.class.php");
$add_left_where = "WHERE p.dateline>0 AND p.mid='".$cfg_ml->M_ID."'";

if(!isset($type) || empty($type)) $type = 'all';

if($type == 'isclick')
{
	$add_left_where .= " AND p.`status`>0";
}

if($type == 'unclick')
{
	$add_left_where .= " AND p.`status`=0";
}

function _getStatus($status=0)
{
	$ordersStatus = array(0 => '新建订单', 1 => '正在处理', 2 => '处理完毕', 999 => '订单结束');
	return isset($ordersStatus[$status]) ? $ordersStatus[$status] : '火星订单';
}

$query = "SELECT p.*, arc.title FROM #@__product_orders AS p LEFT JOIN #@__archives AS arc ON arc.id=p.aid $add_left_where ORDER BY p.dateline DESC";

$dlist = new DataListCP();
$dlist->pageSize = 10;
if($type) $dlist->SetParameter('type', $type);
$dlist->SetTemplate(DEDEMEMBER."/templets/product_orders.htm");
$dlist->SetSource($query);
$dlist->Display();
$dlist->Close(); exit;
?>