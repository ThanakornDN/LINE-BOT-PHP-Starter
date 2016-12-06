<?php
$access_token = 'VlxSZTyumW3qJsUKMnKOTLdqRd7chFWFJARPb7ZB/n3Lzf/lntpuOwBiLNieMReH3aFrT4MoAEWCdFruNp/7VHg3RkM1ja3AUtYVlDabJUgo6wAKsQyrZVo9Vxq+/py7le7bLr6ZDSp6qQHy0RiI2gdB04t89/1O/w1cDnyilFU=';
$msg="";
$m_type="";
$regs="";
$msg_check="???";
	
include("Message.php");

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$s_ans = find_ans($text);
			
			
			//$s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$text);			
			$msg_decode = json_decode($s_ans, true);
				foreach ($msg_decode['msg'] as $msg) {
         				$msg_type = $msg['type'];
	       			}
			$m_stat = $msg_decode['status'];
			if($m_stat=='S1'){
				$messages = $msg_decode['msg'];
				//if($msg_type=='text'){
				//	$messages = $s_ans;
				//}			
			}else if($m_stat=='S2'){
				//$messages = $msg;	
				$messages = [
					  "type"=>"template",
					  "altText"=>"this is a buttons template",
					  "template"=>[
					      "type"=>"buttons",
					      "text"=>$msg_check,
					      "actions"=>[
						  [
						    "type"=>"message",
						    "label"=>"1",
						    "text"=>"1"
						  ],
						  [
						    "type"=>"message",
						    "label"=>"2",
						    "text"=>"2"
						  ]
					      ]
					  ]
				];
			}else{
				if($msg_type=='text'){
					$messages = $msg;
				}

			}
			
			//if($msg_type=='text'){
			//	$messages = $msg;
			//}else if($msg_type=='image'){
				
			//}else if($msg_type=='video'){
				
			//}else if($msg_type=='audio'){
			
			//}else if($msg_type=='sticker'){
			//
			//}
			
					//$messages = [
					//	'type'=>'text',
					//	'text'=>$s_ans
					//];		
					//$msg_t =json_encode($msg_decode['msg']);
					//$messages = ['type'=>'text','text'=>$s_ans];	


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
		}
	}
}
echo "OK";

