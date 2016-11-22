<?php

function find_ans($text) {
    $ans_arr =array();
    $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$text);
    $msg_decode = json_decode($s_ans, true);
    $msg_stat = $msg_decode['msg']['text'];
    $mss = [
            'data'=>[$msg_decode]
            ];
    $maa = json_encode($mss);
    
    $data1 = json_decode($maa, true);
    foreach ($data1['data'] as $data2) {
        $re = $data2['msg']['type'];
    }
    
    return $msg_stat;
    //if($msg_stat=='S0'){
    //    return '019';
    //}else if($msg_stat=='S1'){
    //    return $s_ans;
    //}else{
    //    return 'error';
    //}
    
    
    
    //$ans=$s_ans;
  //return $ans;
}

?>
