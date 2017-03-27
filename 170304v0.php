<?php

extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
//header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//$query_string=$_SERVER['QUERY_STRING'];
//$url=$query_string;
$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];

//
require_once('simple_html_dom.php');
require_once('curl_getinfo.php');
//
if(isset($_POST['inputurl'])){
	$url=$_POST['inputurl'];
}else{
	$url=$_SERVER['QUERY_STRING'];
}

//
$html_inputbox=<<<EOT
<form id='form01' enctype="multipart/form-data" action='$phpself' method="post" onsubmit="">
<input type="text" name="inputurl" size="20" value="">
<input type="submit" name="sendbtn" value="送出">
</form>
EOT;
//
if( substr_count($url, "?res=")>0 ){
	//ok
}else{
	//echo "?res=";
	$FFF=$html_inputbox;
	echo html_body($FFF);
	exit;
}

//exit;

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
//echo $url_num;echo "\n";
$board_title = $html->find('title',0)->innertext;//版面標題
//echo $board_title;echo "\n";


date_default_timezone_set("Asia/Taipei");//時區設定
$time=sprintf('%s',time());//%u=零或正整數//%s=字串
$ymdhis=date('y/m/d H:i:s',$time);//輸出的檔案名稱
$board_title2=''.$board_title.'=第'.$url_num.'篇 於'.$ymdhis.'擷取';
//echo $board_title2;echo "\n";


$cc=0;
foreach( $html->find('div.quote') as $k => $v){$cc++;}
if($cc>0){
	//echo $cc;echo "\n";
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
		$FFF=$v2->outertext;
		$FFF=strip_tags($FFF,"<br><span>");//留下換行標籤
		$chat_array[$k]['quote']=$FFF;
		//
		$v2->outertext="";
	}

	foreach($v->find('a.file-thumb') as $k2 => $v2){
		//$chat_array[$k]['image0'][]=$v2->outertext;
		//$chat_array[$k]['image']
		$FFF=$v2->href;
		$FFF='http:'.$FFF;
		$chat_array[$k]['image']=$FFF;
		

		foreach($v2->find('img') as $k3 => $v3){
			//$chat_array[$k]['image1'][]=$v3->outertext;
			$FFF=$v3->src;
			$FFF='http:'.$FFF;
			$chat_array[$k]['image_t']=$FFF;

		}
		//
		$v2->outertext="";
	}

	//
	$chat_array[$k]['zzz_text']=$v->outertext;

}
	
//echo print_r($chat_array,true);exit;//檢查點
////////////

//用迴圈叫出資料
$cc=$cc2=0;$htmlbody='';
foreach($chat_array as $k => $v){//迴圈
	$cc++;
	//
	$htmlbody.= '<div id="block'.$cc.'">'."\n";
	$htmlbody.= '<div id="box1">'."\n";
	$htmlbody.= '<span class="name">'.$v['name'].'</span> ';
	if(count($v['title'])){
		$htmlbody.= '<span class="title">'.$v['title'].'</span> ';
	}
	$htmlbody.= '<span class="idno">'.$v['now'].$v['qlink'].'</span> ';
	//$htmlbody.= '<span class="qlink">'.$v['qlink'].'</span> ';
	$htmlbody.= '</div>'."\n";
	$htmlbody.= '<div id="box2">'."\n";
	$htmlbody.= '<span class="quote"><blockquote>'.$v['quote'].'</blockquote></span> '."\n";
	if(count($v['image'])){
		$cc2++;//計算圖片數量
		//
		$FFF=''.$v['image'];
		$htmlbody.= '<span class="image"><img class="zoom" src="'.$FFF.'"/></span>圖';
	}

	$htmlbody.= '</div>'."\n";



	$htmlbody.= '</div>'."\n";
}


//echo print_r($htmlbody,true);exit;//檢查點

$chat_array[0]=$htmlbody;
$chat_array[1]="國";
$chat_array[2]=$url_num;
$chat_array[3]=$board_title;
$chat_array[4]=$board_title2;

//////////
$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];

$output_filename  = $phpself2.'.htm';
$output_content   = poi($chat_array);
file_put_contents($output_filename,$output_content);

$FFF="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
$FFF=substr($FFF,0,strrpos($FFF,"/")+1); //根目錄
$output_fileurl=$FFF.$output_filename;

///
header('Content-Type: text/html; charset=utf-8');
$FFF=''.$html_inputbox;
$FFF.=$url."<br/>\n";
$FFF.='<a href="'.$output_fileurl.'">'.$output_fileurl.'</a>'."<br/>\n";
$FFF.='<a href="https://web.archive.org/save/'.$output_fileurl.'">archive.org</a>'."<br/>\n";
$FFF.='<a href="https://archive.is/?run=1&url='.$output_fileurl.'?'.$url.'">archive.is</a>'."<br/>\n";
echo html_body($FFF);

exit;
////////////////

function html_body($x){
	//
$x=<<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
<body>
$x
</body>	
</html>
EOT;
	//	
	return $x;
}
function poi($x){
	$htmlbody    =$x[0];
	$board_title2=$x[4];
//
$FFF=<<<EOT
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
<head>

<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
$htmlbody
</body>

<html>

EOT;
	
	//
	$x=$FFF;
	return $x;
}

	

?>
