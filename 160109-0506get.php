<?php
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$query_string=$_SERVER['QUERY_STRING'];
if(!isset($url)){
	$url=$query_string;
}
if(!preg_match("/^http/",$url)){die('?http only');} //網址有參數
//
$tmp='../curl_getinfo.php';
if(!file_exists($tmp)){die('file_exists');}//file_exists
require_once($tmp);
//
if(1){
	$x        = curl_FFF($url);
	$getdata  = $x[0];
	$getinfo  = $x[1];
	$geterror = $x[2];
	if($getinfo['http_code'] != 200){die('!http_code='.$getinfo['http_code']);}
	if($geterror > 0){die('!curl errors='.$geterror);}
	//
	if(1){
		$FFF='';
		//$FFF.=print_r($getdata,true);
		//$FFF.=print_r($getinfo,true);
		//$FFF.=print_r($geterror,true);
		//echo '<pre>'.$FFF.'</pre>';
		$tmp=pathinfo($phpself);
		//echo $getinfo['content_type'];exit;
		$type=poi2($getinfo['content_type']);//把檔案類型代碼 換成英文副檔名
		//echo $type;exit;
		$tmp=$tmp['filename'].'.'.$type;
		if(is_file($tmp)){
			unlink($tmp);
			if(is_file($tmp)){die("刪除檔案失敗");}
		}//舊檔案有存在就移除
		file_put_contents($tmp, $getdata);
		if(!is_file($tmp)){die("檔案".$tmp);}
		if(1){
			$fp = fopen($tmp,"r");
			flock($fp,1);//鎖定檔案
			//fclose($fp);//跑完自動關閉
		}
		//
		////


	}
	
}

//$ctx=file_get_contents("favicon.png");
//$type='png';
if(!$type){die('?type');}
//if(!$ctx){die('?ctx');}

$myvars = array();
$myvars['type'] = $type;

if(1){
	$ctx=$getdata;
	$ctx=base64_encode($ctx);
	$FFF= $ctx;
	$FFF= str_replace("+", "_", $FFF);
	$FFF= str_replace("/", "-", $FFF);//迴避網址字元
	$FFF= str_replace("=", "`", $FFF);//迴避網址字元
	$ctx= $FFF;
	$md5=md5($ctx);
	//$myvars['ctx']= $ctx;
}

//$upl='@'.realpath($tmp);
//$args['file'] = new CurlFile('filename.png', 'image/png', 'filename.png');
//echo $upl;
if(class_exists('CurlFile')) {
	$myvars['upl']= new CurlFile($tmp, $type_org, $tmp);
}else{
	$myvars['upl']= '@'.realpath($tmp);
}
//$myvars['upl']= $upl;
$myvars['md5']= $md5;

//echo $md5;

//$pp='?'.$myvars;
$url_ary = array();

$url_ary[] = 'http://dottir.hostingsiteforfree.com/swfup/01/upload.php';
$url_ary[] = 'http://zh141104-0349.allalla.com/swfup/01/upload.php';
$url_ary[] = 'http://zh141105-1120.pixub.com/swfup/01/upload.php';
$url_ary[] = 'http://zh141107-1535.fulba.com/swfup/01/upload.php';

$url_ary[] = 'http://zh150310.grn.cc/swfup/01/upload.php';
$url_ary[] = 'http://zh151018.acoxi.com/swfup/01/upload.php';
$url_ary[] = 'http://zh160109.grn.cc/swfup/01/upload.php';
$url_ary[] = 'http://zh150806.allalla.com/swfup/01/upload.php';

$url_ary[] = 'http://gitgud.1gh.in/swfup/01/upload.php';
$url_ary[] = 'http://zh150614.thats.im/poi/swfup/01/upload.php';
$url_ary[] = 'http://zh151112.x20.in/swfup/01/upload.php';

$url_ary[] = 'http://zh150806.id.ai/swfup/01/upload.php';
$url_ary[] = 'http://zh151020.xx.tn/swfup/01/upload.php';
$url_ary[] = 'http://zh151112.uk.ht/swfup/01/upload.php';

$url_ary[] = 'http://pawpad.yzi.me/swfup/01/upload.php';
$url_ary[] = 'http://gnar.3eeweb.com/swfup/01/upload.php';
$url_ary[] = 'http://faggat.honor.es/swfup/01/upload.php';
$url_ary[] = 'http://zh151227.2fh.co/swfup/01/upload.php';

//$url_ary[] = '';

if(0){
	//$FFF=rand(1,6);
	//$FFF=1;
	$FFF=array_rand($url_ary,1);
	$url=$url_ary[$FFF];
}else{
	if($host){
		$url='http://'.$host.'/swfup/01/upload.php';
	}else{
		$url='http://zh150806.twomini.com/swfup/01/upload.php';
	}
}

$useragent='Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36';

$ch = curl_init();//初始化
if(!$ch){die('[x]curl初始化失敗');}

curl_setopt($ch, CURLOPT_URL,            $url);//網址
curl_setopt($ch, CURLOPT_POST,           1);//post模式
curl_setopt($ch, CURLOPT_POSTFIELDS,     $myvars);//參數
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);//跟随重定向页面//safe_mode = Off 
curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容

$getdata  = curl_exec($ch);//抓取URL並把它傳遞給變數
$getinfo  = curl_getinfo($ch);//結果資訊
$geterror = curl_errno($ch);
curl_close($ch);

$FFF='';
$FFF.=print_r($getdata,true);
$FFF.=print_r($getinfo,true);
$FFF.=print_r($geterror,true);
//echo '<pre>'.$FFF.'</pre>';


if($getinfo['http_code'] != 200){die('!http_code='.$getinfo['http_code']);}else{
	$tmp='./index.gif';
	if(0){
		header('Location:'.$tmp);
	}else{
		if(!file_exists($tmp)){die('file_exists'.$tmp);}//file_exists
		//$filetype = mime_content_type($tmp);//確認文件類型
		$FFF=getimagesize($tmp);
		$filetype=$FFF['mime'];
		Header('Content-type:'.$filetype.'');//指定文件類型
		//header('Content-Disposition: filename="'.$filename.'"');//
		//echo $contents;
		readfile($tmp);
	}
}


exit;
//function
function poi2($x){
	$tmp='';
	//
	if(preg_match("/image/",$x)){
		//echo 'w';
		preg_match("/image\/([\w]+)/",$x,$m);
		//print_r($m[1]);
		switch($m[1]){
			case 'gif':
				$tmp='gif';
			break;
			case 'jpeg':
			case 'jpg':
				$tmp='jpg';
			break;
			case 'png':
				$tmp='png';
			break;
			default:
				$tmp=0;
			break;
		}
		//print_r($tmp);
	} //網址有參數
	//
	$x=$tmp;
	return $x;
}

function poi($x){
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
	$x=$tmp;
	return $x;
}
?>