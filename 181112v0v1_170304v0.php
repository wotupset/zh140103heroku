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
if( substr_count($url, "?res=")>0 ){
	$FFF=$html_inputbox;
	echo html_all($FFF);
	//ok
}else{
	//echo "?res=";
	$FFF=$html_inputbox;
	echo html_all($FFF);
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
	$FFF=$chat_array[$k]["quote"];
	//$FFF=urlencode($FFF);
	//$FFF=htmlentities($FFF,ENT_QUOTES);
	$FFF=rawurlencode($FFF);//urlencode
	$FFF=base64_encode($FFF);
	$chat_array[$k]["quote"]=$FFF;
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
//print_r($array_clear);exit;

$FFF = $chat_array;
$FFF = json_encode( $FFF );
//$FFF = json_encode( $FFF );
$myJSON = $FFF;


//print_r($myJSON);exit;
$FFF=json_decode($myJSON);
//print_r($FFF);exit;
//$FFF=json_last_error();
//print_r($FFF);exit;

/*
JSON_HEX_APOS (integer)
所有的 ' 转换成 \u0027。 自 PHP 5.3.0 起生效。
JSON_HEX_QUOT (integer)
所有的 " 转换成 \u0022。 自 PHP 5.3.0 起生效。
JSON_UNESCAPED_UNICODE (integer)
以字面编码多字节 Unicode 字符（默认是编码成 \uXXXX）。 自 PHP 5.4.0 起生效。
*/
//print_r($myJSON);exit;

$js=html_js($myJSON);
//print_r($js);exit;

$css=html_css();
$body=html_body();
$output_content = html_all($body,$js,$css);

//print_r($output_content);exit;





$FFF=pathinfo($_SERVER["SCRIPT_FILENAME"]);
$phpself  = $FFF['basename'];
$phpself2 = $FFF['filename'];

$output_filename  = '181112.htm';

file_put_contents($output_filename,$output_content);
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
//vertical-align:text-top;
function html_body($x=''){
$x=<<<EOT
<div id="ddd">ddd</div>
EOT;
	//
	return $x;	
}
function html_css($x=''){
	//
$x=<<<EOT
.image_orig{
height:50px;
width":50px;
vertical-align:text-top;
}
.image_thumb{
}
EOT;
	//
	return $x;	
}

function html_js($json){
	$time=$GLOBALS['time'];
	$now=$GLOBALS['now'];
	$time_13=$time*1000;
	//$str_json=base64_encode($str_json);
	//global $a, $b;
	//
$x=<<<EOT
window.php_timestamp='$time_13';
window.php_date='$now';

var date = (new Date());
window.js_timestamp = (new Date()).getTime(); //Date().now();//Math.floor(  / 1000);
//console.log( js_timestamp );

var dateUTCvalues = [
   date.getUTCFullYear(),
   date.getUTCMonth()+1,
   date.getUTCDate(),
   date.getUTCHours(),
   date.getUTCMinutes(),
   date.getUTCSeconds(),
];
window.js_date = dateUTCvalues.join(",");
//console.log( window.js_date );

//console.log( window.php_timestamp );
//console.log( window.js_timestamp );


/*
//es6 string template
window.str_json=`
`;
*/
window.str_json='$json';








///
document.addEventListener("DOMContentLoaded", function(event) { 
	console.log( 'DOMContentLoaded' );
	//document.write('DOMContentLoaded');
	
	document.getElementById('ddd').insertAdjacentHTML('beforeend', '這是javascript');

	var jsElm = document.createElement("script");
	jsElm.type = "application/javascript";
	//jsElm.src = 'https://code.jquery.com/jquery-3.3.1.min.js';
	jsElm.src = './jquery-3.3.1.slim.min.js';
	jsElm.onload = function(e){
		console.log('jsElm.onload');
		//console.log(e);
		document.getElementById('ddd').insertAdjacentHTML('beforeend','jquery讀取成功');
		//document.getElementsByTagName("body")[0]
		jquery_start();
	};
	//document.body.appendChild(jsElm);
	document.head.appendChild(jsElm);
	//document.head.insertAdjacentElement('beforeend', jsElm);
});
///
/*
beforebegin
afterbegin
beforeend
afterend
*/

function jquery_start(){

try{
  $(document).ready(function() {
  	console.log('document.ready');
	$("#ddd").append( 'document.ready' );//html

	//
    time = new Date();
    gg=[];
	gg["poi"]='123';
	gg.ypa='456';
	//
	$.gginin=gg;
	//console.log( $.gginin );
	//
    poi_start();
	//console.log( 'jq='+$.now() );
	
  });
}catch(err){
  	console.log( ''+err.message );
}finally{
    console.log( 'try-catch-finally' );
}

}
///
function poi_start(){
/*
btoa('Hello, world'); // "SGVsbG8sIHdvcmxk"
atob('SGVsbG8sIHdvcmxk'); // "Hello, world"
*/
	//window.json=atob(window.json);
	//console.log( window.str_json );
	
    try {
		var ary_json=$.parseJSON( window.str_json );
    } catch(e) {
		console.log( e );
		$("#ddd").html( '分析json失敗' );
		throw "分析json失敗";
    }

	//console.log( ary_json.length );
	$("#ddd").html( '讀取'+ary_json.length );
	array_loop(ary_json);
}


///
function array_loop(ary_json){
	$("#ddd").after("產生文章");
	var cc=0;
	var htmlbody=[];
	cc=0;
	for(var i=0;i<ary_json.length;i++){
		//console.log( ary_json[i] );
		//
		cc++;
		//htmlbody+=""+i;
	}
	//console.log( htmlbody );
	cc=0;
	var FFF='';
	var tmp='';
	ary_json.forEach(function(v,k,a){
		//console.log( k,v );
		FFF='';
		//if( v["title"].toString().length == 0 ){v["title"]='';}
		FFF+='#'+k+',';
		FFF+=v["name"]+',';
		if( v["title"] ){
			FFF+=v["title"]+',';
		}
		FFF+=v["now"]+',';
		FFF+=v["qlink"]+',';
		//
		tmp=v["quote"];
		tmp=atob(tmp);
		tmp=decodeURIComponent(tmp);
		v["quote"]=tmp;
		FFF+='<blockquote>'+v["quote"]+'</blockquote>';
		//
		if(v["image"] == null){
			//
		}else{
			if( v["image"].match(/\.webm$/)){
				FFF+='<img class="image_thumb" src="'+v["image_t"]+'">';
				FFF+='影片';
			}else{
				FFF+='<a href="'+v["image"]+'"><img class="image_thumb" src="'+v["image_t"]+'">'+v['file-name']+' '+v['file-text']+'</a>';
				FFF+='<img class="image_orig" src2="'+v["image"]+'">';
			} 
		}
		FFF+='<br clear="both">';
		
		    

		FFF='<div id="block'+k+'">'+FFF+'</div>';
		htmlbody[k]=FFF;
	});
	//console.log( htmlbody );
	FFF=htmlbody.join(",");
	$("#ddd").html( FFF );
	//
	
	time_check();
	//$("body").("讀取大圖");
	//prepend
	//$("#ddd").after("讀取大圖");
}//ff

/*
before
prepend
append
after
*/

///
function test01(){
		$(".image_orig").each(function(k,v){
			console.log( $(this) );
		});
}

function time_check(){
	$("#ddd").after("檢查時間");
	var FFF='';
	FFF=window.js_timestamp - window.php_timestamp;
	$("#ddd").after(""+FFF);
	if( FFF ){
		//console.log("y");
		//10分鐘後
		if(FFF > 600*1000){ 
			//不顯示
		}else{
			//顯示
			if( $(".image_orig").length >0 ){
				//有圖
				$("#ddd").after("有圖");
				console.log("有圖");
				poi10(0);
			}
			$(".image_orig").css({
				"height":"100px",
				"width":"100px",
				"vertical-align":"text-top",
			});
			$(".image_thumb").css({
				"vertical-align":"text-top",
			});
		}
	}else{
		$("#ddd").after("???");
		//console.log("n");
	}
}//f

function poi10(count){
	var cc=count;
	$("#ddd").after(cc);
	var FFF='';
	FFF=$(".image_orig");
	console.log( cc , $(".image_orig").length );
	if( cc < FFF.length ){
		//console.log( FFF );
		FFF=FFF[cc];
		//console.log( $(FFF) );
		$.gginin.time_checkpoint=(new Date()).getTime();
		$(FFF).attr("src", $(FFF).attr("src2") );
		$(FFF).removeAttr("src2");
		$(FFF).on('load', function(){
			$(FFF).after('成功'+cc);
			$(FFF).after('耗時'+ ((new Date()).getTime() - $.gginin.time_checkpoint) +'毫秒');
			poi10(cc+1);
		});
		$(FFF).on('error', function(){
			$(FFF).after('失敗');
			$(FFF).after('耗時'+ ((new Date()).getTime() - $.gginin.time_checkpoint) +'毫秒');
			poi10(cc+1);
		});
	}else{
		$("#ddd").after("結束");
		//$("#ddd").before(""+navigator.userAgent);
		console.log("結束");
	}
}//ff

///
//float:left

EOT;
	//
	return $x;	
}
function html_all($body,$js='',$css=''){
	//$webm_count  =$x[5];
	//<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	//<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="no-store">
$x=<<<EOT
<html>
<head>
<meta charset="UTF-8" />
<title>title</title>

<style>
$css
</style>
<script id="jq_tmp" src2="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>
$js
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
