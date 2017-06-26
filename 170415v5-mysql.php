<?php
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


try{
//連結
require_once('170415v5__user.php');

$tmp=function(){
$dbhost = 'localhost';
$dbuser = 'id1568431_http';
$dbpass = 'a1a1a1a1';
$dbname = 'id1568431_http';
};


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
//$db->query("SET NAMES UTF8");
//print_r($db->errorInfo());
//$db->query("ALTER table `nyaa170415` CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci';");//
//print_r($db->errorInfo());

//if(1){}
foreach( $db->query("SELECT @@global.time_zone, @@session.time_zone ,@@system_time_zone ,CURTIME() ,now();") as $k => $v ){
	//print_r($v);
}
foreach( $db->query("show variables like 'character%';") as $k => $v ){
	//print_r($v);
}
foreach( $db->query("SHOW CHARACTER SET;") as $k => $v ){
	//print_r($v);
}

//show variables like '%time_zone%';

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤(連結):".$chk);}//錯誤訊息



try{
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


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
//建立table
$sql=<<<EOT
CREATE TABLE IF NOT EXISTS $table_name
(
    c01 char(100) NOT NULL,
    c02 text NOT NULL,
    c03 char(100) NOT NULL,
	UNIQUE(c03),
	auto_id INT NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	auto_time timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    timestamp timestamp default current_timestamp
)
EOT;
//UNIQUE(c02),
//IF NOT EXISTS
$stmt = $db->prepare($sql);
$stmt->execute();
//print_r($stmt->errorInfo());

}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤(建立table):".$chk);}//錯誤訊息


try{
//列出全部table
$sql=<<<EOT
SHOW TABLE STATUS
EOT;
$stmt = $db->prepare($sql);
$stmt->execute();
//print_r($stmt->errorInfo());
//
$cc=0;
while ($row = $stmt->fetch() ) {
	//print_r($row);
	//print_r($row['Name']);
	if($row['Name'] == $table_name ){
		$cc=$cc+1;
	}
}
if($cc>0){
  //echo '成功';
}else{
  echo '失敗';exit;
}
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤(列出全部table):".$chk);}//錯誤訊息

//exit;


if(count($_POST)>0){
$title =$_POST['input_title'];
$title =strip_tags($title);

$text  =$_POST['input_text'];
//$text  =preg_replace("/\r\n/","\n",$text);
//$text  =preg_replace("/\n/","<br/>\n",$text);
//$text  =nl2br($text);
//$text  =strip_tags($text,'<br>');
//$text  =strip_tags($text);
//htmlentities //轉換為HTML實體(包含中文字)
$text  =htmlspecialchars($text);////轉換為HTML實體

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
  ':c02' => base64_encode($text),
  ':c03' => md5($text),
);
//base64_encode($time2)
  
$stmt->execute($array);
//print_r($stmt->errorInfo());
if($stmt->errorInfo() ==''){echo 'ok';}
	
}catch(Exception $e){
//$chk=$e->getMessage();print_r("try-catch錯誤(插入資料):".$chk);
}//錯誤訊息

$refresh_url='';
$refresh_url.=$phpself;
if(strlen($title)){
$refresh_url.='?title='.$title;
}
header("refresh:4; url=$refresh_url");
$tmp='ok';
//<meta http-equiv="refresh" content="2; url=$t_url" />
echo <<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="refresh" content="0; url=$refresh_url" />
</head>
<body>
$tmp
</body>
</html>
EOT;
exit;

}//if

//exit;


ob_start();

try{
//列出資料 (全部)
$page=$_GET['page'];
$page=floor($page);
$title=$_GET['title'];

//echo $page;
//
$sql='';
$sql.=<<<EOT
select * from $table_name 
EOT;

if(strlen($title)){
$sql.=<<<EOT
WHERE c01 = '$title'
EOT;
}

$sql.=<<<EOT
ORDER BY timestamp DESC
EOT;
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();
//print_r($stmt->errorInfo());


$rows_max = $stmt->rowCount();//計數
//echo '<h3>log數='.$rows_max."</h3>\n";
$pagelog=10;
if($page > floor($rows_max/$pagelog) ){
  $page=floor($rows_max/$pagelog);//floor//ceil
}
//echo $page;
$pagelist='';
$pagelist.='<span style="font-size:1.5em;display: block;font-weight: bold;font-family: monospace;">';
//$pagelist.='<h3 style="font-family: monospace;">';
//$pagelist.='<code>';
$tmp='';
if(strlen($title)){
$tmp.='&title='.$title;
}
for($x=0;$x*$pagelog < $rows_max ;$x++){
  //$pagelist.= '<a href="'.$phpself.'?page='.$x.'">#['.$x.']</a>'."\n";
  $pagelist.= '<a href="'.$phpself.'?page='.$x.$tmp.'">';
  if($page==$x){$pagelist.='#';}else{$pagelist.='*';}
  $pagelist.= '['.$x.']</a>'."\n";
}
$pagelist.='('.$rows_max.')';
$pagelist.='</span>';
echo $pagelist;
//$datalist = $stmt->fetchAll();

if(1){
  //
$cc=0;
//foreach($datalist as $row){
while ($row = $stmt->fetch() ) {
  $cc++;
  //if($cc>10){echo 'break';break;}
  if( ($page)*$pagelog >= $cc || $cc > ($page+1)*$pagelog ){
    //echo '#'.$cc.'continue'."<br/>\n";
    //echo '<h3>#cc='.$cc."</h3>\n";
    continue;
    //break;
  }
	
  //echo $row['c01']."\t".$row['c02']."\t".$row['c03']."\t".$row['c04']."\t".$row['id']."\t".$row['timestamp']."\n"
  echo '<div class="box">';
  echo '<div class="title"><h3>#<sub>'.$cc.'</sub>#<sup>'.$row['auto_id'].'</sup>#'.$row['c01'].'</h3></div>';
//mysql的utf8只支援到unicode5.0
$tmp=$row['c02'];
$tmp=base64_decode($tmp);
$tmp=nl2br($tmp);
$tmp=preg_replace('/\s/','',$tmp);
  echo '<div class="text">'.$tmp.'</div>';
  //echo '<pre>'.$row['c03'].'</pre>';//base64_decode($row['c03']).
$tmp=$row['timestamp'];//可讀時間
$tmp=strtotime($tmp)+8*3600;//時間戳,修正時差
//$tmp=strtotime("+8 hours", $tmp );
$tmp=date('Y/m/d H:i:s', $tmp );
  echo '<div class="date" title="時間"><h4>'.$tmp.'</h4></div>';
  //echo $row['timestamp'];
  //
  echo '</div>';
}
  //
}  
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤(列出資料):".$chk);}//錯誤訊息

$out = ob_get_clean();//ob_start();



echo html_body($out);
exit;
///////////
function html_body($x){
	//$webm_count  =$x[5];
$phpself=$GLOBALS['phpself'];
$title=$_GET['title'];
	//
$action='';
$action.=$phpself;
if(strlen($title)){
$action.='?title='.$title;
}


$html_inputbox=<<<EOT
<form id='form01' enctype="multipart/form-data" action='$action' method="post" onsubmit="return check2();">
<input id="input_title" name="input_title" type="text" size="20" value="$title"><br/>
<textarea id="input_text" name="input_text" cols="48" rows="4" maxlength="" style="width: 400px; height: 80px;"></textarea>
<input id="sendbtn" name="sendbtn" type="button" value="送出" onclick='check();'>
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
<script>
function check(){//onclick
	document.getElementById("sendbtn").value="稍後";
	document.getElementById("form01").onsubmit();
}
function check2(){//onsubmit
	document.getElementById("sendbtn").value='submit';
		//
	tmp=document.getElementById("input_title").value;
	//alert(tmp);
	document.getElementById("form01").submit();
}
</script>

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