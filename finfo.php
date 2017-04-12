<?php 
header("content-Type: text/html; charset=utf-8"); //語言強制
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
echo "<pre>";
$FFF='';$status='';
if(function_exists("mime_content_type")){
	$FFF.=mime_content_type($phpself);
	$status="[o]mime_content_type";
}else{
	$status="[x]mime_content_type";
}
echo $FFF;echo "\n";
echo $status;echo "\n";

if(function_exists("finfo_open")){
	$finfo = finfo_open(FILEINFO_MIME);
	$FFF.=finfo_file($finfo, $phpself);
	finfo_close($finfo);
	$status='[o]finfo_open';
}else{
	$status='[x]finfo_open';
}
echo $FFF;echo "\n";
echo $status;echo "\n";

echo "</pre>";
?> 
