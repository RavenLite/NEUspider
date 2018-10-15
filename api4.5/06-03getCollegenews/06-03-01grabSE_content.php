
<?php
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
$link = $_GET["link"];

function getInfo(){
    global $link;
    // 1. 初始化
    $ch = curl_init();
    $login_url = "http://sc.neu.edu.cn".$link;
    $login_url = str_replace('\\','',$login_url);
    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
$output = getInfo();

$data = QueryList::Query($output,array(
    'content' => array('#vsb_content', 'text'),
    'img' => array('#vsb_content img', 'src')
))->getData(function($item){
    return $item;
});

$img='http://sc.neu.edu.cn'.$data[0]['img'];
$content = $data[0]['content'];
$article['content']=$content;
if($img!='http://sc.neu.edu.cn')
    $article['img']=$img;
else
    $article['img']="";
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
echo json_encode(array_to_object($article),JSON_UNESCAPED_UNICODE);

?>
