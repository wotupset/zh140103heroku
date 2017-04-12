<?php
$filename='170412v0.tmp';

//$ext = pathinfo($filename);//, PATHINFO_EXTENSION
//print_r($ext);
$type= mime_content_type($filename);
if(strpos($header_data[0],'200')===false){
	//失敗
}else{
	//成功
	//header('Content-Type: application/json; charset=utf-8');
	header('Content-Type:'.$type);
	$FFF= file_get_contents($filename);
	echo strlen($FFF);
}


?>