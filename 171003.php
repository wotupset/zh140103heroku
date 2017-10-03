<?php
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
//
$query_string=$_SERVER['QUERY_STRING'];
$url=$query_string;
//
require_once('curl_getinfo.php');
$x=curl_FFF($url);
echo print_r($x,true);
exit;
?>
