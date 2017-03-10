<?php
$access_token = 'VlxSZTyumW3qJsUKMnKOTLdqRd7chFWFJARPb7ZB/n3Lzf/lntpuOwBiLNieMReH3aFrT4MoAEWCdFruNp/7VHg3RkM1ja3AUtYVlDabJUgo6wAKsQyrZVo9Vxq+/py7le7bLr6ZDSp6qQHy0RiI2gdB04t89/1O/w1cDnyilFU=';
$msg="";
// $it=9;
$msg_mqtt1 ="11221344";
	
include("Message.php");
include("P_mqtt.php");
//include("a1.php");

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
	$source = $event['source']['type'];
		if($source == 'user'){
			// Reply only when message sent is in 'text' format
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];
				$s_ans = find_ans($text);
				$output = shell_exec('php test_mqtt.php');
				//$s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/check_MSG.php?msg='.$text);			

				$msg_decode = json_decode($s_ans, true);
					foreach ($msg_decode['msg'] as $msg) {
						$msg_type = $msg['msg_type'];
					}

				$m_stat = $msg_decode['status'];
				$msg_type = $msg_decode['msg_type'];
				$id_userMSG = $msg_decode['id_userMSG'];
				
				echo $msg_type;
// 			 	include("test_mqtt.php");

				$messages = ['type'=>'text','text'=>$text];			
				
				// Make a POST Request to Messaging API to reply to sender
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
					//'messages' => [$msg],
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

				echo $result . "\r\n";
			}else if ($event['type'] == 'postback' ) {

				$text = $event['postback']['data'];
				// Get replyToken
				$replyToken = $event['replyToken'];	
				$str = explode("|",$text);
				$insertMSG = $str[1]."|".$str[2];

				if($str[0] == "update"){
					$result = $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$str[1]);
					$messages = [
						 "type"=> "sticker",
						 "packageId"=> "2",
						 "stickerId"=> "179"
					];
				}else if($str[0] == "insert"){
					$result = $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/insert_ans.php?msg='.$insertMSG);
					$messages = [
						'type'=>'text',
						'text'=>'พิมพ์ '.$result.':คำตอบ'
					];
				}else{
					$messages = [
						'type'=>'text',
						'text'=>$text
					];
				}
	//			$messages = [
	//				 "type"=> "sticker",
	//				 "packageId"=> "2",
	//				 "stickerId"=> "179"
	//			];				

				//$messages = [
				//	'type'=>'text',
				//	'text'=>$insertMSG
				//];	

				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
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

			}
		//}
		
		}else if($source == "group"){

			if ($event['type'] == 'join') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];
				
				$messages = ['type'=>'text','text'=>"OK"];

				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
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
			}
		}else{
			$aa="dc";
			
		}
	}
}
echo "OK";



