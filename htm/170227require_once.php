<?php

$htmlbody="";
$htmlbody2="";
$img_all='';
$cc1=0;//回文數
$cc2=0;//貼圖數
//echo $query_string;

//
if($kdao_only){//只使用於綜合網址
	//取得來源內容
	if(1){
		$x=curl_FFF($url);
		//echo print_r($x,true);exit;
		$getdata =$x_0 =$x[0];//資料
		$getinfo =$x_1 =$x[1];//訊息
		$geterror=$x_2 =$x[2];//錯誤
		//simple_html_dom
		if(!$getdata){echo print_r($getinfo,true);exit;}
		$content=$getdata;
	}
	if(0){
		$ch = curl_init();
		if(!$ch){die('[x]curl');}
		//
		$ret = curl_setopt($ch, CURLOPT_URL,            $url);
		$ret = curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
		$ret = curl_setopt($ch, CURLOPT_NOBODY,         0);//是否隱藏body頁面內容
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
		$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        10);//超時
		$ret = curl_setopt($ch, CURLOPT_FAILONERROR,    1);//發生錯誤時不回傳內容
		//$ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//跟随重定向页面
		$ret = curl_setopt($ch, CURLOPT_MAXREDIRS,      3);//跟随重定向页面的最大次數
		$ret = curl_setopt($ch, CURLOPT_AUTOREFERER,    1);//重定向页面自动添加 Referer header 
		
		//$ret = curl_setopt($ch, CURLOPT_REFERER,        "http://eden.komica.org/");//自訂來路頁面 用來獲取目標
		//$ret = curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		//
		$getdata  = curl_exec($ch);//抓取URL並把它傳遞給變數
		$getinfo  = curl_getinfo($ch);//結果資訊
		$geterror = curl_errno($ch);
		//
		$content=$getdata;
	}
	if(0){
		$opts = array('http'=>array('method'=>"GET",'timeout'=>5));
		$stream = stream_context_create($opts);
		$max_size=5*1024*1024;//抓取上限
		$content = file_get_contents($url,NULL,$stream,0,$max_size) or die('[x]file_get_contents');
	}
	//echo print_r( htmlspecialchars($content) ,true);exit;//檢查點
	//檢查資料A
	if(strlen($content) == 0){$htmlbody='沒有資料';phppoi();exit;}
	if(!preg_match("/body/i",substr($content,0,500))){$htmlbody='不是HTML檔案';phppoi();exit;}
	//去掉異常字串
	$content = preg_replace("/\n/","",$content);
	$content = preg_replace("/\t/","",$content);
	$content=preg_replace("/[\x1-\x1F]/", "", $content);
	$content=preg_replace("/[\x7F]/", "", $content);
	//
	$html = str_get_html($content) or die('沒有收到資料');//simple_html_dom自訂函式
	$chat_array='';
	$chat_array = $html->outertext;
	//檢查資料B
	if(0){
		if(preg_match("/\.cloudflare/i",$chat_array) || true){
			//print_r(htmlspecialchars($chat_array));die('[x]cloudflare');
			$htmlbody='[x]cloudflare';phppoi();exit;
		}
	}
	//
	$board_title = $html->find('span',0)->innertext;//版面標題
	$board_title2=''.$board_title.'=第'.$url_num.'篇 於'.$ymdhis.'擷取';
	$cc=0;
	foreach( $html->find('blockquote') as $k => $v){$cc++;}
	if(!$cc){
		//print_r(htmlspecialchars($chat_array));die('[x]blockquote');
		$htmlbody='[x]blockquote';phppoi();exit;
	}
	//echo print_r($chat_array,true);exit;//檢查點
	

	
	
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
			if(preg_match('/archive/',$url,$match)){ //檔案區
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
	//echo print_r($chat_array,true);exit;//檢查點
	
	
	//首篇另外處理
	$html = $html->find('form',1)->outertext;
	$html = str_get_html($html);//重新轉字串解析//有BUG?
	//$first_post=$html;
	//
	$chat_array[0]['org_text'] = $html->outertext;//原始內容
	//
	if(preg_match('/archive/',$url,$match)){ //檔案區
		foreach($html->find('span.Ctitle') as $k2 => $v2){
			$chat_array[0]['title'] =$v2->plaintext;
			$v2->outertext="";
		}
		foreach($html->find('span.Cname') as $k2 => $v2){
			$chat_array[0]['name'] =$v2->plaintext;
			$v2->outertext="";
		}
	}else{
		foreach($html->find('font') as $k2 => $v2){
			if($k2==0){//標題
				$chat_array[0]['title'] =$v2->plaintext;
				$v2->outertext="";
			}
			if($k2==1){//名稱
				$chat_array[0]['name'] =$v2->plaintext;
				$v2->outertext="";
			}
		}
	}

	//內容
	foreach($html->find('blockquote') as $k2 => $v2){
		$chat_array[0]['text']  =$v2->innertext;//內文
		$v2->outertext="";
	}
	//圖片
	foreach($html->find('a') as $k2 => $v2){
		foreach($v2->find('img') as $k3 => $v3){
			$chat_array[0]['image']  =$v3->parent->href;//
			$chat_array[0]['image_t'] =$v3->src;
		}
		$v2->outertext="";
	}
	//
	$chat_array[0]['zzz_text'] = $html->outertext;//剩餘的內容//非檢查點//下方有用到
	//
	//preg_match("/\[[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+ /U",$chat_array[0]['zzz_text'],$chat_array[0]['time']);
	preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+/",$chat_array[0]['zzz_text'],$chat_array[0]['time']);
	$chat_array[0]['time'] = implode("",$chat_array[0]['time']);
	//
	ksort($chat_array);//排序
	$chat_ct=count($chat_array);//計數
	//echo print_r($chat_array,true);exit;//檢查點
	//
	
	
	
	
	//
	//用迴圈叫出資料
	$cc=0;
	foreach($chat_array as $k => $v){//迴圈
		$cc++;
		$htmlbody.= '<div id="block'.$cc.'">'."\n";
		//名稱
		$v['name']=strip_tags($v['name']);
		$htmlbody.= '<span class="name">'.$v['name'].'</span>'."\n";
		$htmlbody.= '<span class="title">'.$v['title'].'</span>'."\n";
		//名稱 ID時間
		$v['time']=preg_replace('/\]/', '', $v['time']);
		$v['time']=strip_tags($v['time']);
		$htmlbody.= '<span class="idno">'.$v['time'].'</span>'."\n";
		//內文
		$v['text']=strip_tags($v['text'],"<br><font>");//留下換行標籤
		$htmlbody.= '<span class="text"><blockquote>'.$v['text'].'</blockquote></span>'."\n";
		if( $v['image'] ){//回應中有圖 // 網址字串
			$cc2++;//計算圖片數量
			//
			$findme='//';
			$mystring=$v['image'];
			$pos = strpos($mystring, $findme);
			$rest = substr($mystring, $pos+strlen($findme));    // 返回 "f"
			//$v['image']=$rest;
			$v['image']='http://'.$rest;

			$findme='//';
			$mystring=$v['image_t'];
			$pos = strpos($mystring, $findme);
			$rest = substr($mystring, $pos+strlen($findme));    // 返回 "f"
			//$v['image_t']=$rest;
			$v['image_t']='http://'.$rest;
			//
			//
			$php_info=pathinfo($_SERVER["PHP_SELF"]);//被執行的文件檔名
			$phpself=$php_info['basename'];
			if($phpself == '160520-2118.php'){
				if( preg_match('/\.webm$/',$v['image'])){
					$FFF=''.$v['image_t'];
					$htmlbody.= '[<span class="image"><img class="zoom" src="'.$FFF.'"/></span>]';//影片縮圖
					
					$FFF=''.$v['image'];
					$FFF='http://web.archive.org/web/2017/'.$FFF;
					$FFF='http://demo.cloudimg.io/cdn/n/n/'.$FFF;
					$htmlbody.= '<b>webm內容<img class="zoom" src="'.$FFF.'"/></b>';//影片
					
					$FFF=''.$v['image'];
					$FFF='http://web.archive.org/web/2017/'.$FFF;
					$htmlbody.='<video controls preload="metadata"><source src="'.$FFF.'" type="video/webm">video</video><br/>'.$FFF;
				}else{
					$FFF=''.$v['image'];
					$htmlbody.= '<span class="image"><img class="zoom" src="'.$FFF.'"/></span>圖';
				}
				
			}else{
				if( preg_match('/\.webm$/',$v['image'])){
					$FFF=''.$v['image_t'];
					$FFF='http://web.archive.org/web/2017/'.$FFF;
					$FFF='http://demo.cloudimg.io/cdn/n/n/'.$FFF;
					$htmlbody.= '[<span class="image"><img class="zoom" src="'.$FFF.'"/></span>]';//影片縮圖
					
					$FFF=''.$v['image'];
					$FFF='http://web.archive.org/web/2017/'.$FFF;
					$FFF='http://demo.cloudimg.io/cdn/n/n/'.$FFF;
					$htmlbody.= '<b>webm內容<img class="zoom" src="'.$FFF.'"/></b>';//影片
					
					$FFF=''.$v['image'];
					$FFF='http://web.archive.org/web/2017/'.$FFF;
					$htmlbody.='<video controls preload="metadata"><source src="'.$FFF.'" type="video/webm">video</video><br/>'.$FFF;
				}else{
					$FFF=''.$v['image'];
					$FFF='http://web.archive.org/web/2017/'.$FFF;
					$FFF='http://demo.cloudimg.io/cdn/n/n/'.$FFF;
					$htmlbody.= '<span class="image"><img class="zoom" src="'.$FFF.'"/></span>圖';
				}
			}
		
			
			//$tmp="http://assembly.firesize.com/n/g_none/".$tmp0;

			$htmlbody.= "<br/>\n";
		}
		$htmlbody.= '</div>'."\n";
		//
		$cc1++;//計算推文數量
	}//迴圈//
	$htmlbody=' '.$url."<br/>\n".$board_title2."\n"."[$cc1][$cc2]<br>\n".$htmlbody."<br>\n<br>\n";
	$output_path=output_html($htmlbody);//回傳檔案位置
	//$output_path=$output_path."?".date('ymd-His',$time);//輸出的檔案名稱;
	$curlpost=curlpost_html($output_path);
}
//有輸入url/
//

?>
