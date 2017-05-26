<?php
/*

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
require_once('simple_html_dom.php');
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
$html = str_get_html($content) or die('沒有收到資料');//simple_html_dom自訂函式
$FFF='';
$FFF_array=array();
foreach( $html->find('img') as $k => $v){
	$FFF_array[]=$v->src;
	$FFF.=$v->src;
	$FFF.='<br/>'."\n";
}
$html_show=$FFF;
//print_r($FFF_array);exit;
//echo $FFF;echo "\n";
//echo $status;echo "\n";

///

$FFF_header =$header_data[0];
foreach( $header_data as $k => $v ){
	$FFF=explode(":",$v);
	//print_r($FFF);
	//print_r(preg_match('/^Content-Type$/',$FFF[0],$matches));
	if (preg_match('/^Content-Type$/',$FFF[0],$matches)) {
		$FFF_header.='<br/>'.$v;
	}
}


////

$FFF_size   =$getinfo['size_download'];
foreach( $header_data as $k => $v ){
	$FFF=explode(":",$v);
	//print_r($FFF);
	if (stripos($FFF[0], 'Content-Length') !== false) {
		$FFF_size.='<br>'.$v;
	}
}
$FFF_url    =$url.'<br>'.$getinfo['url'];


////
$FFF='';
$FFF.='<pre>'.$FFF_header.'</pre>';
$FFF.='<pre>'.$FFF_url.'</pre>';
$FFF.='<pre>'.$FFF_size.'</pre>';
$FFF.='<pre>'.print_r($header_data,true).'</pre>';


$FFF.='<div>'.$html_show.'</div>';

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