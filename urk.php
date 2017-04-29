<?php 
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."";
$httplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING'];//
$host=$_SERVER["SERVER_NAME"]; //主機名稱
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=(string)time();
$time2=date("ymd-His", $time);//年月
//echo $time2;exit;
//
$mode = $_POST["mode"];
$org_url = $_POST["org_url"];
//$enc_key='123';
$enc_key = urlencode($_POST["enc_key"]);
if(!$enc_key){$enc_key=$time2;}
$query_string=$_SERVER['QUERY_STRING'];
////////
$tmp='./Discuz_AzDGCrypt.php';
if(!file_exists($tmp)){die('file_exists');}//file_exists
require_once($tmp);
//

//$htmlend

$echo_body='';
$tmp='';
switch($mode){
	case 'reg':
		if($org_url){
			//$echo_body.="<pre>$org_url</pre>";
			$org_url=trim($org_url);//去頭尾空白
			$org_url_crc32= hash('crc32', $org_url ) ; //sprintf("%u", )
			$org_url2=$org_url_crc32.$org_url;//驗證用
			$enc_url=passport_encrypt($org_url2,$enc_key);//編碼
			$FFF=$enc_url;
			$FFF= str_replace("+", "_", $FFF);
			$FFF= str_replace("/", "-", $FFF);//迴避網址字元
			$enc_url=$FFF;
			//
			$enc_url='http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"].'?'.$enc_url."!".$enc_key;
			//$echo_body.="<form><input type='text' size='40' value='$enc_url'></form>";
			//$echo_body.="<pre>$enc_url2</pre>"."\n";
			$echo_body.='<input type="text" size="20" value="'.$enc_url.'"><a href="'.$enc_url.'">連結</a><br/>'.$org_url.'<br/>';//放在輸入框內
			//$echo_body.="<pre>$enc_key</pre>"."\n";
			//$echo_body.="<pre>$enc_url</pre>"."\n";
			$echo_body.=htmlform();//$form;
			//$echo_body.="<a href='./$phpself'>&#10006;BACK</a> ";
		}else{
			$echo_body.="<pre>no url</pre>";
			$echo_body.=htmlform();//$form;
			//$echo_body.="<a href='./$phpself'>&#10006;BACK</a> ";
		}
	break;
	default:
		if($query_string){//網址有參考值
			$FFF='';
			$FFF=explode("&",$query_string);//list($qs2,)=explode("&",$query_string);
			$tmp2_arr=explode("!",$FFF[0]);
			switch($tmp2_arr[0]){
				case 'url':
					session_id($tmp2_arr[1]); //指定sid
					session_start(); //session
					$url_dec=$_SESSION['se'];
					if(isset($_SESSION['se'])){ //給ext驗證用
						$chk150808='1';
					}else{
						$chk150808='0';
					}
//bbcode()
$string = $url_dec; //bbcode目前只使用連結功能
$string = preg_replace("/(^|[^=\]])(http|https)(:\/\/[\!-;\=\?-\~]+)/si", "\\1<a href=\"\\2\\3\" target='_blank'>\\2\\3</a>", $string);
$url_dec = $string;
//bbcode(/)
					$echo_body.="<div>$url_dec</div>\n";
					//$echo_body.="<a href='./$phpself'>&#10006;BACK</a>"."<br/>\n";
					//session_destroy();//清空sess
					unset($_SESSION['se']);
				break;
				default:
					//$FFF=explode("!",$query_string);
					//$query_string=$FFF[0];
					//$enc_key=$FFF[1];
					list($query_string,$enc_key)=explode("!", $query_string); //substr($query_string,0, strpos($query_string,'&') )
					list($enc_key,)=explode("&",$enc_key);
					$FFF= $query_string;
					$FFF= str_replace("_", "+", $FFF);
					$FFF= str_replace("-", "/", $FFF);
					$query_string=$FFF;
					$url_dec=passport_decrypt($query_string,$enc_key);//解密
					//echo $url_dec;exit;
					if( substr($url_dec,0,8) ==  hash('crc32', substr($url_dec,8)) ){ //驗證成功
						//成功
						$url_dec=substr($url_dec,8);//8碼
						//$echo_body.='<br/>'."\n";
						$echo_body.='<div id="link">v2</div>'."\n";
					}else{//驗證失敗? 重新要求輸入key
						if( substr($url_dec,0,5) =='12345'){ //舊版連結
							$url_dec=substr($url_dec,5); 
							//成功
							$echo_body.='<div id="link">v1</div>'."\n";
						}else{
							//$echo_body.='錯誤?<br/>'."\n";
							$echo_body.='<div id="link">E??</div>'."\n";
						}
					}
						/////////////
						//
						session_start(); //session
						session_regenerate_id();//重新生成id
						$_SESSION['se']=$url_dec;
						if(!isset($_SESSION['se'])){die('session失敗');}
						$sid = session_id(); 
						$tmp=$phpself."?url!".$sid;
//
$tmp2=base64_encode($tmp);//
setcookie("k12", $tmp2, time()+3600);//設定cookie
//
$echo_body.=html_js();
						//$echo_body.="<pre>$query_string</pre>"."\n";
						////////////
					//
					//$echo_body.='<a href="./'.$phpself.'">&#10006;BACK</a>'."\n";
				break;
			}
		}else{//網址沒有參考值
			$echo_body.=htmlform();//$form;
			$echo_body.="<a href='./'>&#10006;ROOT</a>";
		}
	break;
}
//
if(0){//$query_string
	$FFF='';
	$FFF=explode("!",$query_string);
	switch($FFF[0]){
		//case 'url':
		//break;
		default:
			$out = '';
			ob_start(); //打开缓冲区 
			$tmp="./urk_ext.php";
			if(!file_exists($tmp)){die('file_exists');}//file_exists
			require $tmp;
			$out = ob_get_clean();
			$out = '';//不顯示
		break;
	}
}
////////
//
//echo htmlhead();//$htmlhead;
//echo $echo_body;
echo html_echo($echo_body);

exit;
//

/*
$chk_time_key='abc123';
$chk_time_enc=passport_encrypt($time,$chk_time_key);
$chk_time_dec=passport_decrypt($chk_time_enc,$chk_time_key);
echo $time.' '.$chk_time_enc.' '.$chk_time_dec;
*/
function html_js(){
	$x='';
	//
$x=<<<EOT

<div id="myCanvas_div">
<canvas id="myCanvas" width="300" height="177" style="border:1px solid #c3c3c3;" onmousemove="cnvs_getCoordinates(event);" onmouseout="cnvs_clearCoordinates()">
Your browser does not support the canvas element.
</canvas>
</div>
<div id="rain"></div>
<div id="xycoordinates"></div>


<script type="text/javascript" language="javascript">
//全局變數
doc_ready=0;
time = new Date();
global=[];
//
$(document).ready(function() {
	doc_ready=1;
	poi();
	//$(document).mousemove(function(event) {});
});
//
function poi(){
	var ctx = document.getElementById('myCanvas').getContext("2d");
	//引用圖片
	var img = new Image();
	img.onload = function () {
		ctx.globalCompositeOperation='destination-over';//將新圖形畫在舊圖形之下。
		poi02();//寫字
		ctx.drawImage(img, 0, 0);//貼圖
		ctx.globalCompositeOperation='source-over';//將新圖形畫在舊圖形之上。
	}
	img.src = "http://i.imgur.com/WtOyglG.png?1";
	cxt='';
}
function poi02(){
	//寫字
	var c = document.getElementById("myCanvas");
	var ctx = c.getContext("2d");
	ctx.font = "30px Verdana";//字型大小
	// Create gradient
	var gradient = ctx.createLinearGradient(0, 0, c.width, 0);
	gradient.addColorStop("0", "magenta");
	gradient.addColorStop("0.5", "blue");
	gradient.addColorStop("1.0", "red");
	// Fill with gradient
	ctx.fillStyle = gradient;
	ctx.fillText("移動滑鼠", 10, 90);
	ctx='';
}
function cnvs_getCoordinates(e){
	if(doc_ready && 1){//畫點
		x=e.clientX;
		y=e.clientY;
		x2=0;
		y2=0;
		x=x-document.getElementById("myCanvas").offsetLeft;
		y=y-document.getElementById("myCanvas").offsetTop;
		//document.getElementById("xycoordinates").innerHTML="(" + x + "," + y + ")";
		var c=document.getElementById("myCanvas");
		var ctx=c.getContext("2d");
		ctx.fillStyle="#FF0000"; //紅色
		ctx.beginPath();
		ctx.arc(x,y,2,0,Math.PI*2,true);
		ctx.closePath();
		ctx.fill();
		ctx='';
	}

}

function cnvs_clearCoordinates(){
	document.getElementById("xycoordinates").innerHTML="";
}
function randomColor(){ 
	return('#'+Math.floor(Math.random()*16777215).toString(16));
}
////

//var cookieValue = $.cookie("k12");
var cookieValue = getCookie("k12");
cookieValue = decodeURIComponent(cookieValue);
//alert(cookieValue);
//var url = '$tmp2';
var url = cookieValue;
//
var xx=yy="";
var dda_x=dda_y="";
var ddb_x=ddb_y="";
var ddc_x=ddc_y="";
//
global[10]=0;
var chk=1;
var cc=0;
$('#myCanvas').mousemove(function(event) {//document
	if(doc_ready){
		if(chk){
			global[10]++;
			cc++;
			//document.getElementById("rain").innerHTML =global[10];
			var ccmax=88;
			if(global[10] > ccmax){
				chk=0;
				//
				var c=document.getElementById("myCanvas");
				var ctx=c.getContext("2d");
				//console.log(ctx['canvas']['width']);
				//
				if(ctx['canvas']['width'] == 300){
					url=atob(url);
					document.getElementById("link").innerHTML ='<a href="'+url+'">點我</a>'; //
					//document.getElementById("link").style.backgroundColor = "#ffff00";
					document.getElementById("link").setAttribute("style", "background-color:#ffff00;");
					//document.getElementById("myCanvas_div").innerHTML="";
					//alert('ok');
				}
			}else{
				if(event.pageX!=xx || event.pageY!=yy){
					//
					ddc_x=ddb_x;
					ddc_y=ddb_y;
					ddb_x=dda_x;
					ddb_y=dda_y;
					dda_x=event.pageX - document.getElementById("myCanvas").offsetLeft;
					dda_y=event.pageY - document.getElementById("myCanvas").offsetTop;
					//document.getElementById("rain").innerHTML =dda_x+','+dda_y+"<br>"+ddb_x+','+ddb_y+"<br>"+ddc_x+','+ddc_y+"<br>"+global[10];
					//document.getElementById("rain").innerHTML =global[10];
					if(1){//畫線
						var c=document.getElementById("myCanvas");
						var ctx=c.getContext("2d");
						console.log(ctx['canvas']['width']);
						//
						ctx.save();//儲存狀態
						//
						ctx.translate(5,5+cc);//位移
						ctx.rotate( (-cc*Math.PI)/180 );//轉動
						ctx.globalAlpha=(cc / ccmax );//透明
						//畫線
						ctx.beginPath();
						ctx.moveTo(0,0);
						ctx.lineTo(20,20);
						ctx.strokeStyle="blue";
						ctx.stroke();
						//
						ctx.restore();//回覆狀態
						ctx='';
					}
					if(1){//畫圓
						var c = document.getElementById("myCanvas");
						var ctx = c.getContext("2d");
						ctx.beginPath();
						//中心位置
						var center_x =0;
						var center_y =0;
						//半徑
						var center_r =50;
						//角度
						var arc_s = 1.5*Math.PI;
						var arc_e = ((global[10] / ccmax )*2 +1.5)*Math.PI ;
						//
						ctx.save();//儲存狀態
						ctx.setTransform(1,0.2,0.5,1,100,75);//变换矩阵//變形
						//畫圓
						ctx.arc(center_x, center_y, center_r, arc_s, arc_e);
						//ctx.closePath();
						//畫線
						ctx.lineTo(center_x,center_y);//連線到
						ctx.strokeStyle=randomColor();//隨機顏色
						ctx.stroke();//畫線
						//
						ctx.restore();//回覆狀態
						ctx='';
					}
				}
				xx=event.pageX;
				yy=event.pageY;
			}
		}
	}
});

//
function getCookie(cname) {//取得cookie
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
	var c = ca[i];
	while (c.charAt(0)==' ') c = c.substring(1);
		if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	}
	return "";
}
</script>


EOT;
	//
	return $x;
}
//
function htmlhead(){
	$x='';
	//
$x=<<<EOT
<html><head>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META NAME="ROBOTS" CONTENT="noINDEX, FOLLOW">
<title>檔案讀寫練習</title>
</head><body>
EOT;
	//
	return $x;
}
//
function htmlform(){
	$enc_key=$GLOBALS["enc_key"];
	$x='';
	//
//$form
$x=<<<EOT
<form id='form1' action='$phpself' method="post" autocomplete="off">
<input type="hidden" name="mode" value="reg">
<input type="text" name="org_url" id="org_url" size="20" placeholder="http" value=""><br/>
<input type="text" name="enc_key" id="enc_key" size="20" placeholder="key" value="$enc_key"><br/>
<input type="submit" value="送出"/>
</form>
EOT;
	//
	return $x;
}

//
function html_echo($x){
	$phpself=$GLOBALS["phpself"];
	//
$html=<<<EOT
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
</head>
<body>
<div>
<a href="./">&#10006;HOME</a>
<a href="$phpself">&#10006;BACK</a>
</div>
$x
</body>
</html>
EOT;
	//
	$x=$html;
	return $x;
}
?>
