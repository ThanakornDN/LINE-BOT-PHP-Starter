<?php
$access_token = 'VlxSZTyumW3qJsUKMnKOTLdqRd7chFWFJARPb7ZB/n3Lzf/lntpuOwBiLNieMReH3aFrT4MoAEWCdFruNp/7VHg3RkM1ja3AUtYVlDabJUgo6wAKsQyrZVo9Vxq+/py7le7bLr6ZDSp6qQHy0RiI2gdB04t89/1O/w1cDnyilFU=';
$msg="";
$m_type="";
$regs="";
$ans_state=0;

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

			
			$s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/test_ans.php?msg='.$text);
			if(!eregi ( "S0",$s_ans, $regs )){
					$messages = [
						'type'=>'text',
						'text'=>$s_ans
					];
			}else if((eregi ( "คือ", $text, $regs ))or(eregi ( "หมายถึง", $text, $regs ))){
				$msg_split = explode($regs[0],$text);
				$msg1=$msg_split[0]; 
				$msg2=$msg_split[1];
				$msg_check = "แน่ใจนะว่า ".$text." ?";
				$test_insert = urlencode($msg1."|".$msg2);
				
				file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/test_insert.php?msg='.$test_insert);
				
				$messages = [
					  "type"=>"template",
					  "altText"=>"this is a confirm template",
					  "template"=>[
					      "type"=>"confirm",
					      "text"=>$msg_check,
					      "actions"=> [
						  [
						    "type"=>"message",
						    "label"=>"Yes",
						    "text"=>"yes"
						  ],
						  [
						    "type"=>"message",
						    "label"=>"No",
						    "text"=>"no"
						  ]
					      ]
					]
				];
			}else if((eregi ( "ตอบว่า", $text, $regs ))or(eregi ( "ตอบ", $text, $regs ))){
				$msg_split = explode($regs[0],$text);
				$msg1=$msg_split[0]; 
				$msg2=$msg_split[1];
				$msg_check = "แน่ใจนะว่า ".$text." ?";
				$test_insert = urlencode($msg1."|".$msg2);
				
				file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/test_insert.php?msg='.$test_insert);
				
				$messages = [
					  "type"=>"template",
					  "altText"=>"this is a confirm template",
					  "template"=>[
					      "type"=>"confirm",
					      "text"=>$msg_check,
					      "actions"=> [
						  [
						    "type"=>"message",
						    "label"=>"Yes",
						    "text"=>"yes"
						  ],
						  [
						    "type"=>"message",
						    "label"=>"No",
						    "text"=>"no"
						  ]
					      ]
					]
				];
			}else if((eregi ( "ใช่", $text, $regs ))or(eregi ( "ตกลง", $text, $regs ))or(eregi ( "yes", $text, $regs ))or(eregi ( "ok", $text, $regs ))){
				$messages = [
					"id"=>"325708",
					"type"=>"sticker",
					"packageId"=>"1",
					"stickerId"=>"1"
				];
			}else{
				$msg_check =$text."ต้องตอบว่าไงดี ?";
				//file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/test_insert.php?msg='.$test_insert);
				
				$messages = [
					  "type"=>"template",
					  "altText"=>"this is a buttons template",
					  "template"=>[
					      "type"=>"buttons",
					      "text"=>$msg_check,
					      "actions"=>[
						  [
						    "type"=>"postback",
						    "label"=>"Buy",
						    "data"=>"action=buy&itemid=123"
						  ],
						  [
						    "type"=>"postback",
						    "label"=>"Add to cart",
						    "data"=>"action=add&itemid=123"
						  ],
						  [
						    "type"=>"uri",
						    "label"=>"เพิ่ม",
						    "uri"=>"http://202.28.37.32/smartcsmju/LineAPI/test_insert.php"
						  ]
					      ]
					  ]
				];
			}



			// Make a POST Request to Messaging API to reply to sender
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

			echo $result . "\r\n";
		}
	}
}
echo "OK";

