<?php 
function call_mqtt(){
  $output = shell_exec('php test_mqtt.php');
  echo "OK";
}

echo "Test OK ";
?>
