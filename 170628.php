<?php

$title =<<<EOT
🐰🐇🐰🐇🐰🐇
EOT;
echo htmlspecialchars($text);////轉換為HTML實體
echo htmlentities($title);//转换为 HTML 转义字符
exit;


//echo $tmp=preg_quote('\\');

// your code goes here
$title =<<<EOT
中文
''''
""""
\\\\
////
		
EOT;
//echo $title;

$title =strip_tags($title);//清除html標籤
$title =preg_replace('/\'/', '', $title);
$title =preg_replace('/\"/', '', $title);
$title =preg_replace('/\\\/', '', $title);
$title =preg_replace('/\s/', '', $title);

echo $title;

?>
