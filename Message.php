<?php

function find_ans($text) {
    $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$text);
    $ans="Hello1";
  return $ans;
}

?>
