<?php
$query_string=$_SERVER['QUERY_STRING'];
$url=$query_string;
//
require_once('simple_html_dom.php');
require_once('curl_getinfo.php');
//

if(1){
  $x=curl_FFF($url);
  //echo print_r($x,true);exit;
  $getdata =$x_0 =$x[0];//資料
  $getinfo =$x_1 =$x[1];//訊息
  $geterror=$x_2 =$x[2];//錯誤
  //simple_html_dom
  if(!$getdata){echo print_r($getinfo,true);exit;}
  //echo print_r($getinfo,true);
  $content=$getdata;
}

//
$html = str_get_html($content) or die('沒有收到資料');//simple_html_dom自訂函式
$chat_array='';
$chat_array = $html->outertext;
//echo print_r($chat_array,true);exit;//檢查點
$board_title = $html->find('span',0)->innertext;//版面標題
$board_title2=''.$board_title.'=第'.$url_num.'篇 於'.$ymdhis.'擷取';
$cc=0;
foreach( $html->find('blockquote') as $k => $v){$cc++;}
if($cc==0){$htmlbody='[x]blockquote';exit;}



//
//批次找留言
	$chat_array=array();
	$cc=0;
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
		//首篇另外處理
		if($k == 0 ){
			//XX
		}else{
			$vv=$v->parent;
			//原始內容
			$chat_array[$k]['org_text']=$vv->outertext;
			//標題
			if(preg_match('/archive/',$url_p1['host'],$match)){ //檔案區
			//if(1){ //檔案區
				foreach($vv->find('span.Ctitle') as $k2 => $v2){
					$chat_array[$k]['title'] =$v2->plaintext;
					$v2->outertext="";
				}
				foreach($vv->find('span.Cname') as $k2 => $v2){
					$chat_array[$k]['name'] =$v2->plaintext;
					$v2->outertext="";
				}
			}else{
				foreach($vv->find('font') as $k2 => $v2){
					if($k2==0){//標題
						$chat_array[$k]['title'] =$v2->plaintext;
						$v2->outertext="";
					}
					if($k2==1){//名稱
						$chat_array[$k]['name'] =$v2->plaintext;
						$v2->outertext="";
					}
				}
			}
			
			//內容
			foreach($vv->find('blockquote') as $k2 => $v2){
				$chat_array[$k]['text']  =$v2->innertext;//內文
				$v2->outertext="";
			}
			//圖片
			foreach($vv->find('a') as $k2 => $v2){
				foreach($v2->find('img') as $k3 => $v3){
					$chat_array[$k]['image']  =$v3->parent->href;//
					$chat_array[$k]['image_t'] =$v3->src;
				}
				$v2->outertext="";
			}
			//刪除的
			foreach($vv->find('a.del') as $k2 => $v2){
				$v2->outertext="";
			}
			//剩餘的
			$chat_array[$k]['zzz_text']=$vv->outertext;
			//
			//$chat_array[$k]['time']=strip_tags($chat_array[$k]['zzz_text']);
			preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+/",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
			$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
			//整理過的清掉
			$vv->outertext='';
		}
	}
	if(!$cc){die('沒有找到blockquote');}
	echo print_r($chat_array,true);exit;//檢查點






//
$auth="國";

?>
