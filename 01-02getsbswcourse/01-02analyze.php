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
    //print_r($coursearr);//本周42节课的信息-二维数组

    $arrlength=count($coursearr);
    for($i=0;$i<$arrlength;$i++) {
        $arrlength2 = count($coursearr[$i]);
        //如果数组长度为1，说明这个时间一直没有课，不用语义分析（正常每4个数组元素为一个课程的相关信息
        if($arrlength2==1){
            $weeklycoursearr[$i]['coursename']=' ';
            $weeklycoursearr[$i]['classroom']=' ';
        }
        //四个为一组进行检验
        else {
            if($arrlength2%4 <> 0){
                if(whether($coursearr[$i][2])) {
                    //传入的是字符串，含义是这门课的开课周
                    $weeklycoursearr[$i]['coursename'] = $coursearr[$i][0];
                    $weeklycoursearr[$i]['classroom'] = ' ';
                }
                else{
                    $weeklycoursearr[$i]['coursename']=' ';
                    $weeklycoursearr[$i]['classroom']=' ';
                }
            }
            for($j=3;$j<$arrlength2;$j+=4){
                if(whether($coursearr[$i][$j])) {
                    //传入的是字符串，含义是这门课的开课周
                    $weeklycoursearr[$i]['coursename'] = $coursearr[$i][$j - 3];
                    $weeklycoursearr[$i]['classroom'] = $coursearr[$i][$j - 1];
                    }
                    else{
                        $weeklycoursearr[$i]['coursename']=' ';
                        $weeklycoursearr[$i]['classroom']=' ';
                    }
            }
        }
		$newarr[0]=[$weeklycoursearr[0],$weeklycoursearr[1],$weeklycoursearr[2],$weeklycoursearr[3],$weeklycoursearr[4],$weeklycoursearr[5],$weeklycoursearr[6]];
		$newarr[1]=[$weeklycoursearr[7],$weeklycoursearr[8],$weeklycoursearr[9],$weeklycoursearr[10],$weeklycoursearr[11],$weeklycoursearr[12],$weeklycoursearr[13]];
		$newarr[2]=[$weeklycoursearr[14],$weeklycoursearr[15],$weeklycoursearr[16],$weeklycoursearr[17],$weeklycoursearr[18],$weeklycoursearr[19],$weeklycoursearr[20]];
		$newarr[3]=[$weeklycoursearr[21],$weeklycoursearr[22],$weeklycoursearr[23],$weeklycoursearr[24],$weeklycoursearr[25],$weeklycoursearr[26],$weeklycoursearr[27]];
		$newarr[4]=[$weeklycoursearr[28],$weeklycoursearr[29],$weeklycoursearr[30],$weeklycoursearr[31],$weeklycoursearr[32],$weeklycoursearr[33],$weeklycoursearr[34]];
		$newarr[5]=[$weeklycoursearr[35],$weeklycoursearr[36],$weeklycoursearr[37],$weeklycoursearr[38],$weeklycoursearr[39],$weeklycoursearr[40],$weeklycoursearr[41]];
    }
    //print_r($weeklycoursearr);
    echo json_encode($newarr,JSON_UNESCAPED_UNICODE);
}

/*判断该上课周区间是否在选定周内*/
function whether($temp){
    global $weekNo;
    //第一步拆分，按照.拆开
    $explodeing = explode(".", $temp);
    //print_r($explodeing);
    //长度为1的，说明只可能有一种课程，直接进行进一步检验
    if(count($explodeing)==1){
        return splitby($explodeing[0]);
    }
    //长度不为1的，对每种情况进行检验。有三种可能情况
    else{
        for ($i=0;$i<count($explodeing);$i++){
            if($explodeing[$i]==$weekNo)
                return true;
            if(splitby($explodeing[$i]))
                return true;
        }
        return false;
    }
}
/*用于拆分含有中文的元素*/
function splitby($explodeing){
    global $weekNo;
    $temp1=explode('  ', $explodeing );//按空格拆开
    //print_r($temp1);
    $temp2=explode("-",$temp1[0]);//按-拆开，得到起始周和终止周+“周”
    //print_r($temp2);
    $startweek = (int)$temp2[0];//起始周
    $patterns = "/\d+/";//正则表达式选出数字
    preg_match_all($patterns, $temp2[1],$endweekarr);
    $endweek=$endweekarr[0][0];//终止周
    if ($weekNo>=$startweek && $weekNo <= $endweek) {
        return true;
    }
    else{
        return false;
    }
}
?>