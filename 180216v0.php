<?php
/*
參考 170526v0.php

*/
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
//header('Content-Type: application/json; charset=utf-8');
//
//print_r($_POST);
$url=$_GET['inputurl'];
$url=str_replace("https://","http://",$url,$i);

//
if( substr_count($url, "http")>0 ){
	//ok
}else{
	//echo "?res=";
	//$FFF=$html_inputbox;
	echo html_body('url???');exit;
}
//
require_once('simple_html_dom.php');
require_once('curl_getinfo.php');

//
//$url = 'http://pbs.twimg.com/media/C9N0yqxUQAE8AsK.jpg';

if(0){
	$header_data = get_headers($url);
	//print_r(strpos($header_data[0],'200'));
	//var_dump(strpos($header_data[0],'200'));
	//if(strpos($header_data[0],'200')>0){}
	if(strpos($header_data[0],'200')===false){
		echo html_body('404');exit;
		//echo 'get_headers='.$header_data[0];
	}else{
		//echo html_body('ok');exit;
	}

	if(strpos($header_data[2],'image')===false){
		echo html_body('不是圖片');exit;
		//echo 'get_headers='.$header_data[0];
	}else{
		//echo html_body('ok');exit;
	}

	//exit;
	$content_type=explode(':',$header_data[2]);
	print_r($content_type[1]);
	exit;
}

//$url='https://web.archive.org/web/2017/'.$url;
if(1){
  $x=curl_FFF($url);
  //echo print_r($x,true);exit;
  $getdata =$x_0 =$x[0];//資料
  $getinfo =$x_1 =$x[1];//訊息
  $geterror=$x_2 =$x[2];//錯誤
  //simple_html_dom
  //if(!$getdata){echo print_r($getinfo,true);exit;}
  //echo print_r($getinfo,true);//檢查點
  $content=$getdata;
}

//print_r($getinfo);print_r($geterror);exit;

if($geterror >0){
	//有錯誤
	echo html_body($geterror);exit;
}else{
	//正常
	if(strlen($content) == 0){
		echo html_body('0');exit;
	}
}
//print_r( strlen($content) );exit;

////
//echo $getinfo['content_type'];exit;
if(function_exists('tempnam') &&0){
	$temp_file = tempnam(sys_get_temp_dir(), 'Poi');
	print_r(sys_get_temp_dir());
}else{
	$temp_file = $phpself2.'.tmp';
}
//exit;
//print_r($temp_file);exit;

//$chk = file_put_contents($temp_file,$content) or die('!put');
//$info_array=getimagesize($temp_file);
//print_r($info_array);// 

if(strpos( $getinfo['content_type'] ,'image')===false){ //$info_array['mime']
	//不是圖片
	echo html_body('不是圖片');exit;
	//echo 'get_headers='.$header_data[0];
}else{
	//echo html_body('ok');exit;
}
	
//exit;
$filetype=$getinfo['content_type'];
$filetype_ext=explode('/',$filetype);
$savename=$time.'.'.$filetype_ext[1];
//print_r($savename);exit;

header('Content-type:'.$filetype.'');//指定文件類型
header('Content-Disposition: filename="'.$savename.'"');//
echo $getdata;
//readfile($temp_file);
exit;
////////////////function

function html_body($x){
	//$webm_count  =$x[5];
	//

$html_inputbox=<<<EOT
<form id='form01' enctype="multipart/form-data" action='$phpself' method="get" onsubmit="">
<input type="text" name="inputurl" size="20" value="">
<input type="submit" name="sendbtn" value="送出">
</form>
EOT;
//
$x=<<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>標題</title>
</head>
<body>
$html_inputbox
$x
</body>	
</html>
EOT;
	//	
	return $x;
}
?>