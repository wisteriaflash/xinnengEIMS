<?php
$z[0]=Array("field","",254,273);
$z[0][4]['name']="title";
$z[1]=Array("global","",274,300);
$z[1][4]['name']="cfg_webname";
$z[2]=Array("global","",322,351);
$z[2][4]['name']="cfg_templeturl";
$z[3]=Array("field","",448,471);
$z[3][4]['name']="keywords";
$z[4]=Array("field","",511,562);
$z[4][4]['name']="description";
$z[4][4]['function']="html2text(@me)";
$z[5]=Array("global","",626,651);
$z[5][4]['name']="cfg_cmsurl";
$z[6]=Array("include","",963,1000);
$z[6][4]['filename']="header.htm";
$z[7]=Array("global","",1123,1149);
$z[7][4]['name']="cfg_clihost";
$z[8]=Array("lang","",1151,1173);
$z[8][4]['name']="indexname";
$z[9]=Array("field","",1178,1207);
$z[9][4]['name']="position";
$z[10]=Array("list","\r\n      <li> [field:array runphp='yes']@me = (empty(@me['litpic']) ? \"\" : \"<a href='{@me['arcurl']}' class='preview' target='_blank'><img src='{@me['litpic']}'/></a>\"); [/field:array]\r\n       [<b>[field:typelink/]</b>] <a href=\"[field:arcurl/]\" class=\"title\" target=\"_blank\">[field:title/]</a> <span class=\"info\"> <small>日期：</small>[field:pubdate function=\"GetDateTimeMK(@me)\"/] <small>点击：</small>[field:click/] <small>好评：</small>[field:scores/] </span>\r\n       <p class=\"intro\"> [field:description/]... </p>\r\n      </li>\r\n      ",1297,1899);
$z[10][4]['pagesize']="10";
$z[10][4]['orderby']="pubdate";
$z[11]=Array("pagelist","",2014,2092);
$z[11][4]['listitem']="info,index,end,pre,next,pageno,option";
$z[11][4]['listsize']="5";
$z[12]=Array("arclist","\r\n        <li><a href=\"[field:arcurl/]\" target=\"_blank\">[field:title/]</a>\r\n         <p>[field:description function='cn_substr(@me,80)'/]...</p>\r\n        </li>\r\n        ",2370,2595);
$z[12][4]['flag']="c";
$z[12][4]['titlelen']="42";
$z[12][4]['row']="6";
$z[13]=Array("arclist","\r\n        <li><a href=\"[field:arcurl/]\" target=\"_blank\">[field:title/]</a></li>\r\n        ",2851,2990);
$z[13][4]['row']="10";
$z[13][4]['orderby']="click";
$z[14]=Array("include","",3079,3117);
$z[14][4]['filename']="footer.htm";

?>