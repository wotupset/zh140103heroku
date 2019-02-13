<?php 
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示

header('Content-Type: application/json; charset=utf-8');
$host=$_SERVER['HTTP_HOST'];
echo $host;
echo "\n";
$host_hash =hash("md5", $host);
echo $host_hash;
echo "\n";

//echo hash("crc32", $str, true);//二進制
$host_hash_array =str_split($host_hash,3);//9+1組
//array_push($host_hash_array, $host_hash_array[8].$host_hash_array[9].$host_hash_array[10]  );
$host_hash_array[111]=$host_hash_array[8].$host_hash_array[9].$host_hash_array[10];

print_r($host_hash_array);

//$hash_array=[0];
//$hash_array[0]=substr($host_hash, 0, 8);  // bcd


$testGD = get_extension_funcs("gd"); // Grab function list 
if( $testGD ){
	//ok
	echo "GD ok";
	echo "\n";
}else{
	die('testGD');
}

if( function_exists('imageCreate') ){
	//ok
	echo "function_exists ok";
	echo "\n";
}else{
	die('function_exists');
}



//Header("Content-type: image/png");//指定文件類型為PNG
$img = imageCreate(64,64) or die('imageCreate');;
$color_bg = imageColorAllocate($img, 255, 255, 255);
imageFill($img, 0, 0, $color_bg);//底色

$bb=0;//填充邊線
$w=8;$h=8;//方格大小
$x=0;$y=0;//起始位置


$bit16str="0123456789abcdef";

for($i=0 ; $i<strlen($host_hash_array[111]) ; $i++){ // 8個
	echo $i.gettype($i);
	echo " ";
	$i2=(int)$i; 
	$b16=substr($host_hash_array[111], $i2, 1);//expects parameter 2 to be long //???
	echo $b16;
	echo " ";
	$color=$host_hash_array[$i2];
	echo $color;
	echo " ";
	$color_r=base_convert( substr($color, 0, 1).substr($color, 0, 1) , 16, 10);
	echo $color_r;
	echo "=r ";
	$color_g=base_convert( substr($color, 1, 1).substr($color, 1, 1) , 16, 10);
	echo $color_g;
	echo "=g ";
	$color_b=base_convert( substr($color, 2, 1).substr($color, 2, 1) , 16, 10);
	echo $color_b;
	echo "=b ";
	
	
	$b10 = strpos($bit16str, $b16);
	echo $b10;
	echo " ";
	$b10tob2=base_convert($b10, 10, 2);
	$b10tob2=str_pad($b10tob2, 4, "0", STR_PAD_LEFT);//左方補零 4位
	echo $b10tob2;
	echo "\n";
	
	for($j=0 ; $j<strlen($b10tob2) ; $j++){ // 4位
		$j2=(int)$j;// A non-numeric value encountered
		$x=$bb+($w*$j2);$y=$bb+($h*$i2); //位置
		$color_yn= substr($b10tob2, $j2, 1);
		echo $color_yn;
		echo " ";
		
		if( $color_yn >0	){
			$color_rect = imagecolorallocate ( $img, $color_r, $color_g, $color_b );
		}else{
			$color_rect = imagecolorallocate ( $img, 100, 100, 100 );
		}
		$x=$bb+($w*$j2);$y=$bb+($h*$i2);
		imagefilledrectangle( $img, $x, $y, $x+$w-1, $y+$h-1, $color_rect );
	}
		echo "\n";
		
}

//imagePng($img,"./favicon.png");imageDestroy($img);exit;


//exit;







//imagerectangle( $img, 0, 0, 4, 4, $color_rect );

//區塊1
/*
$x=$bb+($w*0);$y=$bb+($h*0);
$color_rect = imagecolorallocate ( $img, 255, 0, 0 );
imagefilledrectangle( $img, $x, $y, $x+$w-1, $y+$h-1, $color_rect );

$x=$bb+($w*1);$y=$bb+($h*1);
$color_rect = imagecolorallocate ( $img, 0, 255, 0 );
imagefilledrectangle( $img, $x, $y, $x+$w-1, $y+$h-1, $color_rect );

$x=$bb+($w*2);$y=$bb+($h*2);
$color_rect = imagecolorallocate ( $img, 0, 0, 255 );
imagefilledrectangle( $img, $x, $y, $x+$w-1, $y+$h-1, $color_rect );

$x=$bb+($w*3);$y=$bb+($h*3);
$color_rect = imagecolorallocate ( $img, 0, 255, 255 );
imagefilledrectangle( $img, $x, $y, $x+$w-1, $y+$h-1, $color_rect );

*/



$img2 = imagecreatetruecolor(8, 64);
$color_bg = imageColorAllocate($img2, 255, 255, 255);
imageFill($img2, 0, 0, $color_bg);//底色

imagecopy($img2, $img, 0, 0, 0, 0, 8, 64);//複製左邊第一行
imagecopy($img, $img2, 64-(8*1), 0, 0, 0, 8, 64);//貼回去

imagecopy($img2, $img, 0, 0, 0+(8*1), 0, 8, 64);//複製左邊第一行
imagecopy($img, $img2, 64-(8*2), 0, 0, 0, 8, 64);//貼回去

imagecopy($img2, $img, 0, 0, 0+(8*2), 0, 8, 64);//複製左邊第一行
imagecopy($img, $img2, 64-(8*3), 0, 0, 0, 8, 64);//貼回去

imagecopy($img2, $img, 0, 0, 0+(8*3), 0, 8, 64);//複製左邊第一行
imagecopy($img, $img2, 64-(8*4), 0, 0, 0, 8, 64);//貼回去



imagePng($img,"./favicon.png");imageDestroy($img);exit;


//imagePng($img2);imageDestroy($img2);exit;

//imagePng($img2);imageDestroy($img2);exit;



//imageflip($img2, IMG_FLIP_HORIZONTAL);//水平翻轉
//Call to undefined function imageflip  //need after PHP 5.5

//imagePng($img2);imageDestroy($img2);exit;


//imageflip($img, IMG_FLIP_VERTICAL);//上下翻轉
//imageflip($img, IMG_FLIP_HORIZONTAL);//水平翻轉


/*
$moji=date("ymd",$time);
$moji=sprintf("%06d",$moji);
$wd_color =imageColorAllocate($img, 0, 255, 0);//綠色
imagestring($img,5,0,0, $moji, $wd_color);
*/





//imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


?> 
