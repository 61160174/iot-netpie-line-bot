<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'msMlM9jPiWccfAL21VfhSJsEbrG9x0DjfRp5iiWzYZsM60xBxqeDgHhBLrEcH0g2hqW7PR5Zl0tsQyRaU5V+ose0c5kUhfn2bCdhFAk/iDU30q1bOeOjb0qmIJcHMIe+P98y6PtPC9QrsnvWlr0CDQdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$CHANNEL_SECRET = '1f882b7aff8dc0ab6f146d97ee48e7de';
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');  // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
	   	if($text == "ชื่อ" || $text == "ชื่ออะไร" || $text == "ชื่ออะไรครับ"|| $text == "ชื่ออะไรคะ"){
			$reply_message = 'ชื่อของฉันคือ COVID-19_61160174';
		}
	   
	   	if($text == "สถานการณ์โควิดวันนี้" || $text == "covid19" || $text == "covid-19" || $text == "Covid-19"){
		    	$url = 'https://covid19.th-stat.com/api/open/today';
            		$data = [
                			'replyToken' => $replyToken,
                			'messages' => [$messages]
            			];
            		$post = json_encode($data);
            		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
            		$ch = curl_init($url);
            		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            		$result = curl_exec($ch);
            		curl_close($ch);
		   
		   	$obj = json_decode($result);
		   
		   	//$reply_message = $result;
		   	$reply_message = 'ผู้ป่วยสะสม '. $obj->{'Confirmed'} .' คน  เสียชีวิต ' .$obj->{'Deaths'} .' คน  รักษาหายแล้ว '.$obj->{'Recovered'} . ' คน';
	        }	
	   
	   if($text =="@บอท ขอรหัสนิสิตของผู้พัฒนา ส่งไปที่ https://linebot.kantit.com/stuid.php"){
	    	  $url = 'https://linebot.kantit.com/stuid.php';
		   $ch = curl_init($url);
		   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		   curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
		   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		   $result = curl_exec($ch);
		   curl_close($ch);   		   
		   $obj = json_decode($result);		   
		   $reply_message = $result;
		   //$reply_message = 'ผลการบันทึกข้อมูล'. $obj->{'status'} .' และ '.$obj->{'data'} . ' OK!';
	   }
	   
	   if($text =="@บอท ขอรายชื่อนิสิตที่ส่งงาน LineBoT"){
	    	   $url = 'https://linebot.kantit.com/list.php';
		   
		   $reply_message = file_get_contents($url);   // Get request content
		   
		   //$request_array = json_decode($request, true);   // Decode JSON to Array
		   
		   //$result = file_get_contents($url);		   
		   //$obj = json_decode($result);
		   //$reply_message = 'มีส่งงาน '. $obj->{'Confirmed'} .' คน ได้แก่...';
		   //$reply_message = 'ติดเชื้อสะสม '. $obj->{'Confirmed'} .' คน รักษาหายแล้ว '.$obj->{'Recovered'} . ' คน';
		   $reply_message = "โปรดรอสักครู่....";
	   }
	   
	     if($text =="@บอท ขอที่อยู่มทร.หน่อยจิ"){
	    	   $url = 'https://www.google.com/maps/place/Phra+Nakhon+Si+Ayutthaya/@14.3935691,100.237786,10z/data=!3m1!4b1!4m8!1m2!2m1!1smaps+google!3m4!1s0x30e2736f5bfd8f7f:0x1019237450c4860!8m2!3d14.3532128!4d100.5689599';
		   
		   $reply_message = $url;   // Get request content

		   //$reply_message = $reply_message;
	   }
	   
		//$reply_message = '('.$text.') ได้รับข้อความเรียบร้อย!!';   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

?>
