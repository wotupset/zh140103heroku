<?php
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//
//$url_p2=pathinfo($phpself);
//$url_p2['filename'];

if(!$type){die('?type');}
//$_POST['ctx']='';
//print_r($_POST);
//Array ( [type] => jpg [ctx] => [ctx_height] => 47896 [md5] => 1129cf7ea13f4692ce9cff898fbd0ab9 [url] => http://homu.komica.org/00/src/1460792505725.png ) 
echo $md5.' '.md5($ctx);
if($md5 != md5($ctx)){die('?md5');}

$FFF= $ctx;
$FFF= str_replace("_", "+", $FFF);
$FFF= str_replace("-", "/", $FFF);//迴避網址字元
$FFF= str_replace("`", "=", $FFF);//迴避網址字元
$ctx= $FFF;

$ctx=base64_decode($ctx);


if(0){
	$tmp=uniqid('1',1).'.'.$type;
	
	if(is_file($tmp)){
		unlink($tmp);
		if(is_file($tmp)){die("刪除檔案失敗");}
	}//舊檔案有存在就移除

	file_put_contents($tmp, $ctx);
	if(!is_file($tmp)){die("建立檔案失敗");}
}

if(1){
	//
	date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
	$time=time();
	$ym=date("ym",$time);
	//
	$dir_path='_'.$ym.'/';
	if(!is_dir($dir_path)){mkdir($dir_path, 0777);}
	if(!is_dir($dir_path)){die("建立資料夾失敗");}
	//if(move_uploaded_file($_FILES['upl']['tmp_name'], $dir_path.'/'.$_FILES['upl']['name'])){
	//md5_file($_FILES['upl']['tmp_name'])
	$img_count=$dir_path."index.htm";
	if(!is_file($img_count)){copy("150403.htm", $img_count);}
	if(!is_file($img_count)){die("複製檔案失敗");}
	$img_count=$dir_path."150403.php";
	if(!is_file($img_count)){copy("150403.php", $img_count);}
	if(!is_file($img_count)){die("複製檔案失敗");}
	//
	$tmp=$dir_path.uniqid('1',1).'.'.$type;
	file_put_contents($tmp, $ctx);
	if(is_file($tmp)){
		echo "ok";
	}else{
		header('The goggles, they do nawtink!', true, 410);//gone
		echo 'no';
	}
	//echo 'ok';
}

exit;

?>