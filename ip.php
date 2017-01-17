<?php
header('Content-type: text/html; charset=utf-8');

echo '鍋貼';
//echo getip();

$tmp=$_SERVER["HTTP_X_FORWARDED_FOR"];
$tmp='HTTP_X_FORWARDED_FOR<pre>'.print_r($tmp,true).'</pre>'.'<hr/>';
echo $tmp;
$tmp=$_SERVER["HTTP_CLIENT_IP"];
$tmp='HTTP_CLIENT_IP<pre>'.print_r($tmp,true).'</pre>'.'<hr/>';
echo $tmp;
$tmp=$_SERVER["REMOTE_ADDR"];
$tmp='REMOTE_ADDR<pre>'.print_r($tmp,true).'</pre>'.'<hr/>';
echo $tmp;


$tmp=getenv("HTTP_X_FORWARDED_FOR");
$tmp='HTTP_X_FORWARDED_FOR<pre>'.print_r($tmp,true).'</pre>'.'<hr/>';
echo $tmp;
$tmp=getenv("HTTP_CLIENT_IP");
$tmp='HTTP_CLIENT_IP<pre>'.print_r($tmp,true).'</pre>'.'<hr/>';
echo $tmp;
$tmp=getenv("REMOTE_ADDR");
$tmp='REMOTE_ADDR<pre>'.print_r($tmp,true).'</pre>'.'<hr/>';
echo $tmp;


 /*获取客户端IP*/ 
function getip(){ 
	if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) 
	$ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
	else if (@$_SERVER["HTTP_CLIENT_IP"]) 
	$ip = $_SERVER["HTTP_CLIENT_IP"]; 
	else if (@$_SERVER["REMOTE_ADDR"]) 
	$ip = $_SERVER["REMOTE_ADDR"]; 
	else if (@getenv("HTTP_X_FORWARDED_FOR")) 
	$ip = getenv("HTTP_X_FORWARDED_FOR"); 
	else if (@getenv("HTTP_CLIENT_IP")) 
	$ip = getenv("HTTP_CLIENT_IP"); 
	else if (@getenv("REMOTE_ADDR")) 
	$ip = getenv("REMOTE_ADDR"); 
	else 
	$ip = "Unknown"; 
	return $ip; 
}
?>