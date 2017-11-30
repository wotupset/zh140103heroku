<?php

header("content-Type: application/json; charset=utf-8"); //強制
//die('註解');

date_default_timezone_set("Asia/Taipei");//時區設定
//date_default_timezone_set("UTC");//時區設定
$tz=date_default_timezone_get();
echo 'php_timezone='.$tz."\n";
$time  =time();
$time2 =array_sum( explode( ' ' , microtime() ) );
echo 'now='.date("Y-m-d H:i:s",$time)."\n";
echo 'UTC='.gmdate("Y-m-d H:i:s",$time)."\n";


//require_once('170113v4b.php');
//if( $auth != "國" ){exit;}

try{
echo '建立資料庫連線';
echo "\n";

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
echo '連線狀態='.$db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
echo "\n";

//exit;
/*
$myQuery = 'SELECT * FROM users WHERE username = :username';
$params = array(':username' => 'admin');
$db->query($myQuery)->execute($params);
*/

echo 'version_php='.phpversion()."\n";
foreach( $db->query("SHOW VARIABLES LIKE '%version%';") as $k => $v ){
  //print_r($v);
  echo $v[0]."\t".$v[1]."\n";
}
  
//$db->exec("SET TIME ZONE '$tz';");//+8
//$db->exec("SELECT @@global.time_zone;");
//修改成+8時區
foreach( $db->query("SELECT @@global.time_zone, @@session.time_zone ,@@system_time_zone ,CURTIME() ,now();") as $k => $v ){
	//print_r($v);
}
$tmp=$db->query("SET time_zone = '+08:00';");
//print_r($tmp);

foreach( $db->query("SELECT @@global.time_zone, @@session.time_zone ,@@system_time_zone ,CURTIME() ,now() ,TIMEDIFF(NOW(), UTC_TIMESTAMP() );") as $k => $v ){
	print_r($v);
}

echo "\n";
//exit;
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


$table_name='nyaa170415';

try{
//移除table
if(0){
$sql=<<<EOT
DROP TABLE IF EXISTS $table_name
EOT;
//IF NOT EXISTS
$stmt = $db->prepare($sql);
$stmt->execute();
echo 'del table';
}
echo "\n";
//exit;


//建立table
echo '建立table';
echo "\n";

$sql=<<<EOT
CREATE TABLE IF NOT EXISTS $table_name
(
    c01 char(100) NOT NULL,
    c02 text NOT NULL,
    c03 char(100) NOT NULL,
	UNIQUE(c03),
	auto_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	auto_time timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
EOT;
//IF NOT EXISTS
$stmt = $db->prepare($sql);
$stmt->execute();

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

echo "\n";
//exit;


try{
//列出全部table
echo '列出全部table';
echo "\n";

$sql=<<<EOT
SHOW TABLE STATUS
EOT;
//AND schemaname != 'information_schema';
$stmt = $db->prepare($sql);
$stmt->execute();
$cc=0;
while ($row = $stmt->fetch() ) {
	//print_r($row);
	echo $row[0];
	//echo "<>";
	//echo $row['Data_length']."<>".$row['Index_length'];
	//echo $row['Data_length'] + $row['Index_length'];
	echo "\n";
}

echo "\n";
//exit;

//
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

try{
//列出column名稱與格式
echo '列出column名稱與格式';
echo "\n";

$sql=<<<EOT
SHOW COLUMNS FROM $table_name;
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();

$cc=0;
while ($row = $stmt->fetch() ) {
	//print_r($row);
	echo $row[0];
	echo '<>';
	echo $row[1];
	echo "\n";
}


}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

echo "\n";
//exit;



try{
//插入資料
//;
$sql=<<<EOT
INSERT INTO $table_name (c01,c02,c03)
VALUES ( :c01 , :c02 , :c03 );
EOT;
$stmt=$db->prepare($sql);

//uniqid('u',1)

$title='標題';
$text='內文';

$array=array(
  ':c01' => $title, 
  ':c02' => $text,
  ':c03' => md5($text),
);
$stmt->execute($array);
  
}catch(Exception $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

echo "\n";
//exit;

//print_r($db->errorInfo());print_r($db->errorCode());



try{
//列出資料 (全部)
echo '列出資料';
echo "\n";

$sql=<<<EOT
select * from $table_name 
ORDER BY auto_time DESC
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();

$rows_max = $stmt->rowCount();//計數
echo 'rows_max='.$rows_max."\n";
$columns_max = $stmt->columnCount();//計數
echo 'columns_max='.$columns_max."\n";

echo "\n";
//exit;



$cc=0;
while($row = $stmt->fetch() ) {
	$cc++;
	if($cc >10){break;}
	print_r($row);
}
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

echo "\n";
//exit;

////


?>