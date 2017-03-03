<?php
header('Content-Type: application/json; charset=utf-8');
$query_string=$_SERVER['QUERY_STRING'];
$url=$query_string;
//
require_once('simple_html_dom.php');
require_once('curl_getinfo.php');
//

if( substr_count($url, "?res=")>0 ){
	//ok
}else{
	echo "?res=";exit;
}

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

//
$html = str_get_html($content) or die('沒有收到資料');//simple_html_dom自訂函式
$chat_array='';
$chat_array = $html->outertext;
//echo print_r($chat_array,true);exit;//檢查點
//

$url_num='0000';
$pattern="%\?res=([0-9]+)%";
if(preg_match($pattern, $url, $matches_url)){
	//echo $matches_url[1];
	$url_num=$matches_url[1];
}
echo $url_num;
echo "\n";
$board_title = $html->find('title',0)->innertext;//版面標題
echo $board_title;
echo "\n";

date_default_timezone_set("Asia/Taipei");//時區設定
$time=sprintf('%s',time());//%u=零或正整數//%s=字串
$ymdhis=date('y/m/d H:i:s',$time);//輸出的檔案名稱
$board_title2=''.$board_title.'=第'.$url_num.'篇 於'.$ymdhis.'擷取';
echo $board_title2;
echo "\n";


$cc=0;
foreach( $html->find('div.quote') as $k => $v){$cc++;}
if($cc>0){
	echo $cc;
	echo "\n";
}else{
	die('[x]blockquote');
}
////////////
//批次找留言
$chat_array=array();
$cc=0;
foreach($html->find('div.post') as $k => $v){
	//$vv=$v->parent;
	$chat_array[$k]['org_text']=$v->outertext;
	//
	foreach($v->find('span.name') as $k2 => $v2){
		$chat_array[$k]['name'] =$v2->plaintext;
		$v2->outertext="";
	}
	foreach($v->find('span.title') as $k2 => $v2){
		$chat_array[$k]['title'] =$v2->plaintext;
		$v2->outertext="";
	}

	foreach($v->find('span.now') as $k2 => $v2){
		$chat_array[$k]['now'] =$v2->plaintext;
		$v2->outertext="";
	}
	foreach($v->find('span.id') as $k2 => $v2){
		$chat_array[$k]['id'] =$v2->plaintext;
		$v2->outertext="";
	}
	foreach($v->find('span.qlink') as $k2 => $v2){
		$chat_array[$k]['qlink'] =$v2->plaintext;
		$v2->outertext="";
	}
	//
	foreach($v->find('div.quote') as $k2 => $v2){
		$chat_array[$k]['quote'] =$v2->innertext;
		$v2->outertext="";
	}
	foreach($v->find('img.img') as $k2 => $v2){
		//$chat_array[$k]['image0'] =$v2->parent->outertext;
		$FFF=$v2->parent->outertext;
		$chat_array[$k]['image0']=$FFF;
		foreach($FFF->find('img') as $k3 => $v3){
			$chat_array[$k]['image_t']=$v3->src;
		}
		//
		$v2->outertext="";
	}

	//
	$chat_array[$k]['zzz_text']=$v->outertext;

}
	
echo print_r($chat_array,true);exit;//檢查點
////////////

exit;
?>

