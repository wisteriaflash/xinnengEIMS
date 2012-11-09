<?php
$m_file = dirname(__file__)."/sqltext.txt";
$fp = fopen($m_file,'r');
$allsource = fread($fp,filesize($m_file));
fclose($fp);
$basestr =  base64_encode($allsource);
echo $basestr; 
?>