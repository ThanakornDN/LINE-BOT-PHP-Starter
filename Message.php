<?php

function find_ans($text) {
    $ans_arr =array();
    $ans_data =array();
    $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/check_MSG.php?msg='.$text);
    $msg_decode = json_decode($s_ans, true);
    $m_stat = $msg_decode['status'];
    if($m_stat == 1){
        $msg_ans = $s_ans;
    }else if($m_stat == 0){
		//$messages = [
	    	//$messages = [
		//	'type'=>'text',
		//	'text'=>$m_stat
		//];
	    	//array_push($ans_arr,$messages);
	    	$data= array("msg"=>"NO","type"=>"Message"); 
    
        $msg_ans =json_encode($data);
	    //$msg_ans = $s_ans;
    }else{

	    	$messages = [
			'type'=>'text',
			'text'=>'Error'
		];        
	    
        $msg_ans =json_encode($messages);
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
