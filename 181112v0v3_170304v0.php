<?php

extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
//header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//$query_string=$_SERVER['QUERY_STRING'];
//$url=$query_string;

date_default_timezone_set("UTC"); 
$time=time();
$now=date('Y-m-d H-i-s',$time);


$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
//
require_once('simple_html_dom.php');
require_once('curl_getinfo.php');
//
if(isset($_POST['inputurl'])){
	$url=$_POST['inputurl'];
}else{
	$url=$_SERVER['QUERY_STRING'];
}
//修飾
$FFF=explode('#',$url);
$url=$FFF[0];

//
$html_inputbox=<<<EOT
<form id='form01' enctype="multipart/form-data" action='$phpself' method="post" onsubmit="">
<input type="text" name="inputurl" size="20" value="">
<input type="submit" name="sendbtn" value="送出">
</form>
EOT;
//

$html_js_title=<<<EOT
document.addEventListener("DOMContentLoaded",function(e){
	//document.title=location.hostname;
});
EOT;

if( substr_count($url, "?res=")>0 ){
	echo html_all( $html_inputbox, $html_js_title);
	//ok
}else{
	//echo "?res=";
	echo html_all( $html_inputbox, $html_js_title);
	exit;}


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
$cc=0;$qlink_newest='';
$FFF='';
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
		//
		$qlink_newest=$chat_array[$k]['qlink'];
	}
	//
	foreach($v->find('div.quote') as $k2 => $v2){
		$FFF=$v2->outertext;
		$FFF=strip_tags($FFF,"<br>");//留下換行標籤//<span>
		$chat_array[$k]['quote']=$FFF;
		//
		$v2->outertext="";
	}

	foreach($v->find('a.file-thumb') as $k2 => $v2){
		//$chat_array[$k]['image0'][]=$v2->outertext;
		//$chat_array[$k]['image']
		$FFF=$v2->href;
		$FFF=''.'http:'.$FFF;
		$chat_array[$k]['image']=$FFF;
		foreach($v2->find('img') as $k3 => $v3){
			//$chat_array[$k]['image1'][]=$v3->outertext;
			$FFF=$v3->src;
			$FFF=''.'http:'.$FFF;
			$chat_array[$k]['image_t']=$FFF;

		}
		//
		$v2->outertext="";
	}
	//
	foreach($v->find('div.file-text') as $k2 => $v2){
		$chat_array[$k]['file-name']=$v2->find('a', 0)->plaintext;		
		$FFF=$v2->outertext;
		$FFF=strip_tags($FFF,"<br>");//留下換行標籤//<span>
		preg_match('/(\(.*\))/', $FFF, $matches); //, PREG_OFFSET_CAPTURE
		//$FFF=print_r($matches,true);
		$FFF=$matches[0];
		$chat_array[$k]['file-text']=$FFF;
		//
		$v2->outertext="";
	}
	//
	$chat_array[$k]['zzz_text']=$v->outertext;

}
	
//echo print_r($chat_array,true);exit;//檢查點
////////////

//用迴圈叫出資料
$cc=$cc2=$cc3=0;$htmlbody='';
$array_clear=array();
//$chat_array

//print_r($chat_array);exit;


foreach($chat_array as $k => $v){//迴圈
	unset( $chat_array[$k]['org_text'] );
	unset( $chat_array[$k]['zzz_text'] );
	//
	//if( $chat_array[$k]['name'] ) 
	//unset( $chat_array[$k]['name'] );
	//unset( $chat_array[$k]["title"] );
	//unset( $chat_array[$k]["now"] );
	//unset( $chat_array[$k]["qlink"] );
	//unset( $chat_array[$k]["quote"] );
	//unset( $chat_array[$k]["image"] );
	//unset( $chat_array[$k]["file-text"] );
	//unset( $chat_array[$k]["file-name"] );
}

//print_r($chat_array);exit;


/*
  $array_clear[$k]["name"]=$chat_array[$k]["name"];
  $array_clear[$k]["title"]=$chat_array[$k]["title"];
  $array_clear[$k]["now"]=$chat_array[$k]["now"];
  $array_clear[$k]["qlink"]=$chat_array[$k]["qlink"];
  $array_clear[$k]["quote"]=$chat_array[$k]["quote"];
  $array_clear[$k]["image"]=$chat_array[$k]["image"];
  $array_clear[$k]["image_t"]=$chat_array[$k]["image_t"];
  $array_clear[$k]["file-text"]=$chat_array[$k]["file-text"];
  $array_clear[$k]["file-name"]=$chat_array[$k]["file-name"];
  

*/
//print_r($chat_array);exit;
$FFF='';
$htmlbody='';
foreach($chat_array as $k => $v){//迴圈
		//print_r( $v );
		//echo '中';
		$FFF='';
		$FFF.='#'.$k.',';
		$FFF.=$v['name'].',';
		if( $v['title'] ){
			$FFF.=$v['title'].',';
		}
		$FFF.=$v['now'].',';
		$FFF.=$v['qlink'].',';
		//
		$FFF.='<blockquote>'.$v['quote'].'</blockquote>';
		//
		if($v["image"] == null){
			//沒事
		}else{
			if( preg_match("/\.webm$/",$v["image_t"]) ){
				$FFF.='<img src="'.$v["image_t"].'">';
				$FFF.='影片';
			}else{
				$FFF.='<img src="'.$v["image"].'">';//圖片
				$FFF.='<br clear="both">';//換行
			} 
		}//if
		//
		$FFF='<div id="block'.$k.'">'.$FFF.'</div>';
		$FFF=$FFF.',';//分隔用的逗號
		//
		//echo $FFF;
		$htmlbody.=$FFF;


}
//print_r($htmlbody);exit;//ok
$htmlbody.='<div id="END">END</div>';


$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];

$output_filename  = '181112.htm';
$output_content = html_all( $htmlbody );

file_put_contents($output_filename,$output_content);//輸出檔案

echo '<a href="'.$output_filename.'">'.$output_filename.'</a>'."<br/>\n";

$FFF="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
$FFF=substr($FFF,0,strrpos($FFF,"/")+1); //根目錄

$output_fileurl=$FFF.$output_filename;
$FFF='';
$FFF.='<a href="https://web.archive.org/save/'.$output_fileurl.'">archive.org</a>'."<br/>\n";
$FFF.='<a href="https://archive.is/?run=1&url='.$output_fileurl.'">archive.is</a>'."<br/>\n";
echo $FFF;


exit;
die("結束");

////


function html_all($body){
	//$webm_count  =$x[5];
	//<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	//<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="no-store">
$x=<<<EOT
<html>
<head>
<meta charset="UTF-8" />
<title>title</title>

<style>
</style>
<script>
</script>

<head>
<body>
$body
</body>	
</html>
EOT;
	//	
	return $x;	
}



///

?>
