<?php

extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
//header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//$query_string=$_SERVER['QUERY_STRING'];
//$url=$query_string;

date_default_timezone_set("UTC"); 
$time=time();
$now=date('Y-m-d H:i:s',$time);
$now2=date('Y-m-d',$time).'T'.date('H:i:s',$time).'+00:00';//
$now2b64=base64_encode($now2);






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

$url_hash=substr( hash('md5',$url) , 0, 6);


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
//echo "?".$board_title;echo "\n";

try{
	if( strlen($board_title) > 0 ){
		//
	}else{
		die('title解析失敗');
	}
}catch(Exception $e){
	print_r($e);
	die('title解析失敗2');
}



$ymdhis=date('y/m/d H:i:s',$time);//輸出的檔案名稱
$board_title2=''.$board_title.'=第'.$url_num.'篇 於'.$ymdhis.'擷取';
//echo $board_title2;echo "\n";


$cc=0;
foreach( $html->find('div.quote') as $k => $v){$cc++;}
if($cc>0){
	//echo $cc;echo "\n";
}else{
	die('[x]blockquote='.$cc);
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
.poi{
pointer-events: none;
}
EOT;
	//
	return $x;	
}

function html_js($json){
	$time=$GLOBALS['time'];
	$now=$GLOBALS['now'];
	$now2=$GLOBALS['now2'];
	$now2b64=$GLOBALS['now2b64'];
	
	$url_hash=$GLOBALS['url_hash'];
	//echo "ss".$url_hash_2;
	//
	$time_13=$time*1000;
	
	//$str_json=base64_encode($str_json);
	//global $a, $b;
	//
$x=<<<EOT
window.php_timestamp='$time_13';
window.php_date='$now';
window.php_date2='$now2';
window.php_date2b64='$now2b64';
console.log( 'b64還原',atob(window.php_date2b64) );



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
console.log( window.js_date );

console.log( window.php_timestamp );
console.log( window.js_timestamp );


/*
//es6 string template
window.str_json=`
`;
*/
window.str_json='$json';







window.addEventListener('ajaxReadyStateChange', function (e) {
	console.log( 'ajaxReadyStateChange',e );
});
window.addEventListener('ajaxAbort', function (e) {
	
});

///
document.addEventListener("readystatechange", function(e){
	//console.log( e,this,this.readyState );
});

document.addEventListener("DOMContentLoaded", function(e){
	console.log( 'DOMContentLoaded' );
	var id01=document.getElementById('ddd');
	id01.innerHTML="";
	//id01.insertAdjacentHTML('beforeend', '網頁DOM載入完成,');
	id01.insertAdjacentHTML('beforebegin', '網頁DOM載入完成,');
	
	//document.title=location.hostname;
	document.title=window.php_date;

	//
	if(typeof(Blob)!='undefined'){}
	if( window.URL !== undefined ){
	//if( 1==0 ){
		var id01=document.getElementById('ddd');
		id01.insertAdjacentHTML('beforebegin',"支援window.URL,");
		console.log( 'window.URL' );
		ver02a_new();
		
	}else{
		var id01=document.getElementById('ddd');
		id01.insertAdjacentHTML('beforebegin',"不支援window.URL,");
		ver02a_old();
		
	}
});


function test_TouchEvent(){
	//測試是否支援觸控
	//var id01=document.getElementById('ddd');
	//var tmp='';
	var id01=document.getElementById('ddd');
	id01.insertAdjacentHTML('beforebegin',"觸控");
	try{
		document.createEvent('TouchEvent');
		console.log('有觸控');
		//tmp='有觸控';
		$.gginin.var190114.TouchEvent=1;
		var id01=document.getElementById('ddd');
		id01.insertAdjacentHTML('beforebegin',"o,");
	}catch(err){
		console.log('無觸控');
		//tmp='無觸控';
		$.gginin.var190114.TouchEvent=0;
		var id01=document.getElementById('ddd');
		id01.insertAdjacentHTML('beforebegin',"x,");
	}finally{
		//console.log('觸控事件');
	}	
	//id01.insertAdjacentHTML('beforebegin',tmp);

}
function ver02a_new(){
	console.log( 'ver02a_new' );
	//使用chrome版本70所支援的語法
	var xhr=new XMLHttpRequest();
	//console.log( 'xhr0',xhr.readyState );
	//0 请求未初始化 客戶端已被建立，但 open() 方法尚未被呼叫。
	
	xhr.ypa='xopowo';
	//xhr.onloadstart=function(){};//要加在open()之前
	xhr.addEventListener("loadstart", function(e){
		console.log("loadstart",e.timeStamp);
	}, true);

	
	xhr.open("GET",'./jquery-3.3.1.slim.min.js');
	//console.log( 'xhr1',xhr.readyState );
	//1 服务器连接已建立 open() 方法已被呼叫。
	/*
	2	HEADERS_RECEIVED	send() 方法已被呼叫，而且可取得 header 與狀態。
	3	LOADING	回應資料下載中，此時 responseText 會擁有部分資料。
	4	DONE	完成下載操作。
	*/
	
	xhr.overrideMimeType("application/javascript");
	xhr.send(null);


	
	//xhr.onreadystatechange = function(e){};
	xhr.addEventListener("readystatechange", function(e){
		if(xhr.readyState === 4 && xhr.status === 200) {
			//console.log(xhr.responseText);
			console.log("xhr成功new",e.timeStamp);
			var id01=document.getElementById('ddd');
			id01.insertAdjacentHTML('beforebegin',"xhr成功new,");
		}
	}, false);
	

	//xhr.onprogress = function(event) {};
	xhr.addEventListener("progress", function(e){
		console.log("progress",e.timeStamp);
		//console.log(e);
		console.log( e.lengthComputable,e.loaded,e.total);
	}, false);
	
	
	xhr.addEventListener("load", function(e){
		console.log("load",e.timeStamp);
		//console.log("產生blobUrl");
		var blob = new Blob([xhr.responseText],{type:"text/javascript"});
		var blobUrl = URL.createObjectURL(blob);
		//
		console.log( blobUrl );
		
		var jsElm = document.createElement("script");
		jsElm.setAttribute("type","application/javascript");
		jsElm.setAttribute("src",blobUrl);
		document.getElementsByTagName("head")[0].appendChild(jsElm);
		//
		var xx=function(e){
			console.log('jsElm.onload');
			var id01=document.getElementById('ddd');
			id01.insertAdjacentHTML('beforebegin','jquery檔案載入成功new,');
			jquery_start();
		};
		jsElm.addEventListener("load", function(){
			//xx();
		});
		jsElm.onload=xx;

	}, false);
	xhr.addEventListener("loadend", function(e){
		console.log("loadend",e.timeStamp);
		console.log(xhr.responseURL);
		//console.log(xhr);
		var myHeader = xhr.getAllResponseHeaders();
		console.log("文件檔頭",myHeader);
		
		
	}, false);
	//超時設定
	xhr.timeout = 30*1000;//默認0毫秒，表示沒有時間限制
	xhr.addEventListener("timeout", function(e){
		console.log("timeout");
	}, false);
	//很少用到
	xhr.addEventListener("abort", function(e){
		console.log("abort");
	}, false);
	xhr.addEventListener("error", function(e){
		console.log("error");
	}, false);


}
///
function ver02a_old(){
	console.log( 'ver02a_old' );
	//使用chrome版本50所支援的語法
	var jsElm = document.createElement("script");
	jsElm.type = "application/javascript";
	jsElm.src = './jquery-3.3.1.slim.min.js';
	document.head.appendChild(jsElm);
	jsElm.onload=function(e){
		var id01=document.getElementById('ddd');
		id01.insertAdjacentHTML('beforebegin','jquery載入成功old,');
		console.log('jquery載入成功old,');
		jquery_start();
	};
}
///





///
/*
beforebegin
afterbegin
beforeend
afterend
*/

function jquery_start(){
	console.log( 'jquery_start' );

	try{
	  $(document).ready(function() {
		//$("#ddd").append( 'jquery執行區塊,' );//html
		$("#ddd").before( 'jquery執行區塊,' );//html
		//
		time = Date.now();//new Date()//timestamp
		gg=[];
		gg.time=time
		gg["ypa"]='xopowo';
		//
		$.gginin=gg;
		$.gginin.count=0;
		$.gginin.cc181214=0;
		$.gginin.var181214=[];
		$.gginin.var181214.t2cc2_a=0;
		$.gginin.var181214.t2cc2_b=0;
		$.gginin.var181219=[];
		$.gginin.var181219.base64_image="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGP6zwAAAgcBApocMXEAAAAASUVORK5CYII=";
		$.gginin.var190114=[];
		$.gginin.var190114.TouchEvent=0;
		$.gginin.var190209=[];
		$.gginin.var190209.count=0;
		$.gginin.var190211=[];
		$.gginin.var190211.video_start_time=Date.now();//
		
		//console.log( $.gginin );
		//
		test_TouchEvent();//結果紀錄於$.gginin.var190114.TouchEvent
		poi_start();
		//console.log( 'jq='+$.now() );
		
	  });
	}catch(err){
		console.log( err );
	}finally{
		//
	}

}//ff
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
		throw "分析json失敗";//throw錯誤後停止
    }

	//console.log( ary_json.length );
	$("#ddd").before( '讀取json'+ary_json.length+',' );
	array_loop(ary_json);
}


///
function array_loop(ary_json){
	$("#ddd").after(",產生文章");
	var cc=0;
	var htmlbody=[];
	cc=0;
	for(var i=0;i<ary_json.length;i++){
		//console.log( ary_json[i] );
		//
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
		//
		FFF+='<blockquote>'+v["quote"]+'</blockquote>';
		//
		if(v["image_t"] == null){ //有伴隨縮圖就附加處理
			//
		}else{
			cc++;//計算縮圖數量//
			//
			//縮圖顯示連結
			FFF+='<a href="'+v["image"]+'"><img class="image_thumb" src="'+v["image_t"]+'">'+v['file-name']+' '+v['file-text']+'</a>';
			//
			if( v["image"].match(/\.webm$/)){
				//這是影片
				FFF+='影';
				//判斷是否支援觸控 //有觸控=移動裝置 //不顯示全部影片
				if( $.gginin.var190114.TouchEvent >0 && 1==1){
					//console.log('有觸控');
					FFF+='[點連結觀看]';
				}else{
					//console.log('無觸控');
					FFF+='<img class="image_webm" src="'+v["image"]+'" >';
					FFF+='<button type="button" class="btn_01">看影片</button>';
					FFF+='<video class="vid_01" controls style="vertical-align: text-top;"></video>'; 
					
				}
				//顯示影片 //太花流量 使用preload="meta"
				//FFF+='<video id="video'+k+'" class="video_orig" src="../" src2="'+v["image"]+'"  muted controls  preload="meta">autoplay</video>';

			}else{
				//這是圖片
				FFF+='圖';
				//放置原圖的區塊 等待js執行開啟圖片
				FFF+='<img id="image'+k+'" class="image_orig" src="'+$.gginin.var181219.base64_image+'" src2="'+v["image"]+'">';
			} 
		}
		FFF+='<br clear="both">';
		
		    

		FFF='<div id="block'+k+'">'+FFF+'</div>';
		FFF=FFF+',';//分隔用的逗號
		htmlbody[k]=FFF;
	});//forEach
	
	//console.log( htmlbody );
	//FFF=htmlbody.join(",");//分隔用的逗號
	FFF=htmlbody.join("");
	FFF=FFF+'<div id="END">END</div>';
	$("#ddd").html( FFF );
	//
	css_setting();
	if(cc>0 ){//有縮圖
		//有縮圖
		//console.log('有縮圖');

		
		time_check();//檢查時間
		poi190928();//處理video
	}
	//$("body").("讀取大圖");
	//prepend
	//$("#ddd").after("讀取大圖");
}//ff

function poi190928(){
	console.log( 'poi190928' );
	//console.log( aa );
	var aa = $(".btn_01");
	aa.click(function(e){
		var bb= $(this).prev().attr('src');
		console.log( bb );
		var cc=$(this).next().attr('src',bb);
		console.log( cc );
	});
	var bb = $(".vid_01");
	bb.css({
        "width": "auto",
        "height": "auto",
        "max-width": "480px",
        "max-height": "480px",
	});
}

function css_setting(){
	$(".image_orig").css({
		"height":"100px",
		"width":"100px",
		"vertical-align":"text-top",
	});
	$(".video_orig").css({
		"height":"100px",
		"width":"300px",
		"vertical-align":"text-top",
	});
	$(".image_thumb").css({
		"vertical-align":"text-top",
	});

}

/*
before
prepend
append
after
*/

///

function test02(){
	$.gginin.cc181214= $.gginin.cc181214 +1;
	var t=setTimeout(function(){
		var t2=$(".image_orig").length;
		var t2_cc1=0;
		var t2_cc2=0;
		for(var i=0;i<t2;i++){
			var t3=$(".image_orig")[i]
			//console.log( $(t3).attr('src') );
			//console.log( $(t3).width(),$(t3).height() );
			//console.log( $(t3).prop('naturalWidth'),$(t3).prop('naturalHeight') );
			var t4a='';
			if( $(t3).attr('src') != $.gginin.var181219.base64_image ){
				t4a='y';
			}else{
				t4a='n';
				t2_cc2 = t2_cc2 + 1 ;
			}
			var t4b='';
			if( $(t3).prop('naturalWidth') > 0 ){
				t4b='y';
			}else{
				t4b='n';
			}
			//console.log( $.gginin.cc181214,t4a,t4b,$(t3).attr('src') );
			if( t4a=='y' && t4b=='n' ){ //src改變 但沒抓到wh
				t2_cc1 = t2_cc1 + 1;
			}
		}
		console.log( $.gginin.cc181214 , "讀取中=" + t2_cc1 , "未讀=" + t2_cc2 );
		var FFF_a='';
		var FFF_b='';
		FFF_a=$.gginin.var181214.t2cc2_a;
		FFF_b=$.gginin.var181214.t2cc2_b;
		if(FFF_a == t2_cc2 ){ //未讀數量沒改變
			FFF_b = FFF_b + 1 ; //記錄+1
		}else{//未讀數量改變
			FFF_b = 0 ; //記錄清空
			FFF_a = t2_cc2; //更新
		}
		console.log( "FFF=",FFF_a,FFF_b );
		$.gginin.var181214.t2cc2_a=FFF_a;
		$.gginin.var181214.t2cc2_b=FFF_b;

		
		if(FFF_b > 3){ //未讀數量沒改變 超過3次
			console.log( '未讀數量沒改變 超過3次' );
			if(t2_cc2 == 0){
				console.log( '未讀數量=0 停止' );
				//停止
			}else{
				console.log( '未讀數量!=0 繼續' );
				console.log( '改背景顏色' );
				$(".image_orig").filter(function(index) {
					if( $(this).attr('src') == $.gginin.var181219.base64_image ){
						return (1==1);
					}else{
						return (1==0);
					}
				}).css('background-color', 'red');
				//迴圈
				if(FFF_b>30){
					console.log( '超過30秒沒讀取 停止' );
					//stop
				}else{
					test02();
				}
			}
		}else{
			test02();
		}
		//$.gginin.var181214.t2cc2_a=0;
		//$.gginin.var181214.t2cc2_b=0;

		//
	},1000);
}
function time_check(){
	$("#ddd").after(",檢查時間");
	//

	
	//
	var FFF='';
	FFF=window.js_timestamp - window.php_timestamp;
	$("#ddd").after(","+FFF);
	if( FFF ){
		//console.log("y");
		//10分後
		if( 0 ){ // (FFF > 10*60*1000)
			$("#ddd").after(",顯示檔案");
			//不顯示原圖 減輕頁面負擔 
			if( $(".image_orig").length >0 ){
				$(".image_orig").each(function(index,v){
					$(v).after('remove');
					$(v).remove();
				});
			}
			//不顯示影片
			if( $(".video_orig").length >0 ){
				$(".video_orig").each(function(index,v){
					$(v).after('remove');
					$(v).remove();
				});
			}
		}else{
			$("#ddd").after(",備份檔案");
			//顯示
			if( $.gginin.var190114.TouchEvent >0  ){ //|| 1==1
				console.log('有觸控');
				timeloop190209();//一秒顯示一張大圖
			}else{
				console.log('無觸控');
				pp190211();//逐次讀取圖片 //一張讀完再讀下一張
				//timeloop190209();//一秒顯示一張大圖//190828
			}
		}
	}else{
		$("#ddd").after("???");
		console.log("時間錯誤");
	}
}//f

function timeloop190209(){
	//alert('timeloop190209');
	//一秒顯示一張大圖
	//$.gginin.var190209.count;//全域變數
	//
	//var xx=$(".image_orig");//$(".image_orig")
	//var FFF=$(".image_orig").length;\
	//var cc=0;
	$(".image_orig").each(function(index,v){
		setTimeout(function(){
			$(v).attr("src", $(v).attr("src2") );
			//$(v).prev().removeAttr("href");
		}, $.gginin.var190209.count *1000);
		$.gginin.var190209.count++;
	});
	$("#ddd").after("," + $.gginin.var190209.count );
	
}//fnc



function pp190211(){
	if( $(".image_orig").length >0 ){
		//有圖
		//$("#ddd").after(",圖");
		//console.log("圖");

		//
		//fnc181214_event();
		poi10();//逐個讀取圖片 //把src2改成src
		//test02();
	}
	if( $(".video_orig").length >0 ){
		$("#ddd").after(",影");
		//console.log("影");

		//
		poi190210();//檢查video狀態
	}


}//ff


function fnc181214_event(){
	$(".image_orig").each(function(index,v) {
		var cc=$.gginin.count;
		$.gginin.count=$.gginin.count + 1;
		$("#ddd").after(cc);
		//
		$(this).on("load", function(e){
			$(this).after('成功');
		});
		$(this).on("error", function(e){
			$(this).after('失敗');
		});
	});
}
function poi190210(){
	//console.log('poi190210');
	//檢查video狀態
	//if(window.location.hostname == '' ){}else{}
	
	$("video.video_orig").each(function(index,v) {
		$(v).attr("src", $(v).attr("src2") );
		$(v).removeAttr("src2");
	});
	var video_time_start=$.gginin.var190211.video_start_time;
	var video_time_end=0;
	var time_diff=0;
	$("video.video_orig").each(function(index,v) {
		$(v).on('loadedmetadata',function(){
			//$(this).after('loadedmetadata');
			video_time_end=Date.now();//
			time_diff=video_time_end-video_time_start;
			$(this).after('🌐'+time_diff);
		});
		$(v).on('error',function(){
			//$(this).after('error');
			console.log( 'error',this.id );
		});
		$(v).on('stalled',function(){
			//$(this).after('stalled');
			console.log( 'stalled',this.id );
		});
	});
}//poi190210


function poi190113(element){//????
 	//console.log('poi190113');
	//xhr讀取
	var video_url=element.src;
 	//console.log( video_url );
	
	
	var xhr = new XMLHttpRequest();
	xhr.onloadstart = function () {
		//console.log("xhr.onloadstart");
		//console.log( element );
		//var id_new='id'+Date.now();
		var id_new='span_id_'+element.id;
		//
		//console.log( id_new );
		element.insertAdjacentHTML('afterend', '<sapn id="'+id_new+'">'+element.id+'</span>');
	};	
	xhr.open('GET', video_url);
	xhr.responseType = 'blob';
	xhr.overrideMimeType('video/webm');
	xhr.send();
	//
    xhr.ontimeout = function(e){
		console.log("xhr.ontimeout");
	};
	xhr.onreadystatechange = function(e){
		console.log("xhr.onreadystatechange");
		if(xhr.readystate == 4){
			if(xhr.status ==200){
				if('response' in xhr){
					//xhr.response
					console.log(xhr);
				}
			}
		}
	};
	xhr.onprogress = function(e) {
		//console.log("progress",e,this);
		//console.log("progress",e.loaded,e.total);
		var id_new='span_id_'+element.id;
		$('#'+id_new).html('讀'+e.loaded);
	};//xhr.onprogress
	xhr.onload = function(e){
		var id_new='span_id_'+element.id;
		console.log( id_new );
		$('#'+id_new).append('成功'+id_new);
	};
	
	
	
}//poi190113()



function poi10(){
	//逐個讀取圖片 //把src2改成src
	var cc=$.gginin.count;
	$.gginin.count=$.gginin.count + 1;
	$("#ddd").after(","+cc);
	//
	var FFF='';
	FFF=$(".image_orig");
	console.log( cc , $(".image_orig").length );
	if( cc < FFF.length ){
		//console.log( FFF );
		FFF=FFF[cc];
		//console.log( $(FFF) );
		//console.log( $(FFF).attr("id") );
		$.gginin.time_checkpoint=(new Date()).getTime();
		//改變src
		if( $(FFF).attr("src") == $.gginin.var181219.base64_image ){
			//沒事
		}else{
			//圖片連結被改變了
			poi10();//跳過
		}
		//img區塊顯示原圖 //把src2改成src
		$(FFF).attr("src", $(FFF).attr("src2") );
		$(FFF).removeAttr("src2");
		//讀取完成的事件
		$(FFF).on('load', function(e){
			//console.log(e);
			//console.log( $(this) );
			//console.log('event.type=' + e.type);
			$(FFF).after('成功'+cc+'');
			var tmp=((new Date()).getTime() - $.gginin.time_checkpoint);
			$(FFF).after('耗時'+ tmp +'毫秒');
			//下一個
			poi10();
		});
		//讀取錯誤的事件
		$(FFF).on('error', function(e){
			$(FFF).after('失敗');
			$(FFF).after('耗時'+ ((new Date()).getTime() - $.gginin.time_checkpoint) +'毫秒');
			//下一個
			poi10();
		});


	}else{
		//完成
		$("#ddd").after("結束");
		//$("#ddd").before(""+navigator.userAgent);
		console.log("結束");
		js_all_done();
	}

}//ff

function js_all_done(){
	console.log("js_all_done");
	//console.log( location.href );
	$("#ddd").css({
		"border-left":"#$url_hash 2px solid",
		"pointer-events":"auto", //none

	});
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
