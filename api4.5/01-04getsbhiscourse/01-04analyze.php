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
	$newarr[0]=[$coursearr[0],$coursearr[1],$coursearr[2],$coursearr[3],$coursearr[4],$coursearr[5],$coursearr[6]];
		$newarr[1]=[$coursearr[7],$coursearr[8],$coursearr[9],$coursearr[10],$coursearr[11],$coursearr[12],$coursearr[13]];
		$newarr[2]=[$coursearr[14],$coursearr[15],$coursearr[16],$coursearr[17],$coursearr[18],$coursearr[19],$coursearr[20]];
		$newarr[3]=[$coursearr[21],$coursearr[22],$coursearr[23],$coursearr[24],$coursearr[25],$coursearr[26],$coursearr[27]];
		$newarr[4]=[$coursearr[28],$coursearr[29],$coursearr[30],$coursearr[31],$coursearr[32],$coursearr[33],$coursearr[34]];
		$newarr[5]=[$coursearr[35],$coursearr[36],$coursearr[37],$coursearr[38],$coursearr[39],$coursearr[40],$coursearr[41]];

    echo json_encode($newarr,JSON_UNESCAPED_UNICODE);
}
