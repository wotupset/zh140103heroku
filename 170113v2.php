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
//建立table
$sql=<<<EOT
CREATE TABLE IF NOT EXISTS poi123 
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
  echo "\n";
  print_r($row['tablename']);
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
//列出table的columns名稱
$sql=<<<EOT
SELECT *
FROM information_schema.columns
WHERE table_schema = 'public'
  AND table_name   = 'poi123'
EOT;
//IF NOT EXISTS
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch() ) {
  echo "\n";
  print_r($row['column_name'].' => '.$row['data_type'].' '.$row['character_maximum_length']);
}
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

try{
//移除table
$sql=<<<EOT
DROP TABLE IF EXISTS poi123
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
  echo "\n";
  print_r($row['tablename']);
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

/*
[c01] => c01
    [0] => c01
    [c02] => c02
    [1] => c02
    [c03] => c03
    [2] => c03
    [c04] => 4
    [3] => 4
    [id] => 1
    [4] => 1
    [timestamp] => 2017-01-14 04:42:30.558034
    [5] => 2017-01-14 04:42:30.558034

*/
?>
