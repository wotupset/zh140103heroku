<?php
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
$url=$_POST['inputurl'];
$url=str_replace("https://","http://",$url,$i);

//
if( substr_count($url, "http")>0 ){
	//ok
}else{
	//echo "?res=";
	//$FFF=$html_inputbox;
	$FFF='??';
	echo html_body($FFF);
	exit;
}
//
require_once('curl_getinfo.php');

//
//$url = 'http://pbs.twimg.com/media/C9N0yqxUQAE8AsK.jpg';


$header_data = get_headers($url);
//print_r(strpos($header_data[0],'200'));
//var_dump(strpos($header_data[0],'200'));
//if(strpos($header_data[0],'200')>0){}
if(strpos($header_data[0],'200')===false){
	//echo html_body('網址不存在');exit;
	//echo 'get_headers='.$header_data[0];
}else{
	//echo html_body('ok');exit;
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


$output_filename  = $phpself2.'.tmp';
$output_content   = $content;
file_put_contents($output_filename,$output_content);


$FFF_header =$header_data[0];
$FFF_url    =$url.'<br>'.$getinfo['url'];
$FFF_size   =$getinfo['size_download'];
foreach( $header_data as $k => $v ){
	if (stripos($v, 'Content-Length') !== false) {
		$FFF_size.='<br>'.$v;
	}
}

////
$FFF='';
$FFF.='<pre>'.$FFF_header.'</pre>';
$FFF.='<pre>'.$FFF_url.'</pre>';
$FFF.='<pre>'.$FFF_size.'</pre>';
$FFF.='<pre>'.print_r($header_data,true).'</pre>';
$html_showimage=<<<EOT
<img src="170412v0b.php?$time">
EOT;
$FFF.='<div>'.$html_showimage.'</div>';

header('Content-Type: text/html; charset=utf-8');
echo html_body($FFF);
exit;
////////////////function

function html_body($x){
	//$webm_count  =$x[5];
	//

$html_inputbox=<<<EOT
<form id='form01' enctype="multipart/form-data" action='$phpself' method="post" onsubmit="">
<input type="text" name="inputurl" size="20" value="">
<input type="submit" name="sendbtn" value="送出">
</form>
EOT;
//
$x=<<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
