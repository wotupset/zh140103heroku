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

$dbopts=parse_url(getenv('DATABASE_URL'));
//print_r($dbopts);
$dbhost = $dbopts["host"];
$dbuser = $dbopts["user"];
$dbpass = $dbopts["pass"];
$dbname = ltrim($dbopts["path"],'/');
//pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass
$tmp='';
$tmp.='pgsql:';
$tmp.='dbname='   .$dbname.';';
$tmp.='host='     .$dbhost.';';
$tmp.='user='     .$dbuser.';';
$tmp.='password=' .$dbpass.';';

$db = new PDO($tmp);
if(!$db){die('連線失敗');}


//$db->exec("SET TIME ZONE '$tz';");//+8
$db->exec("set timezone TO '$tz';");//修改成+8時區
foreach( $db->query("show TimeZone") as $k => $v ){
  echo 'pgsql_timezone='.$v[0]."\n";
}
foreach( $db->query("SELECT now()::date, now()::time") as $k => $v ){
  //print_r($v);
  //echo 'pgsql_time='.$v[0]."\n";
}
$stmt=$db->query("SELECT CURRENT_DATE,CURRENT_TIME,CURRENT_TIMESTAMP,LOCALTIMESTAMP");
//print_r($stmt);
//while ($row = $stmt->fetch() ){}
$row = $stmt->fetch();//取回第一筆資料
//print_r($row);
echo 'pgsql_timestamp='.$row['timestamp'];


echo '連線狀態='.$db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
echo "\n";
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


$table_name='nya170415';

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
//建立table
echo '建立table';
echo "\n";

$sql=<<<EOT
CREATE TABLE IF NOT EXISTS $table_name
(
    c01 varchar(100) NOT NULL,
    c02 text UNIQUE NOT NULL,
    c03 text NOT NULL,
    ID  SERIAL PRIMARY KEY,
    timestamp timestamp default current_timestamp
)
EOT;
//IF NOT EXISTS
$stmt = $db->prepare($sql);
$stmt->execute();

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


try{
//列出全部table
echo '列出全部table';
echo "\n";

$sql=<<<EOT
SELECT * FROM pg_catalog.pg_tables 
WHERE schemaname != 'pg_catalog' 
AND schemaname != 'information_schema';
EOT;
//AND schemaname != 'information_schema';
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch() ) {
  echo $row['tablename']."\n";
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

try{
//列出column名稱與格式
echo '列出column名稱與格式';
echo "\n";

$sql=<<<EOT
SELECT *
FROM information_schema.columns
WHERE table_name   = 'nya170415'
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();


$cc=0;
while ($row = $stmt->fetch() ) {
  //print_r($row);
  $cc++;
  echo $cc."\t";
  echo $row['column_name']."\t";
  echo $row['data_type']."\t";
  echo "\n";
}

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息




if(0){
try{
//插入資料
//;
$sql=<<<EOT
INSERT INTO $table_name (c01,c02,c03)
VALUES ( ? , ? , ? );
EOT;
$stmt=$db->prepare($sql);
$array=array( uniqid('u',1),'不用不用',  $time );
$stmt->execute($array);
  
}catch(Exception $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

}

try{
//插入資料
echo '插入資料';
echo "\n";

//;
$sql=<<<EOT
INSERT INTO $table_name (c01,c02,c03)
VALUES ( :c01 , :c02 , :c03 );
EOT;
$stmt=$db->prepare($sql);

//bindParam的第二個參數不能放字串
//$stmt->bindParam(':c01', $array[':c01']);
//$stmt->bindParam(':c02', $array[':c02']);
//$stmt->bindParam(':c03', $array[':c03']);
$array=array(
  ':c01' => uniqid('u',1), 
  ':c02' => '肏肏肏肏肏肏肏肏',
  ':c03' => base64_encode($time2) ,
);
  
$stmt->execute($array);
  




  
}catch(Exception $e){
  print_r($db->errorInfo());
  print_r($db->errorCode());
  $chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
//列出資料 (全部)
echo '列出資料';
echo "\n";

$sql=<<<EOT
select * from $table_name 
ORDER BY timestamp DESC
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();

$rows_max = $stmt->rowCount();//計數
echo 'rows_max='.$rows_max."\n";
$columns_max = $stmt->columnCount();//計數
echo 'columns_max='.$columns_max."\n";

if(1){
  //
$cc=0;
while ($row = $stmt->fetch() ) {
  $cc++;
  if($cc>1000){break;}
  echo $row['c01']."\t".$row['c02']."\t".$row['c03']."\t".$row['c04']."\t".$row['id']."\t".$row['timestamp']."\n";
  echo pg_unescape_bytea($row['c03'])."\n";
}
  //
}  
  

  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

////


?>
