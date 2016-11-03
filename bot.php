<?php
$access_token = 'VlxSZTyumW3qJsUKMnKOTLdqRd7chFWFJARPb7ZB/n3Lzf/lntpuOwBiLNieMReH3aFrT4MoAEWCdFruNp/7VHg3RkM1ja3AUtYVlDabJUgo6wAKsQyrZVo9Vxq+/py7le7bLr6ZDSp6qQHy0RiI2gdB04t89/1O/w1cDnyilFU=';
$msg="OK";

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
			
			if ( (eregi ( "สวัสดี", $text, $regs ))or(eregi ( "Hello", $text, $regs )) ){
				$msg='สวัสดีครับ';
			}else if(eregi ( "faucet", $text, $regs )){
				$msg='เข้าไปดูได้ตามลิ้งนี้เลยครับ URL:http://202.28.37.32/smartcsmju/SmartFaucet/index.php';
			}else{
				$msg='ขออภัยครับ ไม่มีในคำหลัก ลองพิมพ์มาใหม่นะครับ';
			}
			
			// Build message to reply back
			$messages = [
// 				'type' => 'text',
// 				'text' => $msg
				
// 				"type"=>"image",
// 				"originalContentUrl"=>"https://example.com/original.jpg",
// 				"previewImageUrl"=>"https://example.com/preview.jpg"
				
// 				"type"=>"video",
// 				"originalContentUrl"=>"https://example.com/original.mp4",
// 				"previewImageUrl"=>"https://example.com/preview.jpg"

				"type"=>"audio",
				"originalContentUrl"=>"https://example.com/original.m4a",
				"duration"=>240000
			];

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

