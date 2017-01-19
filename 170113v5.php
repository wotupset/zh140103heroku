<?php

require_once('170113v4b.php');
if( $auth != "國" ){exit;}

function curlpost_html($x){
	//$output_path=$GLOBALS['output_path'];
	$output_path=$x;
	//
	$tmp=$output_path;
	$upf='@'.realpath($tmp).';filename=this.htm';
	$md5=md5_file($tmp);
	//echo $upl;
	$myvars['upf']= $upf;
	$myvars['pass']= 'xopowo';
	//$myvars=array('file' => '@' . realpath('example.txt'));
	//
	$url='http://zh161005.comli.com/htm/151225-2244put.php';
	$useragent='Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36';
	$ch = curl_init();//初始化
	if(!$ch){die('[x]curl初始化失敗');}

	curl_setopt($ch, CURLOPT_URL,            $url);//網址
	curl_setopt($ch, CURLOPT_POST,           1);//post模式
	curl_setopt($ch, CURLOPT_POSTFIELDS,     $myvars);//參數
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);//跟随重定向页面//safe_mode = Off 
	curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
	curl_setopt($ch, CURLOPT_USERAGENT,      $useragent);
	
	$getdata  = curl_exec($ch);//抓取URL並把它傳遞給變數
	$getinfo  = curl_getinfo($ch);//結果資訊
	$geterror = curl_errno($ch);
	curl_close($ch);
	//
	$FFF='';
	$FFF.=print_r($getdata,true);
	$FFF.=print_r($getinfo,true);
	$FFF.=print_r($geterror,true);
	//echo '<pre>'.$FFF.'</pre>';
	//
	$x=$FFF;
	return $x;
}


?>
