<?php
require_once(dirname(__FILE__)."/../config.php");

//载入可发布频道
$addset = '';
$dsql->SetQuery("Select id,typename,addcon,mancon From `#@__channeltype` where id<>-1 And isshow=1 order by id asc");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$addset .= "  <m:item name='{$row->typename}' ischannel='1' link='{$row->mancon}?channelid={$row->id}' linkadd='{$row->addcon}?channelid={$row->id}' channelid='{$row->id}' rank='' target='main' />\r\n";
}

//载入插件菜单
$plusset = '';
$dsql->SetQuery("Select * From `#@__plus` where isshow=1 order by aid asc");
$dsql->Execute();
while($row = $dsql->GetObject()) {
	$plusset .= $row->menustring."\r\n";
}

$menusMain = "
-----------------------------------------------
<m:top item='1_' name='常用操作' display='block'>
  <m:item name='管理首页' link='index_body.php' target='main' />
  <m:item name='网站栏目管理' link='catalog_main.php' ischannel='1' addalt='创建栏目' linkadd='catalog_add.php?listtype=all' rank='t_List,t_AccList' target='main' />
  <m:item name='订单管理' link='product_orders.php' rank='c_ordersList' target='main' />
  <m:item name='人才招聘管理' link='job_main.php' rank='info_Job' target='main' />
  <m:item name='自定义资料' link='lang_main.php' rank='info_Lang' target='main' />
  <m:item name='评论管理' link='feedback_main.php' rank='sys_Feedback' target='main' />
  <m:item name='留言本管理' link='catalog_do.php?dopost=guestbook' rank='plus_留言簿模块' target='main' />
  <m:item name='所有文档列表' link='content_list.php' rank='a_List,a_AccList' target='main' />
  <m:item name='等审核的文档' link='content_list.php?arcrank=-1' rank='a_Check,a_AccCheck' target='main' />
  <m:item name='我发布的文档' link='content_list.php?mid=".$cuserLogin->getUserID()."' rank='a_List,a_AccList,a_MyList' target='main' />
</m:top>

<m:top item='2_' name='内容管理' display='block'>
  $addset    
  <m:item name='内容回收站' link='recycling.php' ischannel='1' addalt='清空回收站' addico='img/gtk-del.png' linkadd='archives_do.php?dopost=clear&aid=no' rank='a_List' target='main' />
  <m:item name='所有文档列表' link='content_list.php' rank='a_List,a_AccList' target='main' />
  <m:item name='等审核的文档' link='content_list.php?arcrank=-1' rank='a_Check,a_AccCheck' target='main' />
  <m:item name='我发布的文档' link='content_list.php?mid=".$cuserLogin->getUserID()."' rank='a_List,a_AccList,a_MyList' target='main' />
  <m:item name='附件数据管理' link='media_main.php' rank='sys_Upload,sys_MyUpload' target='main' />
</m:top>

<m:top item='2_a_' name='频道模型' display='block'>
  <m:item name='内容模型管理' link='mychannel_main.php' rank='c_List' target='main' />
  <m:item name='自定义表单' link='diy_main.php' rank='c_List' target='main' />
 </m:top>


<m:top item='3_' name='辅助插件' display='block'>
  <m:item name='插件管理器' link='plus_main.php' rank='sys_Edit' target='main' />
  $plusset
</m:top>

<m:top item='3_a_' name='采集管理' display='block'>
  <m:item name='采集节点管理' link='co_main.php' rank='co_ListNote' target='main' />
  <m:item name='临时内容管理' link='co_url.php' rank='co_ViewNote' target='main' />
  <m:item name='导入采集规则' link='co_get_corule.php' rank='co_GetOut' target='main'/>
  <m:item name='监控采集模式' link='co_gather_start.php' rank='co_GetOut' target='main'/>
</m:top>


<m:top item='4_' name='自动任务' display='block'>
  <m:item name='一键更新网站' link='makehtml_all.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新系统缓存' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
</m:top>

<m:top item='4_a_' name='HTML更新' display='block'>
  <m:item name='更新主页HTML' link='makehtml_homepage.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新栏目HTML' link='makehtml_list.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新文档HTML' link='makehtml_archives.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新网站地图' link='makehtml_map_guide.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新RSS文件' link='makehtml_rss.php' rank='sys_MakeHtml' target='main' />
</m:top>

<m:top item='5_' name='模板管理' display='block'>
  <m:item name='默认模板管理' link='templets_main.php' rank='temp_All' target='main'/>
  <m:item name='标签源码管理' link='templets_tagsource.php' rank='temp_All' target='main'/>
  <m:item name='自定义宏标记' link='mytag_main.php' rank='temp_MyTag' target='main'/>
  <m:item name='智能标记向导' link='mytag_tag_guide.php' rank='temp_Other' target='main'/>
  <m:item name='下载更多模板' link='http://www.dedeeims.com/download/templets/' rank='' target='_blank' />
</m:top>

<m:top item='5_a' name='系统帮助' display='block'>
  <m:item name='参考文档' link='http://www.dedeeims.com/help/' rank='' target='_blank' />
  <m:item name='推荐主机商' link='http://www.dedeeims.com/host/' rank='' target='_blank' />
  <m:item name='官方交流论坛' link='http://bbs.dedecms.com' rank='' target='_blank' />
</m:top>

<m:top item='6_' name='会员管理' display='block'>
  <m:item name='注册会员列表' link='member_main.php' rank='member_List' target='main' />
  <m:item name='会员短信管理' link='member_pm.php' rank='member_Type' target='main' />
  <m:item name='会员等级管理' link='member_rank.php' rank='member_Type' target='main' />
</m:top>

<m:top item='6_a' name='系统帮助' display='block'>
  <m:item name='参考文档' link='http://www.dedeeims.com/help/' rank='' target='_blank' />
  <m:item name='推荐主机商' link='http://www.dedeeims.com/host/' rank='' target='_blank' />
  <m:item name='官方交流论坛' link='http://bbs.dedecms.com' rank='' target='_blank' />
</m:top>

<m:top item='7_' name='系统设置' display='block'>
  <m:item name='系统基本参数' link='sys_info.php' rank='sys_Edit' target='main' />
  <m:item name='系统用户管理' link='sys_admin_user.php' rank='sys_User' target='main' />
  <m:item name='用户组设定' link='sys_group.php' rank='sys_Group' target='main' />
  <m:item name='系统日志管理' link='log_list.php' rank='sys_Log' target='main' />
  <m:item name='图片水印设置' link='sys_info_mark.php' rank='sys_Edit' target='main' />
  <m:item name='自定义文档属性' link='content_att.php' rank='sys_Att' target='main' />
  <m:item name='搜索关键词维护' link='search_keywords_main.php' rank='sys_Keyword' target='main' />
  <m:item name='数据库备份/还原' link='sys_data.php' rank='sys_Data' target='main' />
  <m:item name='SQL命令行工具' link='sys_sql_query.php' rank='sys_Data' target='main' />
  <m:item name='病毒扫描[S]' link='sys_safetest.php' rank='sys_verify' target='main' />
</m:top>
-----------------------------------------------
";
?>