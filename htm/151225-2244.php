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
if(preg_match("%dreamhosters\.com/[0-9]{2}/%U",$url))
{$kdao_only=1;}
if(preg_match("%komica\.org/([0-9]{2})/%U",$url,$matches))
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
//
$htmlbody="";
$htmlbody2="";
$img_all='';
$cc1=0;//回文數
$cc2=0;//貼圖數
//echo $query_string;
if($kdao_only){//只使用於綜合網址
	//取得來源內容
	if(1){
		$x=curl_FFF($url);
		//echo print_r($x,true);exit;
		$getdata =$x_0 =$x[0];//資料
		$getinfo =$x_1 =$x[1];//訊息
		$geterror=$x_2 =$x[2];//錯誤
		//simple_html_dom
		if(!$getdata){echo print_r($getinfo,true);exit;}
		$content=$getdata;
	}
	if(0){
		$ch = curl_init();
		if(!$ch){die('[x]curl');}
		//
		$ret = curl_setopt($ch, CURLOPT_URL,            $url);
		$ret = curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
		$ret = curl_setopt($ch, CURLOPT_NOBODY,         0);//是否隱藏body頁面內容
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
		$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        10);//超時
		$ret = curl_setopt($ch, CURLOPT_FAILONERROR,    1);//發生錯誤時不回傳內容
		//$ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//跟随重定向页面
		$ret = curl_setopt($ch, CURLOPT_MAXREDIRS,      3);//跟随重定向页面的最大次數
		$ret = curl_setopt($ch, CURLOPT_AUTOREFERER,    1);//重定向页面自动添加 Referer header 
		
		//$ret = curl_setopt($ch, CURLOPT_REFERER,        "http://eden.komica.org/");//自訂來路頁面 用來獲取目標
		//$ret = curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		//
		$getdata  = curl_exec($ch);//抓取URL並把它傳遞給變數
		$getinfo  = curl_getinfo($ch);//結果資訊
		$geterror = curl_errno($ch);
		//
		$content=$getdata;
	}
	if(0){
		$opts = array('http'=>array('method'=>"GET",'timeout'=>5));
		$stream = stream_context_create($opts);
		$max_size=5*1024*1024;//抓取上限
		$content = file_get_contents($url,NULL,$stream,0,$max_size) or die('[x]file_get_contents');
	}
	//echo print_r( htmlspecialchars($content) ,true);exit;//檢查點
	//檢查資料A
	if(strlen($content) == 0){$htmlbody='沒有資料';phppoi();exit;}
	if(!preg_match("/body/i",substr($content,0,500))){$htmlbody='不是HTML檔案';phppoi();exit;}
	//去掉異常字串
	$content = preg_replace("/\n/","",$content);
	$content = preg_replace("/\t/","",$content);
	$content=preg_replace("/[\x1-\x1F]/", "", $content);
	$content=preg_replace("/[\x7F]/", "", $content);
	//
	$html = str_get_html($content) or die('沒有收到資料');//simple_html_dom自訂函式
	$chat_array='';
	$chat_array = $html->outertext;
	//檢查資料B
	if(0){
		if(preg_match("/\.cloudflare/i",$chat_array) || true){
			//print_r(htmlspecialchars($chat_array));die('[x]cloudflare');
			$htmlbody='[x]cloudflare';phppoi();exit;
		}
	}
	//
	$board_title = $html->find('span',0)->innertext;//版面標題
	$board_title2=''.$board_title.'=第'.$url_num.'篇 於'.$ymdhis.'擷取';
	$cc=0;
	foreach( $html->find('blockquote') as $k => $v){$cc++;}
	if(!$cc){
		//print_r(htmlspecialchars($chat_array));die('[x]blockquote');
		$htmlbody='[x]blockquote';phppoi();exit;
	}
	//echo print_r($chat_array,true);exit;//檢查點
	

	
	
	//
	//批次找留言
	$chat_array=array();
	$cc=0;
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
		//首篇另外處理
		if($k == 0 ){
			//XX
		}else{
			$vv=$v->parent;
			//原始內容
			$chat_array[$k]['org_text']=$vv->outertext;
			//標題
			if(preg_match('/archive/',$url_p1['host'],$match)){ //檔案區
			//if(1){ //檔案區
				foreach($vv->find('span.Ctitle') as $k2 => $v2){
					$chat_array[$k]['title'] =$v2->plaintext;
					$v2->outertext="";
				}
				foreach($vv->find('span.Cname') as $k2 => $v2){
					$chat_array[$k]['name'] =$v2->plaintext;
					$v2->outertext="";
				}
			}else{
				foreach($vv->find('font') as $k2 => $v2){
					if($k2==0){//標題
						$chat_array[$k]['title'] =$v2->plaintext;
						$v2->outertext="";
					}
					if($k2==1){//名稱
						$chat_array[$k]['name'] =$v2->plaintext;
						$v2->outertext="";
					}
				}
			}
			
			//內容
			foreach($vv->find('blockquote') as $k2 => $v2){
				$chat_array[$k]['text']  =$v2->innertext;//內文
				$v2->outertext="";
			}
			//圖片
			foreach($vv->find('a') as $k2 => $v2){
				foreach($v2->find('img') as $k3 => $v3){
					$chat_array[$k]['image']  =$v3->parent->href;//
					$chat_array[$k]['image_t'] =$v3->src;
				}
				$v2->outertext="";
			}
			//刪除的
			foreach($vv->find('a.del') as $k2 => $v2){
				$v2->outertext="";
			}
			//剩餘的
			$chat_array[$k]['zzz_text']=$vv->outertext;
			//
			//$chat_array[$k]['time']=strip_tags($chat_array[$k]['zzz_text']);
			preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+ /U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
			$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
			//整理過的清掉
			$vv->outertext='';
		}
	}
	if(!$cc){die('沒有找到blockquote');}
	//echo print_r($chat_array,true);exit;//檢查點
	
	
	//首篇另外處理
	$html = $html->find('form',1)->outertext;
	$html = str_get_html($html);//重新轉字串解析//有BUG?
	//$first_post=$html;
	//
	$chat_array[0]['org_text'] = $html->outertext;//原始內容
	//
	if(preg_match('/archive/',$url_p1['host'],$match)){ //檔案區
		foreach($html->find('span.Ctitle') as $k2 => $v2){
			$chat_array[0]['title'] =$v2->plaintext;
			$v2->outertext="";
		}
		foreach($html->find('span.Cname') as $k2 => $v2){
			$chat_array[0]['name'] =$v2->plaintext;
			$v2->outertext="";
		}
	}else{
		foreach($html->find('font') as $k2 => $v2){
			if($k2==0){//標題
				$chat_array[0]['title'] =$v2->plaintext;
				$v2->outertext="";
			}
			if($k2==1){//名稱
				$chat_array[0]['name'] =$v2->plaintext;
				$v2->outertext="";
			}
		}
	}

	//內容
	foreach($html->find('blockquote') as $k2 => $v2){
		$chat_array[0]['text']  =$v2->innertext;//內文
		$v2->outertext="";
	}
	//圖片
	foreach($html->find('a') as $k2 => $v2){
		foreach($v2->find('img') as $k3 => $v3){
			$chat_array[0]['image']  =$v3->parent->href;//
			$chat_array[0]['image_t'] =$v3->src;
		}
		$v2->outertext="";
	}
	//
	$chat_array[0]['zzz_text'] = $html->outertext;//剩餘的內容//非檢查點//下方有用到
	//
	preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+ /U",$chat_array[0]['zzz_text'],$chat_array[0]['time']);
	$chat_array[0]['time'] = implode("",$chat_array[0]['time']);
	//
	ksort($chat_array);//排序
	$chat_ct=count($chat_array);//計數
	//echo print_r($chat_array,true);exit;//檢查點
	//
	
	
	
	
	//
	//用迴圈叫出資料
	$cc=0;
	foreach($chat_array as $k => $v){//迴圈
		$cc++;
		$htmlbody.= '<div id="block'.$cc.'">'."\n";
		//名稱
		$v['name']=strip_tags($v['name']);
		$htmlbody.= '<span class="name">'.$v['name'].'</span>'."\n";
		$htmlbody.= '<span class="title">'.$v['title'].'</span>'."\n";
		//名稱 ID時間
		$v['time']=strip_tags($v['time']);
		$htmlbody.= '<span class="idno">'.$v['time'].'</span>'."\n";
		//內文
		$v['text']=strip_tags($v['text'],"<br><font>");//留下換行標籤
		$htmlbody.= '<span class="text"><blockquote>'.$v['text'].'</blockquote></span>'."\n";
		if( $v['image'] ){//回應中有圖 // 網址字串
			$cc2++;//計算圖片數量
			//
			//$htmlbody.= '[<span class="image"><a href="'.$v['image'].'" target="_blank"><img class="zoom" src="'.$v['image'].'"/></a></span>]'."<br/>\n";
			//$tmp='http://zh150609.xp3.biz/mysql_blob.php?cdn!'.$v['image'];
			//$tmp0="http://web.archive.org/web/2016/".$v['image'];
			//$htmlbody.= '[<span class="image"><img class="zoom" src="'.$tmp.'"/></span>]';
			//$tmp="http://demo.cloudimg.io/cdn/n/n/".$v['image'];

			//$tmp="http://demo.cloudimg.io/cdn/n/n/"."http://web.archive.org/web/2016/".$v['image'];
			//$htmlbody.= '[<span class="image2"><a href="'.$tmp.'"/>備份?</a></span>]';
			//$htmlbody.= '[<span class="image"><img class="zoom" src="'.$tmp.'"/></span>]';
			//$tmp='http://crossorigin.me/http://zh150614.athost.biz/img_hot_url.php?door='.$tmp;
			//$htmlbody.= '[<span class="image"><img class="zoom" src="'.$tmp.'"/></span>]';
			$findme='//';
			$mystring=$v['image'];
			$pos = strpos($mystring, $findme);
			$rest = substr($mystring, $pos+strlen($findme));    // 返回 "f"
			//$v['image']=$rest;
			$v['image']='http://'.$rest;

			$findme='//';
			$mystring=$v['image_t'];
			$pos = strpos($mystring, $findme);
			$rest = substr($mystring, $pos+strlen($findme));    // 返回 "f"
			//$v['image_t']=$rest;
			$v['image_t']='http://'.$rest;

			if(1==1){
				$tmp_w="http://web.archive.org/web/2017/";
				//
				if(1==1){
					$tmp=$tmp_w;
					$tmp_a="http://demo.cloudimg.io/cdn/n/n/".$tmp;
				}else{
					$FFF_arr=explode("//",$tmp_w);
					$tmp=$FFF_arr[1];
					//$tmp=.$v['image'];
					$FFF=$cc2%3;
					$tmp_a='https://i'.$FFF.'.wp.com/'.$tmp;
				}
				//
				$tmp_r=$tmp_a.$v['image'];//原圖
				$tmp_t=$tmp_a.$v['image_t'];//縮圖
				$tmp_w2=$tmp_w.$v['image'];
				if( preg_match('/\.webm$/',$v['image'])){
					//$tmp="".$tmp0;
					$htmlbody.= '[<span class="image"><img class="zoom" src="'.$tmp_t.'"/></span>]';//縮圖
					$htmlbody.='<b>webm內容</b>';
					//$htmlbody.= '[<span class="image"><img class="zoom" src="'.$tmp_r.'"/></span>]';//讀取
					$htmlbody.= '[<span class="image"><video controls preload="metadata">您<source src="'.$tmp_w2.'" type="video/webm"></video></span>]';
				}else{
					//$tmp="http://demo.cloudimg.io/cdn/n/n/".$tmp0;
					$htmlbody.= '[<span class="image"><img class="zoom" src="'.$tmp_r.'"/></span>]';
				}
				//$tmp="".$tmp0;
				//$htmlbody.= '[<span class="image2"><a href="'.$tmp.'"/>備份?</a></span>]';
				//$tmp=preg_replace('/\.webm$/', 's.jpg', $tmp);
			}

			
			//$tmp="http://assembly.firesize.com/n/g_none/".$tmp0;

			$htmlbody.= "<br/>\n";
		}
		$htmlbody.= '</div>'."\n";
		//
		$cc1++;//計算推文數量
	}//迴圈//
	$htmlbody=' '.$url."<br/>\n".$board_title2."\n"."[$cc1][$cc2]<br>\n".$htmlbody."<br>\n<br>\n";
	$output_path=output_html($htmlbody);//回傳檔案位置
	$curlpost=curlpost_html($output_path);
}
//有輸入url/
//////

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
	$url_ary[]='http://zh160213.1000space.tk/htm/151225-2244put.php';
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
	$FFF.=print_r($getdata,true);
	$FFF.=print_r($getinfo,true);
	$FFF.=print_r($geterror,true);
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
$(document).ready(function() {
	var time = new Date();
	poi();
});


function poi(){
$("img").after('after');
alert($("img").length);
	//
	var timedown_x = setInterval(function() {
		t=t+1;
		//
		//
		if(t<10){
			timedown_x;
		}else{
			clearInterval(timedown_x);
		}
	}, 911);
	//
}

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
