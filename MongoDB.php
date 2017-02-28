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
	echo $FFF;
}
echo $FFF;
echo "\n";




echo '';
echo MongoClient::VERSION;
echo "\n";



define('DB_HOST',getenv('OPENSHIFT_MONGODB_DB_HOST'));
define('DB_PORT',getenv('OPENSHIFT_MONGODB_DB_PORT')); 
define('DB_USER',getenv('OPENSHIFT_MONGODB_DB_USERNAME'));
define('DB_PASS',getenv('OPENSHIFT_MONGODB_DB_PASSWORD'));
define('DB_URL' ,getenv('OPENSHIFT_MONGODB_DB_URL'));

//
ob_start();//

echo '01=';
echo DB_URL;
echo "\n";

echo '02=';
try{
	echo $FFF=getenv('OPENSHIFT_MONGO_DB_URL');
} catch (Exception $e) {
	//echo 'Caught exception: ',  $e->getMessage(), "\n";
	print_r($e);
}
echo "\n";

echo '03=';
try{
	echo $FFF=$_ENV["OPENSHIFT_MONGODB_DB_URL"];
} catch (Exception $e) {
	//echo 'Caught exception: ',  $e->getMessage(), "\n";
	print_r($e);
}
echo "\n";
//
$buffer = ob_get_clean();
//echo '10='.$buffer;


//The MongoDB connection URL (e.g. mongodb://<username>:<password>@<hostname>:<port>/)
//$FFF='mongodb://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.':'.DB_PORT.'';
//echo $FFF;
//$manager = new MongoDB\Driver\Manager("$FFF");
//$m = new MongoClient($FFF); // 连接默认主机和端口为：mongodb://localhost:27017
$FFF=DB_URL;
$manager = new MongoClient($FFF); // 连接默认主机和端口为：mongodb://localhost:27017
var_dump($manager);

$FFF='資料庫連接';
if($manager->connected ){
	$FFF='成功';
}else{
	$FFF='失敗';
	die($FFF);
}
echo $FFF;
echo "\n";


$db = $manager->test; // 获取名称为 "test" 的数据库
$collection = $db->createCollection("runoob");
echo "集合创建成功";
echo "\n";

//exit;
$collection = $db->runoob; // 选择集合
$document = array( 
	"title" => "中文MongoDB", 
	"description" => "中文database", 
	"likes" => '100',
	"url" => "中文http://www.runoob.com/mongodb/",
);
$collection->insert($document);
echo "数据插入成功";
echo "\n";

$cursor = $collection->find();
// 迭代显示文档标题
echo $count=$cursor->count();

echo "\n";
echo "\n";

echo '</pre>';

$cc=0;
foreach ($cursor as $document) {
	$cc++;
	if($cc>10){break;}
	//
	echo '#'.$cc.'#';
	echo '<pre>'.print_r($document,true).'</pre>'."\n";
}

?>
