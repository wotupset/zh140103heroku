<?php
header('Content-Type: application/javascript; charset=utf-8');
echo '<pre>';
echo $org=<<<EOT
夏蚊成雷，私擬作群鶴舞空，心之所向，則或千或百，果然鶴也；
推 Sanish:照片誰拍的阿？                                           07/17 11:40
→ Leeng:SOD拍片啦                                                 07/17 11:40
→ HermesKing:不舒服                                               07/17 11:40
推 wuming2:sod員工測驗                                             07/17 11:40
推 s9415154:這三層肉... 已吐                                       07/17 11:40
推 kitune:HG都是真的                                               07/17 11:41
推 softseaweed:好刺激XD                                            07/17 11:41
EOT;
echo "\n";
echo strlen($org);echo "\n";
//
echo $FFF=print_r(convert_uuencode($org),true);echo "\n";
echo strlen($FFF);echo "\n";

echo $FFF=print_r(base64_encode($org),true);echo "\n";
echo strlen($FFF);echo "\n";
//
$org=gzdeflate($org);
//**************
echo "壓縮後\n";
echo $FFF=print_r(convert_uuencode($org),true);echo "\n";
echo strlen($FFF);echo "\n";
echo $FFF=print_r(base64_encode($org),true);echo "\n";
echo strlen($FFF);echo "\n";

echo '</pre>';

?>