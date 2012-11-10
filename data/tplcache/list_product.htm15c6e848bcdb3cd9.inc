<?php
$z[0]=Array("global","",254,280);
$z[0][4]['name']="cfg_webname";
$z[1]=Array("global","",281,307);
$z[1][4]['name']="cfg_webname";
$z[2]=Array("global","",329,358);
$z[2][4]['name']="cfg_templeturl";
$z[3]=Array("field","",455,478);
$z[3][4]['name']="keywords";
$z[4]=Array("field","",518,569);
$z[4][4]['name']="description";
$z[4][4]['function']="html2text(@me)";
$z[5]=Array("global","",611,637);
$z[5][4]['name']="cfg_cmspath";
$z[6]=Array("global","",723,748);
$z[6][4]['name']="cfg_cmsurl";
$z[7]=Array("include","",1212,1249);
$z[7][4]['filename']="header.htm";
$z[8]=Array("field","",1363,1392);
$z[8][4]['name']="position";
$z[9]=Array("list","\r\n        <li>\r\n          <a href=\"[field:arcurl/]\" class=\"pic\" target=\"_blank\"><img src=\"[field:litpic/]\" alt=\"[field:title function='html2text(@me)'/]\"/></a>\r\n          <a href=\"[field:arcurl/]\" class=\"title\">[field:title/]</a>\r\n          <span class=\"date\"><small>最后更新：</small>[field:pubdate function=\"GetDateMK(@me)\"/]</span>\r\n        </li>\r\n          ",1482,1885);
$z[9][4]['pagesize']="12";
$z[10]=Array("pagelist","",1996,2067);
$z[10][4]['listitem']="info,index,end,pre,next,pageno";
$z[10][4]['listsize']="5";
$z[11]=Array("arclist","\r\n            <li>\r\n              <a href=\"[field:arcurl/]\" target=\"_blank\" class=\"preview\"><img src=\"[field:litpic/]\" alt=\"[field:title function='html2text(@me)'/]\"/></a>\r\n              <a href=\"[field:arcurl/]\" class=\"title\" target=\"_blank\">[field:title/]</a>\r\n              <span class=\"intro\">更新:[field:pubdate function=\"GetDateMK(@me)\"/]</span>\r\n            </li>\r\n                     ",2316,2776);
$z[11][4]['row']="5";
$z[11][4]['type']="image.";
$z[11][4]['orderby']="click";
$z[12]=Array("arclist","\r\n            <li><a href=\"[field:arcurl/]\" target=\"_blank\"><img src=\"[field:litpic/]\" alt=\"[field:title function='html2text(@me)'/]\"/><span class=\"title\">[field:title/]</span></a></li>\r\n                    ",2994,3260);
$z[12][4]['row']="8";
$z[12][4]['type']="image.commend.";
$z[13]=Array("myad","",3380,3412);
$z[13][4]['name']="listRtPicAD2";
$z[14]=Array("include","",3460,3498);
$z[14][4]['filename']="footer.htm";

?>