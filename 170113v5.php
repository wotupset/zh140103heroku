<?php
$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself=$FFF['basename'];
$phpself2=$FFF['filename'];
/*
Array
(
    [dirname] => /00/src
    [basename] => 1406616621815.jpg
    [extension] => jpg
    [filename] => 1406616621815
)
*/


//
require_once('170113v4b.php');
if( $auth != "國" ){exit;}
$output_path=output_html($htmlbody);//回傳檔案位置
//echo $output_path;
//
$curlpost=curlpost_html($output_path);
print_r($curlpost);//檢查點


function curlpost_html($x){
	//$output_path=$GLOBALS['output_path'];
	$output_path=$x;
	//
	if( file_exists( realpath($output_path) ) ){
	 echo "檔案存在";
	 echo filesize(realpath($output_path));
	}else{
	 echo "檔案不存在";
	}
if(class_exists('CurlFile')) {
	//5.6+
	$myvars['upfile']= new CurlFile(realpath($output_path));//
}else{
	//old version(php)
	$myvars['upfile']= '@'.realpath($output_path);
}
	//$upf='@'.realpath($tmp);//filename=this.htm
	//$upf='@'.$output_path;//filename=this.htm
	//$md5=md5_file($tmp);
	//$myvars['upfile']= '@'.realpath($output_path);
	//$myvars=array('file' => '@' . realpath('example.txt'));
	$myvars['pass']= 'xopowo';
	//print_r($myvars);//檢查點
	//
	$url='http://zh161005.comli.com/htm/151225-2244put.php';
	//$useragent='Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36';
	$ch = curl_init();//初始化
	if(!$ch){die('[x]curl初始化失敗');}

	curl_setopt($ch, CURLOPT_URL,            $url);//網址
	curl_setopt($ch, CURLOPT_POST,           1);//post模式
	curl_setopt($ch, CURLOPT_POSTFIELDS,     $myvars);//參數
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);//跟随重定向页面//safe_mode = Off 
	curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
	//curl_setopt($ch, CURLOPT_SAFE_UPLOAD,    0);
	//curl_setopt($ch, CURLOPT_USERAGENT,      $useragent);
	
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

function output_html($x){
	$time=$GLOBALS['time'];
	$ym=date('ym',$time);//輸出的檔案名稱
	$board_title2=$GLOBALS['board_title2'];
	$url_num=$GLOBALS['url_num'];
	$url_num2=$GLOBALS['url_num2'];
	$phpself2=$GLOBALS['phpself2'];
	//
	$htmlbody=$x;
	//
$htmlbody=<<<EOT
<html>
<head>
<title>$board_title2</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<STYLE>
img.zoom {
height:auto; width:auto;
min-width:20px; min-height:20px;
max-width:250px; max-height:250px;
border:1px solid blue;
padding-right:5px;
background-color:#00ffff;
}
span.image {
width:250px; 
height:250px;
border:1px solid #000;
display: inline-block;
}
span.name {
display: inline-block;
white-space:nowrap;
font-weight: bold;
color: #117743;
min-width:10px;
max-width:100px;
overflow:hidden;
}
span.title {
display: inline-block;
white-space:nowrap;
font-weight: bold;
color: #CC1105;
min-width:10px;
max-width:100px;
overflow:hidden;
}
span.idno {
display: inline-block;
white-space:nowrap;
min-width:10px;
max-width:500px;
overflow:hidden;
}
</STYLE>

</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
$htmlbody
</body>
</html>;
EOT;

	//
	//$tmp=$path.'dbp_'.$time.'_'.$url_num2.'n'.$url_num.'_'.rdm_str().'.htm';
	$tmp=$phpself2.'.htm';
	if(is_file($tmp)){
		unlink($tmp);
		if(is_file($tmp)){die("刪除檔案失敗");}
	}//舊檔案有存在就移除
	file_put_contents($tmp, $htmlbody);
	if(!is_file($tmp)){die("建立檔案失敗");}
	//
	$x=$tmp;
	return $x;
}





?>
