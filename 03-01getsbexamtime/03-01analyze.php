<?php
function showcourse($courseReCode){
    $contents=$courseReCode;
    preg_match_all('#nowrap>&nbsp;(.+?);</td>#',$contents,$matched);
    $counter = 0;
    $index = 0;
    foreach($matched[1] as $tmp){
        $counter++;
        if($counter<14 || $counter==21 || $counter==29 || $counter==37 || $counter==45 || $counter==53 || $counter>60)
            continue;
        preg_match_all('#^(.+?)<br style#',$tmp,$matched_first);
        preg_match_all('#cell">(.+?)<br style#',$tmp,$matched_medium);
        $tmpp = strrev($tmp);
        preg_match_all('#^(.+?)>"llec-emas:#',$tmpp,$matched_last);
        //print_r($matched_medium);
        //print_r($matched_first);
        //print_r($matched_last);
        $matched_last[1][0] = strrev($matched_last[1][0]);
        $coursearr[$index]=array_merge($matched_first[1],$matched_medium[1],$matched_last[1]);
        $index++;
    }
    //print_r($matched);
    $pointer=5;
    for($i=0; $i<10; $i++){
        $examTime[$i][0]=$matched[1][++$pointer];
        $examTime[$i][1]=$matched[1][++$pointer];
        $examTime[$i][2]=$matched[1][++$pointer];
        $examTime[$i][3]=$matched[1][++$pointer];
        $examTime[$i][4]=$matched[1][++$pointer];
        $examTime[$i][5]=$matched[1][++$pointer];
        $examTime[$i][6]=$matched[1][++$pointer];
        $examTime[$i][7]=$matched[1][++$pointer];
    }
    echo json_encode($examTime,JSON_UNESCAPED_UNICODE);
}
?>