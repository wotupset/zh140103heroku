<?php

//header("content-Type: application/json; charset=utf-8"); //強制
date_default_timezone_set("Asia/Taipei");//時區設定
//date_default_timezone_set("UTC");//時區設定
$tz=date_default_timezone_get();
//echo 'php_timezone='.$tz."\n";
$time  =time();
$time2 =array_sum( explode( ' ' , microtime() ) );

//echo 'now='.date("Y-m-d H:i:s",$time)."\n";
//echo 'UTC='.gmdate("Y-m-d H:i:s",$time)."\n";
//print_r($_POST);

//require_once('170113v4b.php');
//if( $auth != "國" ){exit;}

try{
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
//$db->exec("SET TIME ZONE '$tz';");//+8
$db->exec("set timezone TO '$tz';");//+8
  
foreach( $db->query("show TimeZone") as $k => $v ){
  //echo 'pgsql_timezone='.$v[0]."\n";
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息



try{
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


$table_name='nya170415';

//刪除table
//建立table
//在170415v0.php中


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
//
$cc=0;
while ($row = $stmt->fetch() ) {
  if($row['tablename'] == $table_name ){
    $cc=$cc+1;
  }
}
if($cc>0){
  //echo '成功';
}else{
  echo '失敗';
  exit;
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息




if(count($_POST)>0){
$title =$_POST['input_title'];
$title =strip_tags($title);

$text  =$_POST['input_text'];
//$text  =preg_replace("/\r\n/","\n",$text);
//$text  =preg_replace("/\n/","<br/>\n",$text);
//$text  =nl2br($text);
//$text  =strip_tags($text,'<br>');
$text  =strip_tags($text);

try{
//插入資料
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
//uniqid('u',1)
$array=array(
  ':c01' => $title, 
  ':c02' => $text,
  ':c03' => base64_encode($time2) ,
);
  
$stmt->execute($array);


	
	
}catch(Exception $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

}



ob_start();

try{
//列出資料 (全部)
$sql=<<<EOT
select * from $table_name 
ORDER BY timestamp DESC
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();
$rows_max = $stmt->rowCount();//計數
echo '<h3>log數='.$rows_max."</h3>\n";

//$datalist = $stmt->fetchAll();

if(1){
  //
$cc=0;
//foreach($datalist as $row){
while ($row = $stmt->fetch() ) {
  $cc++;
  if($cc>100){
	  echo 'break';
	  break;
  }
  //echo $row['c01']."\t".$row['c02']."\t".$row['c03']."\t".$row['c04']."\t".$row['id']."\t".$row['timestamp']."\n"
  echo '<div class="box">';
  echo '<div class="title"><h3>#<sub>'.$cc.'</sub>#<sup>'.$row['id'].'</sup>#'.$row['c01'].'</h3></div>';
  echo '<div class="text">'.nl2br($row['c02']).'</div>';
  //echo '<pre>'.$row['c03'].'</pre>';//base64_decode($row['c03']).
  echo '<div class="date" title="'.base64_decode($row['c03']).'"><h4>'.date('Y/m/d H:i:s',strtotime($row['timestamp'])).'</h4></div>';
  echo '</div>';
}
  //
}  
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

$out = ob_get_clean();

echo html_body($out);
exit;
///////////
function html_body($x){
	//$webm_count  =$x[5];
	//
$html_inputbox=<<<EOT
<form id='form01' enctype="multipart/form-data" action='$phpself' method="post" onsubmit="">
<input type="text" name="input_title" size="20" value=""><br/>
<textarea maxlength="" name="input_text" cols="48" rows="4" style="width: 400px; height: 80px;"></textarea>
<input type="submit" name="sendbtn" value="送出">
</form>
EOT;
//
$x=<<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
div.box {
border:1px solid blue;
padding-left:10px;
background-color:#bdbdbd;
}
	
</style>
</head>
<body>
$html_inputbox
$x
</body>	
</html>
EOT;
	//	
	return $x;
}

?>
