<?php

//print_r( $_SERVER['QUERY_STRING'] );
$url=$_GET['inputurl'];
//print_r( $url );

require_once('curl_getinfo.php');

if(1){
  $x=curl_FFF($url);
  //echo print_r($x,true);exit;
  $getdata =$x_0 =$x[0];//資料
  $getinfo =$x_1 =$x[1];//訊息
  $geterror=$x_2 =$x[2];//錯誤
  //simple_html_dom
  if(!$getdata){echo print_r($getinfo,true);exit;}
  //echo print_r($getinfo,true);//檢查點
  $content=$getdata;
}

//print_r( $getinfo );
//header('content-Type: text/plain; charset=utf-8 '); //語言強制
//header("Access-Control-Allow-Origin: *");
//echo $content;
//echo mime_content_type( $content );
//$file = tmpfile();

$file = tempnam(sys_get_temp_dir(), 'FOO');
//print_r( $file );
file_put_contents($file,$content);
$poi_filesize=filesize($file);
//echo $poi_filesize;





//file_get_contents() 
//echo mime_content_type($file);

//$finfo=new finfo(FILEINFO_MIME_TYPE);
//$mime=$finfo->file($file);


$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file );

//echo $mime;

//exit;
header('content-Type:'.$mime); //語言強制
header('Content-Length:'.$poi_filesize);
header('Accept-Ranges: bytes');
header("Access-Control-Allow-Origin: *");//cross domin

echo file_get_contents($file);
	
?>