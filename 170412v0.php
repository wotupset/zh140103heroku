<?php
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
//
require_once('curl_getinfo.php');

//
$url = 'https://pbs.twimg.com/media/C9N49FPVwAAz6xm.jpg:orig';
$header_data = get_headers($url);
	

////
$FFF='';
$FFF.='<pre>'.print_r($header_data,true).'</pre>';
html_body($FFF);
exit;
////////////////function

function html_body($x){
	//$webm_count  =$x[5];
	//
$x=<<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
<body>
$x
</body>	
</html>
EOT;
?>
