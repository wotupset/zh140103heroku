<?php

//header("content-Type: application/json; charset=utf-8"); //強制
/*
Heroku Postgres :: Database 設定
Heroku Postgres是屬於PostgreSQL類型的資料庫(簡稱pgsql) 
pgsql指令跟mysql有差異 即使是用php的pdo 從mysql換成pgsql也需要修改sql指令

直接在官網上安裝Heroku Postgres 就不用另外設定composer.json
安裝後
使用下列語法來取得資料庫帳號密碼
$dbopts=parse_url(getenv('DATABASE_URL'));

回傳的$dbopts是陣列格式
Array
(
    [scheme] => postgres
    [host] => 資料庫的主機位置
    [port] => 資料庫的port
    [user] => 資料庫的帳號
    [pass] => 資料庫的密碼
    [path] => 資料庫的path (相當於mysql的Database name)
)

取用陣列內容的方法
$dbhost = $dbopts["host"];
$dbuser = $dbopts["user"];
$dbpass = $dbopts["pass"];
$dbname = ltrim($dbopts["path"],'/'); 

*/

////////
//連接到pgsql
try{
$tmp=getenv('DATABASE_URL');
//print_r($tmp);

$dbopts=parse_url(getenv('DATABASE_URL'));
//print_r($tmp);

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

///////////
//測試pdo的設定
try{
//$db->query("SET TIME ZONE '+8';");//+8
$db->exec("set timezone TO 'Asia/Taipei';");//設定時區
//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//
//$db->setAttribute(PDO::PGSQL_ATTR_INIT_COMMAND, "SET time_zone='+08:00'; ");//不存在PDO::PGSQL_ATTR_INIT_COMMAND 這個方法
echo "PDO::ATTR_SERVER_VERSION => ".$db->getAttribute(constant("PDO::ATTR_SERVER_VERSION")) . "<br/>\n";
echo "PDO::ATTR_SERVER_INFO => ".$db->getAttribute(constant("PDO::ATTR_SERVER_INFO")) . "<br/>\n";
echo "PDO::ATTR_CLIENT_VERSION => ".$db->getAttribute(constant("PDO::ATTR_CLIENT_VERSION")) . "<br/>\n";
//echo "PDO::ATTR_ERRMODE => ".$db->getAttribute(constant("PDO::ATTR_ERRMODE")) . "<br/>\n";
//echo "PDO::ATTR_TIMEOUT => ".$db->getAttribute(constant("PDO::ATTR_TIMEOUT")) . "<br/>\n";
  // Driver does not support this function:
  

//$db->beginTransaction();

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

///////////

///////////
try{
//列出所有設定參數
//$sql="SHOW CHARACTER SET";
echo "<h3>SHOW ALL</h2>";
$sql="SHOW ALL";
  
$stmt=$db->prepare($sql);
$stmt->execute();
echo "<table>";
while ($row = $stmt->fetch() ) {
  print_r('<tr><td>'.$row[0].'</td><td>'.$row[1].'</td></tr>'."\n");
}
echo "</table>";

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息




try{
//建立table
$sql=<<<EOT
CREATE TABLE IF NOT EXISTS int_test 
(
    i01 integer NOT NULL,
    i02 integer NOT NULL,
    i03 integer NOT NULL
)
EOT;
//IF NOT EXISTS
$stmt = $db->prepare($sql);
$stmt->execute();

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息





try{
//列出目前建立的table (不包含系統建立的pg_catalog跟information_schema)
$sql=<<<EOT
SELECT * FROM pg_catalog.pg_tables 
WHERE schemaname != 'pg_catalog' 
AND schemaname != 'information_schema';
EOT;
//AND schemaname != 'information_schema';
$stmt = $db->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch() ) {
  echo "<pre>".print_r($row,true)."</pre>";
}


}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息





/*
Array
(
    [scheme] => postgres
    [host] => 
    [port] => 
    [user] => 
    [pass] => 
    [path] => 
)

*/
?>
