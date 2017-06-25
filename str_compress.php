<?php  
  
$str1 = '布局 1 介绍 布局，简单来说就是设置元素的大小和位置。 Ext 的布局系统包括组件，布局，容器，容器是一种特殊的组件，可以管理组件的大小和位置。 容器是通过 doLayout 来重新计算布局，并更新 DOM. 2 手工布局是不必要的，框架会为你自动处理。';  
  
$str2 = '!@#$%^&*()QWERTYUIOPSDFGHJKL!@#$%^&*()QWERTYUIOPSDFGHJKL:ZXCVBNMa!@#$%^&*()ERTYUIODFGHJKLXCVBNM@#$%^&*()RTYUIOPD:ZXCVBNM#!@#!@#$%^&*()QWERTYUIOPSDFGHJKL:ZXCVBNM-!@#$%^&*()ERTYUIODFGHJKLXCVBNM@#$%^&*()RTYUIOPD$%^&*()ERTYUIODFGHJ!@#$%^&*()QWERTYUIOPSDFGHJKL:ZXCVBNM]!@#$%^&*()ERTYUIODFGHJKLXCVBNM@#$%^&*()RTYUIOPDKLXCVBNM@#$%^&*()RTYUIOPDFGHJKLCVBNMFGHJTYU%^&RFGHJ4d56g7h8ui7h8ujirqwerqh8';  
  
echo '<b>压缩中文比较</b><br><br>';  
compress_comp($str1, 1000); // 压缩1000次 与 解压缩1000次比较  
  
echo '<b>压缩英文数字比较</b><br><br>';  
compress_comp($str2, 1000); // 压缩1000次 与 解压缩1000次比较  
  
/* 压缩 */  
function compress_comp($str, $num){  
  
    $func_compress = array('gzcompress', 'gzencode', 'gzdeflate', 'bzcompress');  
  
    echo '原文:'.$str.'<br><br>';  
    echo '原文大小:'.strlen($str).'<br><br>';  
  
    for($i=0,$length=count($func_compress); $i<$length; $i++){  
  
        $starttime = get_microtime();  
        for($j=0; $j<$num; $j++){  
            switch($func_compress[$i]){  
                case 'gzcompress':  
                    $mstr = gzcompress($str, 9); // 解压方法：gzuncompress  
                    break;  
                case 'gzencode':  
                    $mstr = gzencode($str, 9); // 解压方法：gzdecode php>=5.4  
                    break;  
                case 'gzdeflate':  
                    $mstr = gzdeflate($str, 9); // 解压方法：gzinflate  
                    break;  
                case 'bzcompress':  
                    $mstr = bzcompress($str, 9); // 解压方法：bzdecompress  
                    break;            
            }  
        }  
        $endtime = get_microtime();  
        echo $func_compress[$i].' 压缩后大小:'.strlen($mstr).' 耗时:'.round(($endtime-$starttime)*1000,5).'ms<br><br>';  
    }  
}  
  
  
/* 获取 microtime */  
function get_microtime(){  
    list($usec, $sec) = explode(' ', microtime(true));  
    return $usec+$sec;  
}  
?>  