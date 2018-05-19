<?php
function showcourse($courseReCode){
    $regex = '/(?<=<td>).*(?=</td>)/';
    $contents=$courseReCode;
    //preg_match_all('/<td([\s\S]*?)>([\s\S]*?)<\/tr>/',$contents,$matched);
    preg_match_all('#<td.+?>(.+?)</td>#',$contents,$matched);
    //print_r($matched);

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
    //print_r($coursearr);//本周42节课的信息-二维数组
    echo json_encode($coursearr,JSON_UNESCAPED_UNICODE);
}
