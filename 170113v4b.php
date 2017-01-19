<?php
require_once('curl_getinfo.php');
$query_string=$_SERVER['QUERY_STRING'];
$url=$query_string;


if(1){
  $x=curl_FFF($url);
  //echo print_r($x,true);exit;
  $getdata =$x_0 =$x[0];//資料
  $getinfo =$x_1 =$x[1];//訊息
  $geterror=$x_2 =$x[2];//錯誤
  //simple_html_dom
  if(!$getdata){echo print_r($getinfo,true);exit;}
  echo print_r($getinfo,true);
  $content=$getdata;
}

//
$auth="國";

?>
