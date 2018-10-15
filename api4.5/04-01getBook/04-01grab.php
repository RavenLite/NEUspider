<?php
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
error_reporting(E_ALL^E_NOTICE^E_WARNING);
//可以先手动获取要采集的页面源码
$bookname=$_GET['bookname'];
$page=$_GET['page'];
function getInfo(){
    global $bookname, $page;
    $ch = curl_init();
    if($page==1){
        $login_url = 'http://202.118.8.7:8991/F?func=find-b&find_code=WRD&request='.$bookname.'&filter_code_1=WLN&filter_request_1=&filter_code_2=WYR&filter_request_2=&filter_code_3=WYR&filter_request_3=&filter_code_4=WFM&filter_request_4=&filter_code_5=WSL&filter_request_5=';
    }
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);

    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
$output = getInfo();
//然后可以把页面源码或者HTML片段传给QueryList
/*$data = QueryList::html($output)->rules([  //设置采集规则
    // 采集所有a标签的href属性
    'bookname' => ['.itemtitle a:even','text'],
    'bookstate' => ['.libs  a', 'text']
])->query()->getData();*/

$data = QueryList::Query($output,array(
    'bookname' => array('.itemtitle a:even','text'),
    'bookstate' => array('.libs  a', 'text')
))->getData(function($item){
    return $item;
});

$data2 = QueryList::Query($output,array(  //设置采集规则
    'item' => array('tr .content','text')
))->getData(function($item){
    return $item;
});
for($i=0, $j=0; $i<60; $i+=6, $j++)
{
    $author[$j]=$data2[$i]['item'];
}
for($i=1, $j=0; $i<60; $i+=6, $j++)
{
    $searchno[$j]=$data2[$i]['item'];
}
for($i=2, $j=0; $i<60; $i+=6, $j++)
{
    $press[$j]=$data2[$i]['item'];
}
for($i=3, $j=0; $i<60; $i+=6, $j++)
{
    $year[$j]=$data2[$i]['item'];
}

$data3 = QueryList::Query($output,array(  //设置采集规则
    'item' => array('a','onmouseover')
))->getData(function($item){
    return $item;
});

$data4 = QueryList::Query($output,array(  //设置采集规则
    'img' => array('table .items img','src')
))->getData(function($item){
    return $item;
});
for($i=0, $j=0; $i<30; $i+=3, $j++)
{
    $img[$j]=$data4[$i]['img'];
}

for($i=36, $j=0; $i<86; $i+=5, $j++)
{
    $location[$j]=$data3[$i]['item'];
    $location[$j]=str_replace('clearTimeout(tm);hint(\'','',$location[$j]);
    $location[$j]=str_replace('\',this','',$location[$j]);
    $location[$j]=$str = preg_replace("/<a[^>]*>(.*?)<\/a>/is", "$1", $location[$j]);
    $location[$j]=$str = preg_replace("/<td[^>]*>(.*?)<\/td>/is", "$1", $location[$j]);
    $location[$j]=$str = preg_replace("/<td[^>]*>(.*?)/is", "$1", $location[$j]);
    $location[$j]=$str = preg_replace("/<tr[^>]*>(.*?)/is", "$1", $location[$j]);
}
//打印结果
for($i=0; $i<10; $i++)
{
    $book[$i]['bookname']=$data[$i]['bookname'];
    $book[$i]['bookstate']=$data[$i]['bookstate'];
    $book[$i]['author']=$author[$i];
    $book[$i]['searchno']=$searchno[$i];
    $book[$i]['press']=$press[$i];
    $book[$i]['year']=$year[$i];
    $book[$i]['location']=$location[$i];
    $book[$i]['img']=$img[$i];
}
function array_to_object($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }

    return (object)$arr;
}

echo json_encode(array_to_object($book),JSON_UNESCAPED_UNICODE);
?>