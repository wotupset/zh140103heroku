<?php

header("content-Type: application/json; charset=utf-8"); //強制
date_default_timezone_set("Asia/Taipei");//時區設定
//date_default_timezone_set("UTC");//時區設定
$tz=date_default_timezone_get();
echo 'php_timezone='.$tz."\n";
$time=time();
echo 'now='.date("Y-m-d H:i:s",$time)."\n";
echo 'UTC='.gmdate("Y-m-d H:i:s",$time)."\n";


//require_once('170113v4b.php');
//if( $auth != "國" ){exit;}

try{
$dbopts=parse_url(getenv('DATABASE_URL'));
//print_r($dbopts);
$dbhost = $dbopts["host"];
$dbuser = $dbopts["user"];
$dbpass = $dbopts["pass"];
$dbname = ltrim($dbopts["path"],'/');
  
$db = new PDO('pgsql:'.
              'dbname='.$dbname.';'.
              'host='.$dbhost.';'.
              'user='.$dbuser.';'.
              'password='.$dbpass.';'
              );
//$db->exec("SET TIME ZONE '$tz';");//+8
$db->exec("set timezone TO '$tz';");//+8
  
foreach( $db->query("show TimeZone") as $k => $v ){
  echo 'pgsql_timezone='.$v[0]."\n";
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
if(0){
//移除table
$sql=<<<EOT
DROP TABLE IF EXISTS nya123
EOT;
//IF NOT EXISTS
$stmt = $db->prepare($sql);
$stmt->execute();

}
//建立table
$sql=<<<EOT
CREATE TABLE IF NOT EXISTS nya123
(
    c01 varchar(100) NOT NULL,
    c02 text NOT NULL,
    c03 integer NOT NULL,
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



if(0){
try{
//插入資料
//;
$sql=<<<EOT
INSERT INTO nya123 (c01,c02,c03)
VALUES ( ? , ? , ? );
EOT;
$stmt=$db->prepare($sql);
$array=array( uniqid('u',1),'不用不用',  $time );
$stmt->execute($array);
  
}catch(Exception $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

}

try{
//插入資料
//;
$sql=<<<EOT
INSERT INTO nya123 (c01,c02,c03)
VALUES ( :c01 , :c02 , :c03 );
EOT;
$stmt=$db->prepare($sql);

//bindParam的第二個參數不能放字串
//$stmt->bindParam(':c01', $array[':c01']);
//$stmt->bindParam(':c02', $array[':c02']);
//$stmt->bindParam(':c03', $array[':c03']);
$array=array(
  ':c01' => uniqid('u',1), 
  ':c02' => '有害氣體不會傷害環境和人員',
  ':c03' => $time,
);
  
$stmt->execute($array);

  
}catch(Exception $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
//列出資料 (全部)
$sql=<<<EOT
select * from nya123 
ORDER BY timestamp DESC
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();
$rows_max = $stmt->rowCount();//計數
echo 'ALL='.$rows_max."\n";
if(1){
  //
$cc=0;
while ($row = $stmt->fetch() ) {
  $cc++;
  if($cc>1000){break;}
  echo $row['c01']."\t".$row['c02']."\t".$row['c03']."\t".$row['c04']."\t".$row['id']."\t".$row['timestamp']."\n";
}
  //
}  
  

  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

?>
