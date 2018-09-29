<?php
//header("content-Type: application/json; charset=utf-8"); //強制

echo $tmp=parse_url(getenv("DATABASE_URL"));

$db=(object)[];
$db->host = ''.$_ENV['MYSQL_HOST'];//'localhost'
$db->user = ''.$_ENV['MYSQL_USERNAME'];
$db->pass = ''.$_ENV['MYSQL_PASSWORD'];
$db->name = ''.$_ENV['MYSQL_DBNAME'];
$db->port = ''.$_ENV['MYSQL_PORT'];
//print_r($db);

?>