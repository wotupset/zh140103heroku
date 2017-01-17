<?php 
header('Content-Type: application/javascript; charset=utf-8');
echo $org=$buf="10051 台北市中正區中山南路一號"; //time()
echo "\n";

$aaa="gzdeflate";
echo "$aaa \t- ";
if(function_exists($aaa)){
	echo $buf=base64_encode(gzdeflate($buf));//壓縮
	echo "\n";
}else{
	echo "不支援";
	echo "\n";
}
$aaa="gzinflate";
echo "$aaa \t- ";
if(function_exists($aaa)){
	echo $buf=gzinflate(base64_decode($buf));//解壓縮
	echo "\n";
}else{
	echo "不支援";
	echo "\n";
}

$aaa="gzcompress";
echo "$aaa \t- ";
if(function_exists($aaa)){
	echo $buf=base64_encode(gzcompress($buf));//壓縮
	echo "\n";
}else{
	echo "不支援";
	echo "\n";
}
$aaa="gzuncompress";
echo "$aaa \t- ";
if(function_exists($aaa)){
	echo $buf=gzuncompress(base64_decode($buf));//解壓縮
	echo "\n";
}else{
	echo "不支援";
	echo "\n";
}
$aaa="gzencode";
echo "$aaa \t- ";
if(function_exists($aaa)){
	echo $buf=base64_encode(gzencode($buf));//壓縮
	echo "\n";
}else{
	echo "不支援";
	echo "\n";
}
$aaa="gzdecode";
echo "$aaa \t- ";
if(function_exists($aaa)){
	echo $buf=gzdecode(base64_decode($buf));//解壓縮
	echo "\n";
}else{
	echo "不支援";
	echo "-";
	$aaa="gzinflate";
	if(function_exists($aaa)){
		function gzdecode($data){return gzinflate(substr($data,10,-8));} 
		echo $buf=gzdecode(base64_decode($buf));//解壓縮
	}else{
		echo "不支援";
		echo "-";
	}
	echo "\n";
}
//function gzdecode($data){return gzinflate(substr($data,10,-8));} 


?>