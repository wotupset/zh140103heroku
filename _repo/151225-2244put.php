<?php
extract($_POST,EXTR_SKIP);
extract($_GET,EXTR_SKIP);
extract($_COOKIE,EXTR_SKIP);
extract($_FILES,EXTR_SKIP);
//
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//
//$url_p2=pathinfo($phpself);
//$url_p2['filename'];

//var_dump($_COOKIE);
//var_dump($_FILES);

if(isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0){
	//ok
}else{
	if( !isset($_FILES['upfile']) ){die('沒有上傳檔案');}
	if( $_FILES['upfile']['error']!=0 ){die('上傳發生錯誤');}
	die();
}

if($pass != 'xopowo'){die('?pass');}

if(0){
	if(!$md5){die('?$md5');}
	$md5_new=md5_file($_FILES['upfile']['tmp_name']);
	if($md5 != $md5_new){die('?md5');}
}

$type='htm';


if(1){
	//
	date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
	$time=''.time();
	$ym=date("ym",$time);
	//
	$dir_path='_'.$ym.'/';
	if(!is_dir($dir_path)){mkdir($dir_path, 0777);}
	if(!is_dir($dir_path)){die("建立資料夾失敗");}
	//if(move_uploaded_file($_FILES['upl']['tmp_name'], $dir_path.'/'.$_FILES['upl']['name'])){
	//md5_file($_FILES['upl']['tmp_name'])
	$dir_index=$dir_path."index.php";
	if(!is_file($dir_index)){copy("index.php", $dir_index);}
	if(!is_file($dir_index)){die("複製檔案失敗");}

	//
	$tmp=$dir_path.''.uniqid('1',1).'.'.$type;
	move_uploaded_file($_FILES['upfile']['tmp_name'], $tmp);
	//file_put_contents($tmp, $ctx);
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