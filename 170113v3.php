<?php
header("content-Type: application/json; charset=utf-8"); //強制

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
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


try{
//列出資料 (指定區間)
$sql=<<<EOT
select * from xopowo123 
WHERE timestamp BETWEEN '2017-01-14' AND '2017-2-14' 
ORDER BY timestamp DESC
limit 100 offset 0
EOT;
//WHERE timestamp BETWEEN '2017-01-15 00:00:01' AND '2017-01-15 23:59:59' 

$stmt = $db->prepare($sql);
$stmt->execute();
$rows_max = $stmt->rowCount();//計數
echo "\n";
echo $rows_max;
$cc=0;
while ($row = $stmt->fetch() ) {
  $cc++;
  if($cc>1000){
    break;
  }else{
    //continue;
  }
    
  echo "\n";
  print_r($row['c01'].'/'.$row['c02'].'/'.$row['c03'].'/'.$row['c04'].'/'.$row['id'].'/'.$row['timestamp']);
}
echo "\n";
echo $cc;

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


try{
//刪除1天前的資料
/////////
date_default_timezone_set("Asia/Taipei");//時區設定
//date_default_timezone_set("UTC");//時區設定
//date_default_timezone_get();
$time=time();
//date("y-m-d h-i-s",$time);
echo "\n";
echo $date01=gmdate("Y-m-d h:i:s",$time);
echo "\n";
//echo $date01=gmdate("Y-m-d h:i:s",strtotime("-20 hour"));
echo $date01=gmdate("Y-m-d h:i:s",strtotime("-1 day"));
/////////////

$sql=<<<EOT
DELETE FROM xopowo123 WHERE timestamp <= '$date01';
EOT;
//WHERE timestamp BETWEEN '2017-01-15 00:00:01' AND '2017-01-15 23:59:59' 
echo "\n";
echo $sql; 
$stmt = $db->prepare($sql);
$stmt->execute();

$count = $stmt->rowCount();
echo "\n";
echo $count;
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


?>
