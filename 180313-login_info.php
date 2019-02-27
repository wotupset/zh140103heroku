<?php
header("content-Type: application/json; charset=utf-8"); //強制

$tmp=parse_url(getenv("DATABASE_URL"));
//print_r($tmp);//含有密碼資訊
//print_r($_ENV);//含有密碼資訊

$env=(object)[];
$env->HTTP_HOST = ''.$_ENV['HTTP_HOST'];
$env->HTTP_USER_AGENT = ''.$_ENV['HTTP_USER_AGENT'];
$env->SCRIPT_NAME = ''.$_ENV['SCRIPT_NAME'];
print_r($env);
print_r($env->HTTP_USER_AGENT);
echo "\n";
echo "\n";


$arr=[
  'HTTP_HOST'=>''.$_ENV['HTTP_HOST'],
  'HTTP_USER_AGENT'=>''.$_ENV['HTTP_USER_AGENT'],
  'SCRIPT_NAME'=>''.$_ENV['SCRIPT_NAME'],
];
print_r($arr);
print_r($arr['SCRIPT_NAME']);
echo "\n";
echo "\n";


?>
