
<?php
//第一次GET
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
$cookie = '';
$cookie_file = tempnam('./temp','cookie');
$stuID = $_GET["stuID"];
$stuPass = $_GET["stuPass"];
function getInfo(){
    global $cookie, $cookie_file;
    // 1. 初始化
    $ch = curl_init();
    $login_url = 'https://zhjw.neu.edu.cn/ACTIONLOGON.APPPROCESS?mode=1&applicant=ACTIONQUERYSTUDENTSCHEDULEBYSELF';
    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    preg_match('/Set-Cookie:(.*);/iU',$output,$cookie);
    $info = curl_getinfo($ch);
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
$output = getInfo();

//第一次POST
function sendPost(){
    global $cookie, $cookie_file, $stuID, $stuPass;
    //初始化curl连接
    $ch = curl_init();
    //设置header部分
    $header = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding: gzip, deflate, br',
        'Accept-Language: en-US,en;q=0.9,zh;q=0.8,zh-CN;q=0.7',
        'Connection: keep-alive',
        //'Content-Length: '.$conlength,
        'Cache-Control: max-age=0',
        'Origin: https://zhjw.neu.edu.cn',
        'Content-Type: application/x-www-form-urlencoded',
        'Host: zhjw.neu.edu.cn',
        'Referer: https://zhjw.neu.edu.cn/ACTIONLOGON.APPPROCESS?mode=1&applicant=ACTIONQUERYSTUDENTSCHEDULEBYSELF',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'
    );
    //设置body数据
    $post_data = array(
        'WebUserNO' => $stuID,
        'applicant' => 'ACTIONQUERYSTUDENTSCHEDULEBYSELF',
        'Password' => $stuPass,
        'Agnomen' => '0',
        'submit7' => '%B5%C7%C2%BC'
    );
    //需要一个转化
    $query = http_build_query($post_data);
    //设置cURL参数
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);//加入data
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//加入header
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//插入cookie
    curl_setopt($ch, CURLOPT_URL, 'https://zhjw.neu.edu.cn/ACTIONLOGON.APPPROCESS?mode=');//设置目标地址
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);//发送POST请求
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //运行
    $data = curl_exec($ch);
    $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //关闭请求
    curl_close($ch);
    echo json_encode($httpcode);
}
sendPost();
?>
