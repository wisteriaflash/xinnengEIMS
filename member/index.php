<?php
require_once(dirname(__FILE__)."/config.php");
//会员后台
$iscontrol = 'yes';
if(!$cfg_ml->IsLogin())
{
		include_once(dirname(__FILE__)."/templets/index-notlogin.htm");
}
else
{
		require_once(DEDEINC.'/channelunit.func.php');
		/* 最新产品20条 */
		$archives = array();
		$sql = "select arc.*, category.namerule, category.typedir, mem.userid
		from #@__archives arc
		left join #@__arctype category on category.id=arc.typeid
		left join #@__member mem on mem.mid=arc.mid
		where arc.channel=6 And category.lang like '{$userLang}' And arc.arcrank > -1 And arcrank <= {$cfg_ml->M_Rank}
		order by arc.sortrank desc limit 20";
		$dsql->SetQuery($sql);
		$dsql->Execute();
		while ($row = $dsql->GetArray())
		{
			$row['htmlurl'] = GetFileUrl($row['id'], $row['typeid'], $row['senddate'], $row['title'], $row['ismake'], $row['arcrank'], $row['namerule'], $row['typedir'], $row['filename']);
			$archives[] = $row;
		}

		/** 我的收藏 **/
		$favorites = array();
		$dsql->Execute('fl',"Select * From `#@__member_stow` where mid='{$cfg_ml->M_ID}'  limit 8");
		while($arr = $dsql->GetArray('fl'))
		{
			$favorites[] = $arr;
		}
		
		/** 产品数量 **/
		$products = $dsql->GetOne("SELECT COUNT(*) AS nums FROM #@__addonproduct");		
		
		/** 订单数量 **/
		$orders = $dsql->GetOne("SELECT COUNT(*) AS nums FROM #@__product_orders WHERE mid = '{$cfg_ml->M_ID}'");	

		/** 有没新短信 **/
		$pms = $dsql->GetOne("SELECT COUNT(*) AS nums FROM #@__member_pms WHERE toid='{$cfg_ml->M_ID}' AND `hasview`=0 AND folder = 'inbox'");		

		$dpl = new DedeTemplate();
		$tpl = dirname(__FILE__)."/templets/index.htm";
		$dpl->LoadTemplate($tpl);
		$dpl->display();
}
?>