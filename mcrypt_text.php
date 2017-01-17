<?php
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);//
//
if(!extension_loaded('mcrypt')){die('[x]extension_loaded');}
if(!function_exists("mcrypt_encrypt")){die('[x]function_exists');}
//
$algorithms=mcrypt_list_algorithms();
$tmp='';
$tmp.='/MCRYPT_ciphername'."\n".'/mcrypt_module_get_supported_key_sizes'."\n".'/mcrypt_module_get_algo_key_size'."\n";
foreach ($algorithms as $k => $v) {
	$cipher=$v;
	//以数组形式返回指定演算法所支持的密钥大小。
	//如果从 1 到 mcrypt_module_get_algo_key_size() 的密钥大小都支持，则返回空数组。
	$ksizes1 = mcrypt_module_get_supported_key_sizes($cipher, '');
	$ksizes1 = print_r(implode(',',$ksizes1),true);
	//获取打开模式所支持的最大密钥大小。
	$ksizes2 = mcrypt_module_get_algo_key_size($cipher, '');
	$tmp.=sprintf("%1\$-20s %2\$-20s %3\$-20s",$cipher,$ksizes1,$ksizes2)."\n";
	//$tmp.=$cipher."\t".$ksizes1."\t".$ksizes2."\n";
}
$tmp='<pre>'.print_r($tmp,true).'</pre>';
echo $tmp;

/*
MCrypt is a replacement for the old crypt()
Warning: mcrypt_encrypt(): Size of key is too large for this algorithm
Warning: mcrypt_encrypt(): Module initialization failed
*/
$cipher = 'des'; //DES算法表示为MCRYPT_DES;//密碼檢索本 (cipher) 
$modes = 'ecb'; //ECB模式表示为MCRYPT_MODE_ECB；
$td = mcrypt_module_open( $cipher, '', $modes, '');
/*
本函数打开指定算法和模式对应的模块。
算法名称可以是字符串，例如 "twofish"， 也可以是 MCRYPT_ciphername 常量。
密碼檢索本 (cipher) 
http://php.net/manual/en/mcrypt.ciphers.php

调用 mcrypt_module_close() 函数可以关闭模块。
*/
//
//本函数返回演算法名称。
$tmp=mcrypt_enc_get_algorithms_name($td);//cipher名稱
$tmp='<pre>mcrypt_enc_get_algorithms_name='.print_r($tmp,true).'</pre>';
echo $tmp;
//本函数返回模式名称。
$tmp=mcrypt_enc_get_modes_name($td);//mode名稱
$tmp='<pre>mcrypt_enc_get_modes_name='.print_r($tmp,true).'</pre>';
echo $tmp;

//
//返回打开的模式所能支持的最长密钥，以字节为单位。
$tmp=mcrypt_enc_get_key_size($td);//密鑰字數上限//maximum supported key size
$key_size=$tmp;
$tmp='<pre>mcrypt_enc_get_key_size='.print_r($tmp,true).'</pre>';
echo $tmp;
//获取演算法的區塊大小。
$tmp=mcrypt_enc_get_block_size($td);//blocksize//Returns the blocksize
$tmp='<pre>mcrypt_enc_get_block_size='.print_r($tmp,true).'</pre>';
echo $tmp;
//本函数返回由加密描述符指定的演算法所使用的初始向量大小
//以字节为单位。 在 cbc，cfb 和 ofb 模式以及某些流模式算法中会用到初始向量。
$tmp=mcrypt_enc_get_iv_size($td);
$tmp='<pre>mcrypt_enc_get_iv_size='.print_r($tmp,true).'</pre>';
echo $tmp;
//获取打开的演算法所支持的密钥长度。
$tmp=mcrypt_enc_get_supported_key_sizes($td);
$tmp='<pre>mcrypt_enc_get_supported_key_sizes='.print_r($tmp,true).'</pre>';
echo $tmp;
/*
mcrypt_enc_get_key_size
返回打开的模式所能支持的最长密钥，以字节为单位。
mcrypt_enc_get_supported_key_sizes
返回由加密描述符(td)指定的演算法所能够支持的密钥长度。
如果该演算法支持从 1 到 mcrypt_enc_get_key_size() 之间任意长度的密钥，则返回空数组。
*/
//


$key = "你又吃鍋貼"; //密鑰//specified cipher//有字數上限 例如AES-256是32bit
$tmp=hash('md5',$key);
//echo $key_size;
$key = substr($tmp, 0, $key_size );//截成可接受的長度
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);//初始化向量
/*
IV= initialization vector =初始向量 (或SV, starting variable）
初始向量的長度依密碼運算的所需決定。在區塊加密中，初始向量的長度通常就等於一個區塊的大小。
初始向量数据来源。可选值有： 
MCRYPT_RAND （系统随机数生成器）
MCRYPT_DEV_RANDOM （从 /dev/random 文件读取数据）
MCRYPT_DEV_URANDOM （从 /dev/urandom 文件读取数据）
在 Windows 平台，PHP 5.3.0 之前的版本中，仅支持 MCRYPT_RAND。
请注意，在 PHP 5.6.0 之前的版本中， 此参数的默认值为 MCRYPT_DEV_RANDOM。
*/
/* 
mcrypt_generic_init =初始化
在每次调用 mcrypt_generic() 或 mdecrypt_generic() 函数之前必须调用本函数。
若沒特殊要求 可以直接使用 mcrypt_encrypt() 會更簡潔 不需要另外初始化
與使用 mcrypt_generic() 來加密並沒有差別
*/

$str = "今天又是びょんびょん的一天 = ="; //加密內容
$str_a=strlen($str);
echo "<pre>加密前($str_a)："."\n"
.$str."\n"
.$cipher."\n"
.$key."\n"
.$str."\n"
.$modes."\n"
.$iv."</pre>";

$str_encrypt = mcrypt_encrypt($cipher,$key,$str,$modes,$iv); //加密函數
$str_b=strlen($str_encrypt);
echo "<pre>加密後($str_b)：".$str_encrypt."</pre>";
$FFF=base64_encode($str_encrypt);
$str_c=strlen($FFF);
echo "<pre>base64包裝($str_c)：".$FFF."</pre>";

$buf=base64_encode(gzdeflate($str));//壓縮
$str_d=strlen($buf);
echo "<pre>與gz+b64比較($str_d)：".$buf."</pre>";


$str_decrypt = mcrypt_decrypt($cipher,$key,$str_encrypt,$modes,$iv); //解密函數
echo "<pre>還原：".$str_decrypt.'</pre>';
//
//可用的演算法
$tmp=mcrypt_list_algorithms();//$cipher
$tmp='<pre>'.print_r($tmp,true).'</pre>';
echo $tmp;
//可用的模式
$tmp=mcrypt_list_modes();//$mode
$tmp='<pre>'.print_r($tmp,true).'</pre>';
echo $tmp;
/*
//可用的演算法
Array
(
    [0] => cast-128
    [1] => gost
    [2] => rijndael-128
    [3] => twofish
    [4] => arcfour
    [5] => cast-256
    [6] => loki97
    [7] => rijndael-192
    [8] => saferplus
    [9] => wake
    [10] => blowfish-compat
    [11] => des
    [12] => rijndael-256
    [13] => serpent
    [14] => xtea
    [15] => blowfish
    [16] => enigma
    [17] => rc2
    [18] => tripledes
)
*/

/*
//可用的模式
Array
(
    [0] => cbc
    [1] => cfb
    [2] => ctr
    [3] => ecb
    [4] => ncfb
    [5] => nofb
    [6] => ofb
    [7] => stream
)

Mcrypt支持四种块加密模型：
 1).MCRYPT_MODE_ECB (electronic codebook) 電子密碼書模式
   适合对小数量随机数据的加密，比如加密用户的登录密码之类的。
 2).MCRYPT_MODE_CBC (cipher block chaining) 密碼塊連結模式
   适合加密安全等级较高的重要文件类型。
 3).MCRYPT_MODE_CFB ( cipher feedback ) 密文反饋
   适合于需要对数据流的每一个字节进行加密的场合。
 4).MCRYPT_MODE_OFB (output feedback, in 8bit) 輸出回饋模式
   和CFB模式兼容，但比CFB模式更安全。
   CFB模式会引起加密的错误扩散，如果一个byte出错，则其后续的所有byte都会出错。
   OFB模式则不会有 此问题。但该模式的安全度不是很高，不建议使用。
 5).MCRYPT_MODE_NOFB (output feedback, in nbit) 輸出回饋n位元模式
   和OFB兼容，由于采用了块操作算法，安全度更高。
 6).MCRYPT_MODE_STREAM 
   是为了WAKE或者RC4等流加密算法提供的额外模型。 
   NOFB和STREAM仅当mycrypt的版本号大于等于 libmcrypt-2.4.x才有效。
*/


//
mcrypt_module_close($td);
exit;
?>