<?php

$lang_map_arr = array(
   'gb2312'=>'简体中文',
   'en'=>'English',
   'big5'=>'繁體中文'
);

$install_lang = array();

$install_lang['gb2312']['about'] = '公司简介';
$install_lang['gb2312']['news'] = '新闻动态';
$install_lang['gb2312']['product'] = '产品展示';
$install_lang['gb2312']['joinus'] = '人才招聘';
$install_lang['gb2312']['feedback'] = '在线反馈';
$install_lang['gb2312']['membercenter'] = '会员中心';

$install_lang['big5']['about'] = '公司簡介';
$install_lang['big5']['news'] = '新聞動態';
$install_lang['big5']['product'] = '產品展示';
$install_lang['big5']['joinus'] = '人才招聘';
$install_lang['big5']['feedback'] = '在綫反饋';
$install_lang['big5']['membercenter'] = '會員中心';

$install_lang['en']['about'] = 'Introduce';
$install_lang['en']['news'] = 'News';
$install_lang['en']['product'] = 'Products';
$install_lang['en']['joinus'] = 'Join US';
$install_lang['en']['feedback'] = 'Feedback';
$install_lang['en']['membercenter'] = 'Member Center';

$install_catalog_sql['about']   = "INSERT INTO `#@__arctype` (`reid`, `topid`, `sortrank`, `typename`, `lang`, `typedir`, `isdefault`, `defaultname`, `channeltype`, `ispart`, `tempsgpage`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `description`, `keywords`, `ishidden`, `content`) VALUES ('~topid~', '~topid~', '1', '~name~', '~lang~', '{cmspath}/a/~lang~/', '~isdefault~', 'about.html', 1, 3, '{style}/{lang}/catalog_sgpage.htm', '{style}/{lang}/index_article.htm', '{style}/{lang}/list_article.htm', '{style}/{lang}/article_article.htm', '{typedir}/{Y}{M}{D}/{aid}.html', '{typedir}/index-{tid}-{page}.html', '', '', 0, 'about'); ";

$install_catalog_sql['news']    = "INSERT INTO `#@__arctype` ( `reid`, `topid`, `sortrank`, `typename`, `lang`, `typedir`, `isdefault`, `defaultname`, `channeltype`, `ispart`, `tempsgpage`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `description`, `keywords`, `ishidden`, `content`) VALUES ('~topid~', '~topid~', '2', '~name~', '~lang~', '{cmspath}/a/~lang~/news', '~isdefault~', 'index.html', 1, 0, '{style}/{lang}/catalog_sgpage.htm', '{style}/{lang}/index_article.htm', '{style}/{lang}/list_article.htm', '{style}/{lang}/article_article.htm', '{typedir}/{Y}{M}{D}/{aid}.html', '{typedir}/index-{tid}-{page}.html', '', '', 0, ''); ";

$install_catalog_sql['product'] = "INSERT INTO `#@__arctype` ( `reid`, `topid`, `sortrank`, `typename`, `lang`, `typedir`, `isdefault`, `defaultname`, `channeltype`, `ispart`, `tempsgpage`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `description`, `keywords`, `ishidden`, `content`) VALUES ('~topid~', '~topid~', '3', '~name~', '~lang~', '{cmspath}/a/~lang~/product', '~isdefault~', 'index.html', 6, 0, '{style}/{lang}/catalog_sgpage.htm', '{style}/{lang}/index_product.htm', '{style}/{lang}/list_product.htm', '{style}/{lang}/article_product.htm', '{typedir}/{Y}{M}{D}/{aid}.html', '{typedir}/index-{tid}-{page}.html', '', '', 0, ''); ";


$install_catalog_sql['joinus'] = "INSERT INTO `#@__arctype` ( `reid`, `topid`, `sortrank`, `typename`, `lang`, `typedir`, `isdefault`, `defaultname`, `channeltype`, `ispart`, `tempsgpage`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `description`, `keywords`, `ishidden`, `content`) VALUES ('~topid~', '~topid~', 4, '~name~', '~lang~', '/plus/jobs.php?lang=~lang~', -1, 'index.html', 1, 2, '{style}/{lang}/catalog_sgpage.htm', '{style}/{lang}/index_article.htm', '{style}/{lang}/list_article.htm', '{style}/{lang}/article_article.htm', '{typedir}/{Y}/{M}{D}/{aid}.html', '{typedir}/index-{tid}-{page}.html', '', '', 0, ''); ";

$install_catalog_sql['feedback'] = "INSERT INTO `#@__arctype` ( `reid`, `topid`, `sortrank`, `typename`, `lang`, `typedir`, `isdefault`, `defaultname`, `channeltype`, `ispart`, `tempsgpage`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `description`, `keywords`, `ishidden`, `content`) VALUES ('~topid~', '~topid~', 5, '~name~', '~lang~', '/plus/guestbook.php?lang=~lang~', -1, 'index.html', 1, 2, '{style}/{lang}/catalog_sgpage.htm', '{style}/{lang}/index_article.htm', '{style}/{lang}/list_article.htm', '{style}/{lang}/article_article.htm', '{typedir}/{Y}/{M}{D}/{aid}.html', '{typedir}/index-{tid}-{page}.html', '', '', 0, ''); ";

$install_catalog_sql['membercenter'] = "INSERT INTO `#@__arctype` ( `reid`, `topid`, `sortrank`, `typename`, `lang`, `typedir`, `isdefault`, `defaultname`, `channeltype`, `ispart`, `tempsgpage`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `description`, `keywords`, `ishidden`, `content`) VALUES ('~topid~', '~topid~', 6, '~name~', '~lang~', '/member/?lang=~lang~', -1, 'index.html', 1, 2, '{style}/{lang}/catalog_sgpage.htm', '{style}/{lang}/index_article.htm', '{style}/{lang}/list_article.htm', '{style}/{lang}/article_article.htm', '{typedir}/{Y}/{M}{D}/{aid}.html', '{typedir}/index-{tid}-{page}.html', '', '', 0, ''); ";


?>