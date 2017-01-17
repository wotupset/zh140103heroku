<?php
header('Content-type: text/html; charset=utf-8');
//clearstatcache();
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$phppath_rel=dirname('.'.$_SERVER["PHP_SELF"]);//被執行的文件檔名 所在資料夾
$phppath=dirname($_SERVER["SCRIPT_FILENAME"]);//絕對路徑
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."";
$phphost=$_SERVER["SERVER_NAME"];
$phpext = pathinfo($_SERVER["SCRIPT_FILENAME"], PATHINFO_EXTENSION);
session_save_path($phppath);//needs to be called before session_start()
session_start(); //session

echo '<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head><body onkeypress="check(event)">';

echo '名稱'.$phpself.'';
echo "<br/>\n";
echo '副檔名'.$phpext.'';
echo "<br/>\n";
echo '權限';
echo is_writeable($phpself)?'w':'F';
echo is_readable($phpself)?'r':'F';
echo file_exists($phpself)?'x':'F';
echo @chmod($phpself,0666)?'c':'F'; //@=不顯示錯誤
echo "<br/>\n";
echo '大小';
echo filesize($phpself).'byte(s)';
echo "<br/>\n";
echo "<br/>\n";

echo '相對路徑'.$phppath_rel.'';
echo "<br/>\n";
echo '絕對路徑'.$phppath.'';
echo "<br/>\n";
echo '網路連結'.$phplink.'';
echo "<br/>\n";
echo '主機'.$phphost.'';
echo "<br/>\n";
echo '根目錄'.$_SERVER['DOCUMENT_ROOT'].'';
echo "<br/>\n";
echo '主機空間狀態 [ free / total ] ';
echo "<br/>\n";
$tmp='';
if(disk_free_space($phppath)){
echo number_format((disk_free_space($phppath) / (1024*1024*1024)),2).'G';
}else{echo 'F';}
echo " / ";
if(disk_total_space($phppath)){
echo number_format((disk_total_space($phppath) / (1024*1024*1024)),2).'G';
}else{echo 'F';}
echo "<br/>\n";

echo "<br/>\n";
echo '檔案擁有者'.fileowner($phpself);
echo "<br/>\n";
echo '檔案群組'.filegroup($phpself);
echo "<br/>\n";
echo "最後存取時間".fileatime($phpself);//返回文件的上次访问时间。
echo "<br/>\n";
echo "權限改變時間".filectime($phpself);//返回文件的上次访问时间。
echo "<br/>\n";
echo "檔案修改時間".filemtime($phpself);//返回文件的上次访问时间。
echo "<br/>\n";
echo "inode".fileinode($phpself);

echo "<br/>\n";
//echo $_SERVER['DOCUMENT_ROOT'];

//session_start(); //session

$_SESSION["test"]='ON';
echo 'session ';
if(isset($_SESSION["test"])){
	echo $_SESSION["test"];
}else{
	echo 'OFF';
}

echo "<br/>\n";
session_destroy();
echo '<pre>'.print_r(stat($phpself),true).'</pre>';
clearstatcache();

echo <<<EOT
<input type="text" value="??" id="in"  />
<script language="Javascript">
function check(e){
	document.getElementById("in").value=e.keyCode;
}
</script>
EOT;
echo "<br/>\n";
//
echo 'apache_request_headers';
echo "<br/>\n";
$headers = apache_request_headers();
$tmp='';
foreach ($headers as $header => $value) {
	$tmp.= "$header: $value \n";
}
echo '<pre>'.print_r($tmp,true).'</pre>';
//
echo 'apache_response_headers';
echo "<br/>\n";
$headers = apache_response_headers();
$tmp='';
foreach ($headers as $header => $value) {
	$tmp.= "$header: $value \n";
}
echo '<pre>'.print_r($tmp,true).'</pre>';
//
echo 'headers_list';
echo "<br/>\n";
echo '<pre>'.print_r(headers_list(),true).'</pre>';
//
echo 'var_dump';
echo "<br/>\n";
//echo '<pre>'.print_r(headers_list(),true).'</pre>';
var_dump(time());
var_export(time());//var_dump
echo '<pre>'.print_r(time(),true).'</pre>';
//
echo '$_SERVER';
echo "<br/>\n";
//$headers = apache_response_headers();
$tmp='';
foreach ($_SERVER as $k => $v) {
	$tmp.= "$k: $v \n";
}
echo '<pre>'.print_r($tmp,true).'</pre>';
//
//
echo '</body></html>';


/*
$cp = fopen($tmp, "a+");// 讀寫模式, 指標於最後, 找不到會嘗試建立檔案
rewind($cp); //從頭讀取
$cpbuf=fread($cp,1000000);//讀取檔案內容
fclose($cp);

$cellarr=array();
$cellarr=explode("\n",$cpbuf);
print_r($cellarr);
*/
?>