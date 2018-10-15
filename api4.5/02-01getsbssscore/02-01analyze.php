<?php
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
function showcourse($html){
    $rule = array(
        'item' => array('.color-row td, .color-rowNext td','text')
    );
    //!!!大坑!!!head里面有中文，有乱码，需要先去除！
    $data = QueryList::Query($html,$rule,'','','',true)->data;

    for($i=0, $j=0; $i<sizeof($data); $i+=11, $j++){
        if(strcmp($data[$i+1]['item'],'')==2){
            $j--;
            continue;
        }

        $list[$j]['property']=$data[$i]['item'];
        $list[$j]['number']=$data[$i+1]['item'];
        $list[$j]['name']=$data[$i+2]['item'];
        $list[$j]['examtype']=$data[$i+3]['item'];
        $list[$j]['hour']=$data[$i+4]['item'];
        $list[$j]['credit']=$data[$i+5]['item'];
        $list[$j]['scoretype']=$data[$i+6]['item'];
        $list[$j]['common']=$data[$i+7]['item'];
        $list[$j]['medium']=$data[$i+8]['item'];
        $list[$j]['final']=$data[$i+9]['item'];
        $list[$j]['total']=$data[$i+10]['item'];
    }
    echo json_encode($list,JSON_UNESCAPED_UNICODE);
}
?>