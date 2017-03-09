<script src="mqtt.js"></script>
<script type="text/javascript">
// Create a client instance
var mes = "";
var sub = "TEST/MQTT/HEROKU/1";
var host= "m12.cloudmqtt.com";
var port = 34115;
var user = "tjrehruv";
var pass = "FNBY4LmV_evX";
 
<?php $msg_mqtt = "test_mqtt ms2g"; ?> 
 
 
client = new Paho.MQTT.Client(host, Number(port), "clientId60");
 
// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;
 
// connect the client
client.connect({onSuccess:onConnect,userName : user, password : pass, useSSL: true});
 
// called when the client connects
function onConnect() {
  // Once a connection has been made, make a subscription and send a message.
  console.log("onConnect");
  client.subscribe("TEST/MQTT/HEROKU/1");
//   message = new Paho.MQTT.Message("Hello Sv"); //message pub
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
}
 
function pub($text) {
  console.log("<?php echo $content; ?>");
  message = new Paho.MQTT.Message("<?php echo $text; ?>");
  message.destinationName = "INTNIN_LAB/CHAT_BOT";
  client.send(message); 
} 
</script>
