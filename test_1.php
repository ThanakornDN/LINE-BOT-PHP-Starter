<?php
$access_token = 'VlxSZTyumW3qJsUKMnKOTLdqRd7chFWFJARPb7ZB/n3Lzf/lntpuOwBiLNieMReH3aFrT4MoAEWCdFruNp/7VHg3RkM1ja3AUtYVlDabJUgo6wAKsQyrZVo9Vxq+/py7le7bLr6ZDSp6qQHy0RiI2gdB04t89/1O/w1cDnyilFU=';
$msg="";
$m_type="";
$regs="";
$msg_check="";
$it=3;
	
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
			
			//$s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/check_MSG.php?msg='.$text);			
		
			$msg_decode = json_decode($s_ans, true);
				foreach ($msg_decode['msg'] as $msg) {
         				$msg_type = $msg['type'];
	       			}
			
			$m_stat = $msg_decode['status'];
			$msg_type = $msg_decode['msg_type'];
			
			if($msg_type=='Message'){
				$messages = [
						'type'=>'text',
						'text'=>$msg_decode['msg']
					];	
				//$messages = $msg_decode['msg'];		
			}else if($msg_type=='Template'){
				$msg_c = $msg_decode['msg'];
				$arrlength=count($msg_c);
				$msg_check=$msg_decode['msg_check']." ต้องตอบว่าไงดี ???";	
				
				switch($arrlength){
					case '0':
						$messages = [
							  "type"=>"template",
							  "altText"=>"this is a buttons template",
							  "template"=>[
							      "type"=>"buttons",
							      "text"=>$msg_check,
							      "actions"=>[
									  [
									    "type"=>"message",
									    "label"=>"อื่นๆ...",
									    "text"=>"อื่นๆ..."
									  ]
							      ]
							  ]
						];
						break;
					case '1':
						$messages = [
							  "type"=>"template",
							  "altText"=>"this is a buttons template",
							  "template"=>[
							      "type"=>"buttons",
							      "text"=>$msg_check,
							      "actions"=>[
								  [
								    "type"=>"postback",
								    "label"=>$msg_c[0],
								    "data"=>$msg_c[0]
								  ],
								  [
								    "type"=>"postback",
								    "label"=>"อื่นๆ...",
								    "data"=>"อื่นๆ..."
								  ]
							      ]
							  ]
						];
						break;
					case '2':
						$messages = [
							  "type"=> "template",
							  "altText"=> "this is a buttons template",
							  "template"=> [
							      "type"=> "buttons",
							      //"thumbnailImageUrl"=> "https://example.com/bot/images/image.jpg",
							      //"title"=> "Menu",
							      "text"=> $msg_check,
							      "actions"=> [
								  [
								    "type"=> "postback",
								    "label"=> $msg_c[0],
								    "data"=> $msg_c[0]
								  ],
								  [
								    "type"=> "postback",
								    "label"=> $msg_c[1],
								    "data"=> $msg_c[1]
								  ],
								  [
								    "type"=> "postback",
								    "label"=> "อื่นๆ...",
								    "data"=>"อื่นๆ..."
								  ]
							      ]
							  ]
						];
						break;
					case '3':
						$messages = [
							  "type"=> "template",
							  "altText"=> "this is a buttons template",
							  "template"=> [
							      "type"=> "buttons",
							      "text"=> $msg_check,
							      "actions"=> [
								  [
								    "type"=> "postback",
								    "label"=>$msg_c[0],
								    "data"=>$msg_c[0]
								  ],
								  [
								    "type"=> "postback",
								    "label"=>$msg_c[1],
								    "data"=>$msg_c[1]
								  ],
								  [
								    "type"=> "postback",
								    "label"=>$msg_c[2],
								    "data"=>$msg_c[2]
								  ],
								  [
								    "type"=> "postback",
								    "label"=>"อื่นๆ...",
								    "data"=>"อื่นๆ..."
								  ]
							      ]
							  ]
						];
						break;
				}
			}else{
				$messages = ['type'=>'text','text'=>'Error'];
			}			
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
		}else if ($event['type'] == 'postback' ) {

			$text = $event['postback']['data'];
			// Get replyToken
			$replyToken = $event['replyToken'];	
			$updt = $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$text);
			$messages = [
				'type'=>'text',
				'text'=>$updt
			];	
			
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
	}
}
echo "OK";

