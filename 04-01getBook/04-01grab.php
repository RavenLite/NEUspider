
<?php
//第一次GET
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
require_once '04-01analyze.php';
$request = $_GET["request"];

function getInfo(){
    global $request;
    // 1. 初始化
    $ch = curl_init();
    $login_url = 'http://202.118.8.7:8991/F?func=find-b&find_code=WRD&request='.$request.'&filter_code_1=WLN&filter_request_1=&filter_code_2=WYR&filter_request_2=&filter_code_3=WYR&filter_request_3=&filter_code_4=WFM&filter_request_4=&filter_code_5=WSL&filter_request_5=';
    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    preg_match('/Set-Cookie:(.*);/iU',$output,$cookie);
    $info = curl_getinfo($ch);
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
$output = getInfo();

//图书信息
//echo $output;
echo showcourse($output);
?>
