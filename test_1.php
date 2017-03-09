<?php
$access_token = 'VlxSZTyumW3qJsUKMnKOTLdqRd7chFWFJARPb7ZB/n3Lzf/lntpuOwBiLNieMReH3aFrT4MoAEWCdFruNp/7VHg3RkM1ja3AUtYVlDabJUgo6wAKsQyrZVo9Vxq+/py7le7bLr6ZDSp6qQHy0RiI2gdB04t89/1O/w1cDnyilFU=';
$msg="";
$m_type="";
$regs="";
$msg_check="";
$it=9;
$msg_mqtt ="11223344";
	
include("Message.php");
include("test_mqtt.php");
?>

<script src="mqtt.js"></script>
<script type="text/javascript">
// Create a client instance
var mes = "";
var sub = "TEST/MQTT/HEROKU/1";
var host= "m12.cloudmqtt.com";
var port = 34115;
var user = "tjrehruv";
var pass = "FNBY4LmV_evX";

client = new Paho.MQTT.Client(host, Number(port), "clientId62");
// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;
// connect the client
client.connect({onSuccess:onConnect,userName : user, password : pass, useSSL: true});
// called when the client connects
function onConnect() {
  // Once a connection has been made, make a subscription and send a message.
  console.log("onConnect");
  client.subscribe("TEST/MQTT/HEROKU/1"); //Toppic sub
  //message = new Paho.MQTT.Message("Hello MJU"); //message pub
  message = new Paho.MQTT.Message("<?php echo $msg_mqtt; ?>"); //message pub
  message.destinationName = "TEST/MQTT/HEROKU/1"; //Toppic pub
  client.send(message);
}
// called when the client loses its connection
function onConnectionLost(responseObject) {
  if (responseObject.errorCode !== 0) {
    console.log("onConnectionLost:"+responseObject.errorMessage);
  }
}
// called when a message arrives
function onMessageArrived(message) {
  console.log("onMessageArrived:"+message.payloadString);
 // alert(message);
}
</script>

<?php
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

				//$s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/check_MSG.php?msg='.$text);			

				$msg_decode = json_decode($s_ans, true);
					foreach ($msg_decode['msg'] as $msg) {
						$msg_type = $msg['msg_type'];
					}

				$m_stat = $msg_decode['status'];
				$msg_type = $msg_decode['msg_type'];
				$id_userMSG = $msg_decode['id_userMSG'];
				

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



