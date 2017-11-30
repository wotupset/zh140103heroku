<?php
echo '需要時再啟用';
exit;

//header("content-Type: application/json; charset=utf-8"); //強制
date_default_timezone_set("Asia/Taipei");//時區設定
//date_default_timezone_set("UTC");//時區設定
$tz=date_default_timezone_get();
//echo 'php_timezone='.$tz."\n";
$time  =time();
$time2 =array_sum( explode( ' ' , microtime() ) );
$php_info=pathinfo($_SERVER["PHP_SELF"]);//被執行的文件檔名
//$php_dir=$php_info['dirname'];//
$phpself=$php_info['basename'];
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$query_string=$_SERVER['QUERY_STRING'];

//echo 'now='.date("Y-m-d H:i:s",$time)."\n";
//echo 'UTC='.gmdate("Y-m-d H:i:s",$time)."\n";
//print_r($_POST);

//require_once('170113v4b.php');
//if( $auth != "國" ){exit;}

try{
require_once('170415v5__user.php');

$db_config['dsn'] = "mysql:host=$dbhost;dbname=$dbname;";//charset=utf8
$db_config['user'] = $dbuser;
$db_config['password'] = $dbpass;
$db_config['options'] = array();
//array(PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES 'utf8' COLLATE 'utf8_general_ci';")
$db = new PDO(
	$db_config['dsn'],
	$db_config['user'],
	$db_config['password'],
	$db_config['options']
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8' COLLATE 'utf8_unicode_ci';");
//echo '連線狀態='.$db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
//echo "\n";


}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);exit;}//錯誤訊息

//exit;


try{
  //nothing
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


$table_name='nyaa170415';

//刪除table
//建立table
//在170415v0.php中


try{
//列出全部table
$sql=<<<EOT
SHOW TABLE STATUS
EOT;
//AND schemaname != 'information_schema';
$stmt = $db->prepare($sql);
$stmt->execute();
//
$cc=0;
while ($row = $stmt->fetch() ) {
  if($row[0] == $table_name ){
    $cc=$cc+1;
  }
}
if($cc>0){
  //echo '成功';//
}else{
  echo '失敗';
  exit;
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

//echo "\n";
//exit;

//print_r($_POST);



ob_start();

try{
//列出資料 (全部)
//$page=$_GET['page'];
//echo $page;
//
$sql=<<<EOT
select * from $table_name 
ORDER BY auto_time DESC
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();
$rows_max = $stmt->rowCount();//計數
//echo '<h3>log數='.$rows_max."</h3>\n";
//echo "\n";
//exit;
	
//$filename = date("Y-m-d") . ".csv";
$fp = fopen('php://output', 'w'); // 寫入 php://memory

$cc=0;
while($row = $stmt->fetch() ) {
	$cc++;
	//echo "\n";
	//print_r($row);
	if($cc<10){ //測試時 只取前10筆
		$row2=array();
		$row2=array($row[0],$row[1],$row[2],$row[3],$row[4]);
		fputcsv($fp, $row2);
	}
}//while
fseek($fp, 0);
fpassthru($fp);
fclose($fp);

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

$out = ob_get_clean();
//print_r($out);
//exit;
$filename = date("Y-m-d") . ".csv";
header('Content-Type: application/csv');
header('Content-Disposition: attachement; filename="' . $filename . '"');
echo $out;

exit;
///////////
/*
Array
(
    [c01] => 
    [0] => 
    [c02] => [新]Wake Up, Girls! 新章 #1 私たち、Wake Up, Girls!でーす
    [1] => [新]Wake Up, Girls! 新章 #1 私たち、Wake Up, Girls!でーす
    [c03] => MTUwNzY1MDExOS4wNzE0
    [2] => MTUwNzY1MDExOS4wNzE0
    [id] => 482
    [3] => 482
    [timestamp] => 2017-10-10 23:41:59.097848
    [4] => 2017-10-10 23:41:59.097848
)
*/
?>