<?php 
function call_mqtt(){
  //$output = shell_exec('php test_mqtt.php');
  
 echo '<script type="text/javascript">',
     'mqtt_s();',
     '</script>'
; 
  echo "MQTT";
}


?>

<script src="mqtt.js"></script>
<script type="text/javascript">
// Create a client instance
function mqtt_s(){
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
      message = new Paho.MQTT.Message("test2"); //message pub
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
}
</script>


<?php
echo "Test OK ";

?>
