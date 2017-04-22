<?php
date_default_timezone_set("Asia/Taipei");//時區設定
$time  =time();
$time2 =array_sum( explode( ' ' , microtime() ) );

$filename='170412v0.tmp';

$info_array=getimagesize($filename);

//if(preg_match('/image/',$filetype)){
if($info_array[2] >0){
	$filetype=$info_array['mime'];
	$ext=imagetype($info_array['2']);//副檔名
}else{
	die('!image');
}



//$ext = pathinfo($filename);//, PATHINFO_EXTENSION
//print_r($ext);
$type= mime_content_type($filename);
//echo 'type='.$type;exit;
if(strpos($type,'image')===false){
	//失敗
	echo 'exit';exit;
}else{
	//成功
	//header('Content-Type: application/json; charset=utf-8');
	header('Content-Type:'.$type);
	$FFF=base64_encode($time).'.'.$ext;
	header('Content-Disposition: filename="'.$FFF.'"');//
	$FFF= file_get_contents($filename);
	//echo strlen($FFF);
	echo $FFF;
}

exit;
////
function imagetype($x){
	$tmp='';
	switch($x){
		case 1:
			$tmp='gif';
		break;
		case 2:
			$tmp='jpg';
		break;
		case 3:
			$tmp='png';
		break;
		default:
			$tmp='';
		break;
	}
	//
	$x=$tmp;
	return $x;
}

?>
