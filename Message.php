<?php

function find_ans($text) {
    $ans_arr =array();
    $ans_data =array();
    $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$text);
    $msg_decode = json_decode($s_ans, true);
    $m_stat = $msg_decode['status'];
    if($m_stat == 'S1'){
        //$msg_ans =$m_stat;
        foreach ($msg_decode['msg'] as $msg) {
            $msg_dc = $msg['type'];
        }
        array_push($ans_data,$msg_dc);
        $ans_arr= array("data"=>$ans_data); 
        $msg_ans=json_encode($ans_arr);
        
    }else if($m_stat == 'S0'){
        $msg_ans =$m_stat;
    }else{
        $msg_ans ='Error';
    }
    
    //$msg_stat = $msg_decode['msg'];
    //$events = json_decode($msg_text, true);
        //foreach ($events['events'] as $event) {
            //$text = $event['message']['text'];
        //}           
    

    //if($msg_stat=='S0'){
    //    return '019';
    //}else if($msg_stat=='S1'){
    //    return $s_ans;
    //}else{
    //    return 'error';
    //}
    
    
    
    return $msg_ans; 
}

?>
