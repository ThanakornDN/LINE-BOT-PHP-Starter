<?php

function find_ans($text) {
    $ans_arr =array();
    $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$text);
    $msg_decode = json_decode($s_ans, true);
    $msg_stat = $msg_decode['status'];
    //$msg_data = $s_ans;
    $mm = $msg_decode['data'];
    $ans_arr = $mm;
    if($msg_stat=='S0'){
        return '012';
    }else if($msg_stat=='S1'){
        return $ans_arr;
    }else{
        return 'err';
    }
    
    
    
    //$ans=$s_ans;
  //return $ans;
}

?>
