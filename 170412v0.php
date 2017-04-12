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
print_r($header_data);
	

?>
