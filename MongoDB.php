<?php
header('Content-Type: application/javascript; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//

$FFF='MongoDB\Driver\Manager';
if(class_exists($FFF)){
	$FFF=$FFF.'存在';
}else{
	$FFF=$FFF.'不存在';
}
echo $FFF;
echo "\n";

$FFF='MongoClient';
if(class_exists($FFF)){
	$FFF=$FFF.'存在';
}else{
	$FFF=$FFF.'不存在';
	die($FFF);
}
echo $FFF;
echo "\n";




echo 'mongo版本=';
echo MongoClient::VERSION;
echo "\n";



//define('DB_HOST',getenv('OPENSHIFT_MONGODB_DB_HOST'));
//define('DB_PORT',getenv('OPENSHIFT_MONGODB_DB_PORT')); 
//define('DB_USER',getenv('OPENSHIFT_MONGODB_DB_USERNAME'));
//define('DB_PASS',getenv('OPENSHIFT_MONGODB_DB_PASSWORD'));
//define('DB_URL' ,getenv('OPENSHIFT_MONGODB_DB_URL'));

//
//ob_start();//



//The MongoDB connection URL (e.g. mongodb://<username>:<password>@<hostname>:<port>/)
//$FFF='mongodb://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.':'.DB_PORT.'';
//echo $FFF;
//$manager = new MongoDB\Driver\Manager("$FFF");
//$m = new MongoClient($FFF); // 连接默认主机和端口为：mongodb://localhost:27017

//$FFF=DB_URL;
//$FFF=getenv('OPENSHIFT_MONGO_DB_URL');

$FFF=$_ENV["OPENSHIFT_MONGODB_DB_URL"];
//echo '01='.$FFF;echo "\n";//

try{
	//$FFF=getenv('OPENSHIFT_MONGO_DB_URL');
	$manager = new MongoClient($FFF); // 连接默认主机和端口为：mongodb://localhost:27017
}catch(Exception $e){
	//echo 'Caught exception: ', $e->getMessage(), "\n";
	print_r($e);
}
var_dump($manager);

$FFF='MongoClient呼叫';
if($manager->connected ){
	$FFF=$FFF.'成功';
}else{
	$FFF=$FFF.'失敗';
	die($FFF);
}
echo $FFF;
echo "\n";

// get the database named "test"
// 获取名称为 "test" 的数据库
$db = $manager->test; 
var_dump($db);
$FFF='database呼叫';
if( count((array)$db) >0 ){
	$FFF=$FFF.'成功';
}else{
	$FFF=$FFF.'失敗';
	die($FFF);
}
echo $FFF;
echo "\n";

// Get the poi123 collection
//$collection = $db->createCollection("runoob");
$collection = $db->poi123;
var_dump($collection);
$FFF='collection呼叫';
if( count((array)$collection) >0 ){
	$FFF=$FFF.'成功';
}else{
	$FFF=$FFF.'失敗';
	die($FFF);
}
echo $FFF;
echo "\n";

//簡化的寫法1
//$collection = $manager->selectCollection("test", "poi123");
//簡化的寫法2
//$collection = $manager->selectDB("test")->selectCollection("poi123");


//$collection = $db->runoob; // 选择集合
$document = array( 
	"title" => "中文MongoDB", 
	"description" => "中文database", 
	"likes" => '100',
	"url" => "中文http://www.runoob.com/mongodb/",
);
var_dump($document);//array

$options = array(
    "w" => 1,
    "j" => true,
);
$stmt=$collection->insert($document,$options);
var_dump($stmt);//array
var_dump($document);//array//有改變 多了mongoid
var_dump($collection);//object//回傳的只是參數

$FFF='数据插入';
if($stmt['ok'] ){
	$FFF=$FFF.'成功';
}else{
	$FFF=$FFF.'失敗';
	die($FFF);
}
echo $FFF;
echo "\n";



//exit;
//
$cursor = $collection->find();
var_dump($cursor);//object//沒東西
$count=$cursor->count();
echo '數量='.$count;
echo "\n";

//echo '</pre>';
// 迭代显示文档标题

$cc=0;
foreach ($cursor as $k=>$v) {
	$cc++;
	if($cc < $count-5){continue;}//break;//顯示最新5個
	//
	echo '#'.$cc.'#';
	echo "\n";
	echo '#'.print_r($k,1).'#';
	echo "\n";
	echo '#'.print_r($v,1).'#';
	echo "\n";
}

?>