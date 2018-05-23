<?php
function showcourse($courseReCode){
    $contents=$courseReCode;
    preg_match_all('#<td.+?>(.+?)</td>#',$contents,$matched);
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
    $tempStr = end($matched[1]);
    preg_match('/\d+/',$tempStr,$tempArr);
    //print_r($tempArr);
    $pointer=11;
    for($i=0; $i<$tempArr[0]; $i++){
        $score[$i][0]=$matched[1][++$pointer];
        $score[$i][1]=$matched[1][++$pointer];
        $score[$i][2]=$matched[1][++$pointer];
        $score[$i][3]=$matched[1][++$pointer];
        $score[$i][4]=$matched[1][++$pointer];
        $score[$i][5]=$matched[1][++$pointer];
        $score[$i][6]=$matched[1][++$pointer];
        $score[$i][7]=$matched[1][++$pointer];
    }
    echo json_encode($score,JSON_UNESCAPED_UNICODE);
}
?>