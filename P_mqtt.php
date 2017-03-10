<?php 
function call_mqtt(){
  $output = shell_exec('php test_mqtt.php');
}

echo "Test OK";
?>
