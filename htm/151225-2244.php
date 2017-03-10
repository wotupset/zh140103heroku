<?php 
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
//echo set_time_limit();
//ini_set('max_execution_time',0);
$query_string=$_SERVER['QUERY_STRING'];
$phphost=$_SERVER["SERVER_NAME"];
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?".$query_string;
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//$input_a=$_POST['input_a'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('y/m/d H:i:s',$time);//輸出的檔案名稱
//if($query_string){$url=$query_string;}else{$url=$input_a;}
$url=$input_a;
$url=trim($url);
//載入外部檔案
$tmp="../curl_getinfo.php";
if(!file_exists($tmp)){die('file_exists');}//file_exists
require_once($tmp);
$tmp='../simple_html_dom.php';
if(!file_exists($tmp)){die('file_exists');}//file_exists
require_once($tmp);
//
//處理網址

$url_p1=parse_url($url);
//print_r($url_p);
/*
Array
(
    [scheme] => http
    [host] => sage.komica.org
    [path] => /00/src/1406616621815.jpg
)
*/
$url_p2=pathinfo($url_p['path']);
//print_r($url_i);
/*
Array
(
    [dirname] => /00/src
    [basename] => 1406616621815.jpg
    [extension] => jpg
    [filename] => 1406616621815
)
*/
//echo preg_match('/archive/',$url_p1['host'],$match);
//print_r($match);exit;
//允許的網址格式
//$url=$query_string;//從query取得網址
$kdao_only=0;

if(preg_match("%komica\.org/.{1,10}/index\.php\?%",$url,$matches))
{$kdao_only=1;}

$url_num2=$matches[1];
//
//抓首串編號
$pattern="%index.php\?res=([0-9]+)%";
if(preg_match($pattern, $url, $matches_url)){
	//echo $matches_url[1];
	$url_num=$matches_url[1];
}


//允許的網址格式//

//if($kdao_only){//只使用於綜合網址
$tmp="./170227require_once.php";
if(!file_exists($tmp)){die('file_exists');}//file_exists
require_once($tmp);


phppoi();

exit;
//

function curlpost_html($x){
	//$output_path=$GLOBALS['output_path'];
	$output_path=$x;
	//
	$tmp=$output_path;
	if(class_exists('CurlFile')) {
		//$upf= new CurlFile($tmp, $type_org, $tmp);//=curl_file_create
		$upf= new CurlFile($tmp);//=curl_file_create
	}else{
		$upf= '@'.realpath($tmp);
	}
	//$upf='@'.realpath($tmp).';filename=this.htm';
	$md5=md5_file($tmp);
	$myvars['md5']= $md5;
	//echo $upl;
	$myvars['upfile']= $upf;
	$myvars['pass']= 'xopowo';
	//$myvars=array('file' => '@' . realpath('example.txt'));
	//
	$url_ary = array();
	$url_ary[]='http://cqcq.fastsito.com/htm/151225-2244put.php';
	$url_ary[]='http://zh161005.comli.com/htm/151225-2244put.php';
	$FFF=array_rand($url_ary,1);
	$url=$url_ary[$FFF];
	//
	//$url='http://zh160213.1000space.tk/htm/151225-2244put.php';
	//$useragent='Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36';
	$ch = curl_init();//初始化
	if(!$ch){die('[x]curl初始化失敗');}

	curl_setopt($ch, CURLOPT_URL,            $url);//網址
	curl_setopt($ch, CURLOPT_POST,           1);//post模式
	curl_setopt($ch, CURLOPT_POSTFIELDS,     $myvars);//參數
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);//跟随重定向页面//safe_mode = Off 
	curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
	//curl_setopt($ch, CURLOPT_USERAGENT,      $useragent);
	
	$getdata  = curl_exec($ch);//抓取URL並把它傳遞給變數
	$getinfo  = curl_getinfo($ch);//結果資訊
	$geterror = curl_errno($ch);
	curl_close($ch);
	//
	$FFF='';
	$FFF.='getdata='.print_r($getdata,true)."\n";
	$FFF.='getinfo='.print_r($getinfo,true)."\n";
	$FFF.='geterror='.print_r($geterror,true)."\n";
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
	//$htmlbody=htmlhead().$htmlbody.htmlendd();
	$path='./_'.$ym.'/';
	if( !is_dir($path) ){
		mkdir($path, 0777); //建立資料夾 權限0777
		chmod($path, 0777); //權限0777
	}
	if( !is_dir($path) ){die("資料夾建立失敗");}
	if( !is_writeable($path) ){die("資料夾無法寫入");}
	if( !is_readable($path) ){die("資料夾無法讀取");}
	//
	$tmp=$path."index.php";
	if(!is_file($tmp)){copy("./index.php", $tmp);}
	if(!is_file($tmp)){die("複製檔案失敗");}
	//
	//$tmp=$path.'dbp_'.$time.'_'.$url_num2.'n'.$url_num.'_'.rdm_str().'.htm';
	$tmp=$path.'dbp_'.$time.'_'.$url_num2.'n'.$url_num.'.htm';
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

function phppoi(){
	$phpself=$GLOBALS['phpself'];
	$kdao_only=$GLOBALS['kdao_only'];
	$phplink=$GLOBALS['phplink'];
	$htmlbody=$GLOBALS['htmlbody'];
	$output_path=$GLOBALS['output_path'];
	$curlpost=$GLOBALS['curlpost'];
	//一般頁面
	echo htmlhead();
	echo form();
	$output='';
	if($kdao_only){
		//$output.='<span id="span_iframe01" style="display:block; width:50px; height:50px; BORDER:#000 1px solid;">archive.today</span>'."<br/>\n";
		//$output.='<span id="span_iframe02" style="display:block; width:120px; height:50px; BORDER:#000 1px solid;">archive.org</span>'."<br/>\n";
	}
	$output.='<a href="'.$output_path.'">'.$output_path.'</a>'."\n";
	$output.='<a href="./'.$phpself.'">返</a>'."\n";
	$output.='<a href="./">根</a>'."\n";
	
	$output.='<pre>'.$curlpost.'</pre>'."\n";

	//$output.='<a href="https://archive.today/?run=1&url='.$phplink.'" target="_blank">archive.today</a>'."\n";
	//$output.='<a href="https://web.archive.org/save/'.$phplink.'" target="_blank">archive.org</a>'."\n";

	echo $output;
	echo $htmlbody;
	echo "<br/>\n";
	echo htmlendd();
}


////
function rdm_str($x=''){
	for($i=0;$i<3;$i++){
		$x=$x.chr(rand(48,57)).chr(rand(65,90)).chr(rand(97,122)); //
	}
	return $x;
}
//htmlhead()
function htmlhead(){
	$phpself=$GLOBALS['phpself'];
	$url_num=$GLOBALS['url_num'];
	$ymdhis=$GLOBALS['ymdhis'];
	$board_title2=$GLOBALS['board_title2'];
	$phplink=$GLOBALS['phplink'];
	
	//
$x=<<<EOT
<html><head>
<title>$board_title2</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>

<meta name="Robots" content="index,follow">
<STYLE>
img.zoom {
height:auto; width:auto;
min-width:20px; min-height:20px;
max-width:250px; max-height:250px;
border:1px solid blue;
padding-right:5px;
background-color:#00ffff;
display:none;
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
<script>
//表單有用到
var click1=1;		
function check(){		
	click1=0;		
	document.getElementById("send").innerHTML="稍後";		
	document.getElementById("form01").action="$phpself?"+document.getElementById("input_a").value;		
	document.getElementById("form01").onsubmit();		
}		
function check2(){		
	//document.getElementById("send").disabled=true;		
	document.getElementById("send").style.background="#ff0000";		
	document.getElementById("form01").submit();		
}
//
	
$(document).ready(function() {
	var time = new Date();
	poi();
});


function poi_170222(){
	$("img").after('after');
	alert($("img").length);
	//
	var t=0;
	var time_old=0;
	var time_x1=time_x2=0;
	//
	time_orig = new Date().getTime();
	time_orig = Number(time_orig);
	time_orig = Math.floor(time_orig/1000);
	//
	var timedown_x = setInterval(function() {
		//
		//
		t=t+1;
		//console.log( 'time_old'+time_old );
		time_now = new Date().getTime();
		time_now = Number(time_now);
		time_now = Math.floor(time_now/1000);
		time_old = time_now;
		//console.log( 'time_now'+time_now );
		//
		time_x1 = time_now - time_orig;
		//
		if(1==1){
			if(time_x1 != time_x2){
				console.log( 'poi' );
				xopowo(t);
			}
			timedown_x;
		}else{
			clearInterval(timedown_x);
		}
		//
		time_x2 = time_x1;
		//
	}, 1000);
	//
}
	function xopowo(t){
		if($("img").get(t)){
			var FFF=$("img").get(t);
			FFF.after(FFF.src);
		}
	}
//
function poi(){
	//
	$("img").after('after');
	//$("img").css( "display", "none" );//
	//alert($("img").length);
	//$("img").css( "display", "none" );
	//
	var images = $('img');
	var tmp='';
	$.each(images, function() {
		tmp=$(this).attr('src');
		console.log( '但'+tmp);
		$(this).attr('src_x',tmp);
		//$(this).attr('src','');
	    $(this).removeAttr("src");
	});
	
	//
	var t=0;
	var timedown_x = setInterval(function() {
		//
		//
		if( $("img").get(t) ){
			console.log( $("img").get(t) );
			$("img").get(t).after($("img").get(t).src);
			//
			tmp=$("img").get(t);
			//console.log( tmp );
			\$tmp=$(tmp);//becomes javascript object.
			//console.log( $tmp );
			\$tmp.attr('src', \$tmp.attr('src_x') );
			\$tmp.removeAttr("src_x");
			\$tmp.css( {'background-color':'#343434'} );
			\$tmp.css( "display", "inline" )
			//
			timedown_x;
		}else{
			console.log( '但願人長久 千里共嬋娟' );
			clearInterval(timedown_x);
		}
		//
		console.log( t );
		t=t+1;
		//
		//
		//
	}, 1900);
}


//
	

</script>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
EOT;
	$x="\n".$x."\n";
	return $x;
}
//htmlhead()//

//htmlendd()
function htmlendd(){
$x=<<<EOT
</body></html>
EOT;
	$x="\n".$x."\n";
	return $x;
}
//htmlendd()//
//form()
function form(){
	$phpself=$GLOBALS['phpself'];
$x=<<<EOT
<form id='form01' enctype="multipart/form-data" action='$phpself' method="post" onsubmit="return check2();">
綜合網址<input type="text" name="input_a" id="input_a" size="20" value="">
<br/>
<span style="display:block; width:120px; height:90px; BORDER:#000 1px solid;" id='send' name="send" onclick='if(click1){check();}'/>送出</span>
</form>
EOT;
	$x="\n".$x."\n";
	return $x;
}
//form()//
?>
