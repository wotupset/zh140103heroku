<?php
header("content-Type: application/json; charset=utf-8"); //強制

date_default_timezone_set("Asia/Taipei");//時區設定
//date_default_timezone_set("UTC");//時區設定
$tz=date_default_timezone_get();
echo 'php_timezone='.$tz."\n";
$time=time();
echo 'now='.date("Y-m-d H:i:s",$time)."\n";
echo 'UTC='.gmdate("Y-m-d H:i:s",$time)."\n";


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
//建立table
$sql=<<<EOT
CREATE TABLE IF NOT EXISTS xopowo123
(
    c01 varchar(200) NOT NULL,
    c02 varchar NOT NULL,
    c03 text NOT NULL,
    c04 integer NOT NULL,
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


try{
//插入資料
$sql=<<<EOT
INSERT INTO xopowo123 (c01,c02,c03,c04)
VALUES ( 'c01語りたい', 'c02字幕組', 'c03ぴょんぴょん', $time );
EOT;

$stmt = $db->prepare($sql);
$stmt->execute();

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息





try{
//列出資料 (全部)
$sql=<<<EOT
select * from xopowo123 
ORDER BY timestamp DESC
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();
echo $rows_max = $stmt->rowCount();//計數

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
  
$date01=date("Y-m-d H:i:s",strtotime("-5 min"));
$date02=date("Y-m-d H:i:s",strtotime("-5 hour"));
//echo $date02=gmdate("Y-m-d h:i:s",strtotime("-5 day"));
//echo $date02=gmdate("Y-m-d h:i:s",strtotime("-5 month"));
//echo $date02=gmdate("Y-m-d h:i:s",strtotime("-5 year"));

  
//列出資料 (指定區間)
$sql=<<<EOT
select * from xopowo123 
WHERE timestamp BETWEEN '$date02' AND '$date01' 
ORDER BY timestamp DESC
limit 100 offset 0
EOT;
//WHERE timestamp BETWEEN '2017-01-15 00:00:01' AND '2017-01-15 23:59:59' 
echo $sql."\n"; 

$stmt = $db->prepare($sql);
$stmt->execute();
$rows_max = $stmt->rowCount();//計數
  
echo $rows_max."\n";

$cc=0;
while ($row = $stmt->fetch() ) {
  $cc++;
  if($cc>1000){break;}
  echo $row['c01']."\t".$row['c02']."\t".$row['c03']."\t".$row['c04']."\t".$row['id']."\t".$row['timestamp']."\n";
}
echo $cc."\n";

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


try{
//刪除5天前的資料
/////////
//echo $date01=gmdate("Y-m-d h:i:s",strtotime("-20 hour"));
$date01=date("Y-m-d H:i:s",strtotime("-5 day"));
/////////////

$sql=<<<EOT
DELETE FROM xopowo123 WHERE timestamp <= '$date01';
EOT;
//WHERE timestamp BETWEEN '2017-01-15 00:00:01' AND '2017-01-15 23:59:59' 
echo $sql."\n"; 
$stmt = $db->prepare($sql);
$stmt->execute();

$count = $stmt->rowCount();
echo '刪除='.$count."\n";
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


?>
