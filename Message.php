<?php
public function replyText($event){

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
			}else if((eregi ( "ใช่", $text, $regs ))or(eregi ( "ตกลง", $text, $regs ))or(eregi ( "yes", $text, $regs ))or(eregi ( "ok", $text, $regs ))){
				$messages = [
					"id"=>"325708",
					"type"=>"sticker",
					"packageId"=>"1",
					"stickerId"=>"1"
				];
			}else{
				
				$test_insert = "Hi|Hi";
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
						    "label"=>"Add",
						    "data"=>"action=010"
						  ],
						  [
						    "type"=>"uri",
						    "label"=>"เพิ่มคำตอบ อื่น..",
						    "uri"=>"http://202.28.37.32/smartcsmju/LineAPI/test_insert_user_msg.php"
						  ]
					      ]
					  ]
				];
			}

			// Make a POST Request to Messaging API to reply to sender
			//$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
      
      return $this->replyMessage($data);
}

?>
