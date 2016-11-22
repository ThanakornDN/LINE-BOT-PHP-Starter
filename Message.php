<?php

function find_ans($text) {
    $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$text);
    $msg_decode = json_decode($s_ans, true);
    $msg_stat = $msg_decode['status'];
    $msg_data = $msg_decode['data'][0];
    if($msg_stat=='S0'){
        return '001';
    }else if($msg_stat=='S1'){
        return $msg_data;
    }else{
        return 'err';
    }
    
    
    
    //$ans=$s_ans;
  //return $ans;
}

?>
