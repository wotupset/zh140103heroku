<?php

echo "\n".'系統預設';
echo date_default_timezone_get();
echo "\n";
echo $time=time();
echo "\n";
echo date("y-m-d h-i-s",$time);
echo "\n";
echo gmdate("y-m-d h-i-s",$time);

date_default_timezone_set("Asia/Taipei");//時區設定

echo "\n".'+8時區';
echo date_default_timezone_get();
echo "\n";
echo $time=time();
echo "\n";
echo date("y-m-d h-i-s",$time);
echo "\n";
echo gmdate("y-m-d h-i-s",$time);

date_default_timezone_set("UTC");//時區設定

echo "\n".'UTC時區';
echo date_default_timezone_get();
echo "\n";
echo $time=time();
echo "\n";
echo date("y-m-d h-i-s",$time);
echo "\n";
echo gmdate("y-m-d h-i-s",$time);


?>
