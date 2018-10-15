
<?php
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
$cookie_file = tempnam('./temp','cookie');
$stuID = $_GET["cardID"];
$stuPass = $_GET["cardPass"];

//第一次GET
function getInfo(){
    global $cookie_file;
    // 1. 初始化
    $ch = curl_init();
    $login_url = 'http://ecard.neu.edu.cn/SelfSearch/Login.aspx';
    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
$output = getInfo();


function ocr($html){
    echo $html;
    //echo htmlspecialchars($html);
    $data = QueryList::Query($html,array(
        'href' => array('#imgRandom','src')
    ))->getData(function($item){
        return $item;
    });
    print_r($data);
}
$vaild=ocr($output);
//echo $output;


//第一次POST
function sendPost(){
    global $cookie_file, $stuID, $stuPass;
    //初始化curl连接
    $ch = curl_init();
    //设置header部分
    $header = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: en-US,en;q=0.9,zh;q=0.8,zh-CN;q=0.7',
        'Cache-Control: max-age=0',
        'Connection: keep-alive',
        //'Content-Length: '.$conlength,
        'Origin: http://ecard.neu.edu.cn',
        'Content-Type: application/x-www-form-urlencoded',
        'Host: ecard.neu.edu.cn',
        'Referer: http://ecard.neu.edu.cn/SelfSearch/Login.aspx',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'
    );
    //设置body数据
    $post_data = array(
        '__LASTFOCUS' => '0',
        '__EVENTTARGET' => 'btnLogin',
        '__EVENTARGUMENT' => '',
        'txtUserName' => $stuID,
        'txtPassword' => $stuPass,
        'txtVaildateCode' => '0',
        'hfIsManager' => '1'
    );
    //需要一个转化
    $query = http_build_query($post_data);
    //设置cURL参数
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);//加入data
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//加入header
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//插入cookie
    curl_setopt($ch, CURLOPT_URL, 'http://ecard.neu.edu.cn/SelfSearch/Login.aspx');//设置目标地址
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);//发送POST请求
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //运行
    $data = curl_exec($ch);
    echo $data;
    print_r($data);
    echo '*';
    //print_r($post_data);
    //print_r($header);
    //关闭请求
    curl_close($ch);
}
//sendPost();

//第二次GET
function getInfo2(){
    global $cookie_file;

    $ch = curl_init();
    $login_url = 'http://ecard.neu.edu.cn/SelfSearch/Index.aspx';
    // 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    //删除cookie临时文件
    unlink($cookie_file);
    //转码
    $contents = mb_convert_encoding($output, 'utf-8', 'GBK,UTF-8,ASCII');
    // 4. 释放curl句柄
    curl_close($ch);
    return $contents;
}
//$courseInfo = getInfo2();

//课程信息
//echo $courseInfo;
//echo showcourse($courseInfo);


?>
