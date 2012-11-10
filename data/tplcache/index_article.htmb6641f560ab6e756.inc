<?php
$z[0]=Array("global","",254,280);
$z[0][4]['name']="cfg_webname";
$z[1]=Array("field","",321,344);
$z[1][4]['name']="keywords";
$z[2]=Array("field","",384,435);
$z[2][4]['name']="description";
$z[2][4]['function']="html2text(@me)";
$z[3]=Array("global","",453,482);
$z[3][4]['name']="cfg_templeturl";
$z[4]=Array("global","",584,610);
$z[4][4]['name']="cfg_clihost";
$z[5]=Array("global","",711,740);
$z[5][4]['name']="cfg_templeturl";
$z[6]=Array("global","",838,867);
$z[6][4]['name']="cfg_templeturl";
$z[7]=Array("global","",963,988);
$z[7][4]['name']="cfg_cmsurl";
$z[8]=Array("include","",1466,1503);
$z[8][4]['filename']="header.htm";
$z[9]=Array("arclist","\r\n      [field:id function=\"Getimg(2,1280,410)\" /]\r\n    ",1644,1759);
$z[9][4]['titlelen']="50";
$z[9][4]['row']="1";
$z[9][4]['aid']="2";
$z[10]=Array("channelartlist","\r\n   <dl class=\"tbox\">\r\n    <dt><a class=\"channel channel_{dede:field name='typeid'/}\" href=\"{dede:field name='typeurl'/}\"><span class=\"title\">{dede:field name='typename'/}</span><i></i></a><span class=\"more\"><a href=\"{dede:field name='typeurl'/}\">更多>></a></span></dt>\r\n    <dd>\r\n      {dede:arclist titlelen='50' row='1' flag='h' orderby='sortrank'}\r\n      <div class=\"hotpic\">\r\n        <a class=\"pic\" href=\"[field:arcurl /]\" target=\"_blank\"><img src=\"[field:picname /]\" /></a>\r\n        <p class=\"title\"><span class=\"maskbg\"></span><em>[field:title /]</em></p>\r\n      </div>\r\n      {/dede:arclist}\r\n     <ul class=\"d1 ico3\">\r\n      {dede:arclist titlelen='60' noflag='h' row='4'}\r\n      <li><a href=\"[field:arcurl /]\" target=\"_blank\">[field:title /]</a><span>[field:pubdate function=\"MyDate('m-d',@me)\"/]</span></li>\r\n      {/dede:arclist}\r\n     </ul>\r\n    </dd>\r\n   </dl>\r\n   ",1883,2823);
$z[10][4]['typeid']="3,4,0";
$z[11]=Array("global","",2875,2904);
$z[11][4]['name']="cfg_templeturl";
$z[12]=Array("mylang","",2948,2977);
$z[12][4]['eid']="contact";
$z[13]=Array("include","",2997,3035);
$z[13][4]['filename']="footer.htm";

?>