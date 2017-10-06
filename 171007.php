<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);//
$php_info=pathinfo($_SERVER["PHP_SELF"]);//
$php_dir=$php_info['dirname'];//
$phpself=$php_info['basename'];//被執行的文件檔名
$php_http_link="http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING'];//
$php_http_dir ="http://".$_SERVER["SERVER_NAME"].$php_dir."/";//
//
date_default_timezone_set("Asia/Taipei");//時區設定
//$time = time();//UNIX時間時區設定
$time=sprintf('%s',time());//%u=零或正整數//%s=字串
$query_string=$_SERVER['QUERY_STRING'];//
//date("y/m/d H:i:s", $time);
$time2=date("ymd-His", $time);//年月
$ymd=date("ymd",$time);
//
$data='';
if(get_magic_quotes_gpc()) {$input_a = stripslashes($input_a);} //去掉字串中的反斜線字元
$data=$input_a;
$data=preg_replace("/EttppZX/i", "http://", $data);//有些免空會擋過多的http字串
//
$tmp="./curl_getinfo.php";
if(!file_exists($tmp)){die('file_exists');}//file_exists
require_once($tmp);
$tmp='./simple_html_dom.php';
if(!file_exists($tmp)){die('file_exists');}//file_exists
require_once($tmp);
//
$html_entity=html_entity_extA_rand(6)."".html_entity_rand(6);
//$html_entity=$time2.$html_entity.md5($html_entity);
//$html_entity=$time2.$html_entity;
//$html_entity=$time2.'';

$query_string=sprintf('%u',$query_string);//%u=零或正整數//%s=字串
$tmp='';
$g1q='ymd'.$ymd;
$g1=$_GET[$g1q];
$g1=floor($g1);
//$g2q='time'.$ymd;
//$g2=$_GET[$g2q];
$page=$_GET['page'];

if(($time-$g1)<3600){
	$qs='';
	//$qs='&'.$g1q.'='.$ymd;
	//$qs='&'.$g2q.'='.$time;
	$qs='&page='.$page;
	$mode = 'reg';
}
$url_org='http://rem.komica2.net/00/pixmicat.php?mode=module&load=mod_threadlist';
$url=$url_org.$qs;
//

if($mode == 'reg'){
	$data_new='';
	//$data_new.=$html_entity;
	//$data_new.=$url;
	//
	$x=curl_FFF($url);
	//echo print_r($x,true);exit;
	$getdata =$x_0 =$x[0];//資料
	$getinfo =$x_1 =$x[1];//訊息
	$geterror=$x_2 =$x[2];//錯誤
	//echo print_r($getinfo,true);exit;
	$tmp='<pre>'.print_r($getinfo,true).'</pre>';
	if(!preg_match("/".preg_quote('綜合')."/i",$getdata)){print_r($tmp);die('[x]綜合');}
	$html = str_get_html($getdata) or die('沒有收到資料');//simple_html_dom
	$html2 = $html->find('table',0);//find('tr',0)->find('td',0)->outertext;
	//echo print_r($html2,true);exit; 
	//$data_new.=print_r($html2,true);
	$cc=0;
//
	$page_bar='';
	$tmp='';
	$page_link='';
	for($x=0;$x<10;$x++){
		$tmp='';
		if($x == $page){$tmp.='*';}else{}
		$tmp.=str_pad($x,2,"0",STR_PAD_LEFT);
		$page_link.='<a href="'.$phpself.'?'.$g1q.'='.$time.'&page='.$x.'">'.$tmp.'</a>'."\n";
	}
$page_bar.=<<<EOT
<table>
<tr>
<td>
$page_link
<td>
</tr>
<tr>
<td>
$url
</td>
</tr>
</table>
EOT;

//
	$data_new='';
	$data_new.="\n";
	$data_new.=$page_bar;
	$data_new.="\n";
	$data_new.='x<table>';
	$cc=0;
	foreach($html2->find('tr') as $k => $v){
		$cc=$cc+1;
		if($k==0){continue;}
		//
		$FFF=$FFF2='';
		//
		$data_new.='<tr>';
		//
		$FFF=''.$v->find('td',0)->plaintext;
		$FFF='<a href="http://rem.komica2.net/00/pixmicat.php?res='.$FFF.'" target="poi">'.$FFF.'</a>';
		$FFF=$FFF.'('.$v->find('td',3)->plaintext.')';
		$FFF='<td>'.$FFF.'</td>';
		$data_new.=$FFF;
		//
		$FFF=''.$v->find('td',1)->plaintext;
		$FFF='<td>'.$FFF.'</td>';
		$data_new.=$FFF;
		//
		//$data_new.=$v->outertext;
		$data_new.='</tr>';
	}
	$data_new.='</table>';
	$data_new.=$cc;
	
	$data_new.="\n";
	$data_new.=$page_bar;
	$data_new.="\n";
	//$data_new.=$cc;
	//

}
//$html_entity=str_repeat($html_entity, 10);
function html_entity_extA_rand($n){
	$s='';
	for($x=0;$x<$n;$x++){
		$s.='&#'.rand(13312,19894).';';//建立唯一ID 
		//CJK統合漢字拡張A
		//中日韓越漢字擴展A區
		//19968-40959(實際40869)
		//13312-19903(實際19894)
	}
	//
	$x=$s;
	return $s;
}
function html_entity_rand($n){
	$s='';
	for($x=0;$x<$n;$x++){
		$s.='&#'.rand(19968,40869).';';//建立唯一ID 
		//CJK統合漢字拡張A
		//中日韓越漢字擴展A區
		//19968-40959(實際40869)
		//13312-19903(實際19894)
	}
	//
	$x=$s;
	return $s;
}
//
$body='';
$body.='<a href="./'.$phpself.'">archive.komica</a>';
$body.=form();
//$body.='<pre>'.print_r($html_entity,true).'</pre>';
$body.='<pre style="position:fixed;top:10px;left:100px; border:1px solid #0000FF;overflow: scroll;width: 700px;height:150px;">'.print_r($data_new,true).'</pre>';
header('Content-type: text/html; charset=utf-8');
echo '<html>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8">'.$base_target.'
<body>'.$body.'
<iframe style="position:fixed;top:180px;left:0px; border:1px solid #0000FF;width: 800px;height:400px;overflow: visible;" src="http://rem.komica2.net/00/pixmicat.php?mode=module&load=mod_threadlist" name="poi"></iframe>
</body></html>';
//
function form(){
	$phpself=$GLOBALS['phpself'];
$x=<<<EOT
<form enctype="multipart/form-data" action='$phpself' method="post" onsubmit="return check2();" id="form0">
<textarea name="input_a" id="input_a" cols="48" rows="4" wrap=soft></textarea><br/>
<span style="display:block; width:120px; height:90px; BORDER:#000 1px solid;" id='send' name="send" onclick='if(click1){check();}'/>送出</span>
<input type="hidden" name="mode" id="mode" value="reg">
<input type="hidden" name="code" id="code" value="??">
</form>
<script>
var click1=1;
function check(){//submit
	click1=0;
	document.getElementById("send").innerHTML="稍後";
	document.getElementById("code").value="稍後";
	document.getElementById("form0").onsubmit();
}
function check2(){//onsubmit
	//document.getElementById("send").disabled=true;
	document.getElementById("send").style.backgroundColor="#ff0000";
	//
	var tmp;
	var regStr = 'http://';
	var re = new RegExp(regStr,'gi');
	tmp = document.getElementById("input_a").value;
	//alert(regStr);
	tmp = tmp.replace(re,"EttppZX");//有些免空會擋過多的http字串
	document.getElementById("input_a").value =tmp;
	document.getElementById("form0").submit();
}

</script>
EOT;
	$x="\n".$x."\n";
	return $x;
}
?>