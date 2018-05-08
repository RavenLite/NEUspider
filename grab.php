<?php
require_once 'AipOcr.php';
$cookie = '';
$cookie_file = tempnam('./temp','cookie');
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
if($output === FALSE ){
    echo "CURL Error:".curl_error($ch);
}
$info = curl_getinfo($ch);
echo ' 获取 '.$info['url'].'耗时'.$info['total_time'].'秒';
// 4. 释放curl句柄
curl_close($ch);
return $output;
}

function getAddress($str){
    preg_match_all('/<img.*?src="(.*?)".*?>/is',$str,$array);
    return $array ;
}
?>
<html lang="en">
<head>
    <title>东北大学教务处镜像</title>
    <style>
        .largeInfo{
            height: 400px;
            width: 400px;
        }
        .littleInfo{
            height: 50px;
            width: 700px;
        }
    </style>
</head>
<body>
<p>登录界面源码</p>
<textarea class="littleInfo">
<?php
$output = getInfo();
echo $output;?>
</textarea>

<p>验证码地址</p>
<textarea class="littleInfo">
<?php
$src = getAddress($output);
$urlsrc = 'https://zhjw.neu.edu.cn/'.$src[1][2];
echo $urlsrc;
$img = file_get_contents($urlsrc);
file_put_contents('agnomn.jpg',$img);
?>
</textarea>

<p>验证码识别结果</p>
<textarea class="littleInfo">
    <?php

    function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);

        return $data;
    }

    $url = 'https://aip.baidubce.com/oauth/2.0/token';
    $post_data['grant_type']       = 'client_credentials';
    $post_data['client_id']      = '4HOpoMNSN0qglszOForZnBDi';
    $post_data['client_secret'] = 'vXp88DeGcEilR9uqaw1ERemEbtCaYbHV';
    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);

    $res1 = request_post($url, $post_data);

    //var_dump($res1);
    $json_string = json_decode($res1,true);
    $token = $json_string['access_token'];

    function request_post2($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        // 初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);
        echo $data;
        return $data;
    }

    $url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general?access_token=' . $token;
    $img = file_get_contents('./agnomn.jpg');
    $img = base64_encode($img);
    $bodys = array(
        "image" => $img
    );
    $res = request_post($url, $bodys);
    $json_string2 = json_decode($res,true);
    $result = $json_string2['words_result'][0]['words'];
    echo $result;
    ?>
</textarea>

<p>验证码计算结果</p>
<textarea class="littleInfo">
<?php
$char1 = substr($result,0,1);
$char2 = substr($result,1,1);
$char3 = substr($result,2,1);
//将第一位和第三位的数字转成int类型
$num1 = (int)$char1;
$num2 = (int)$char3;
if($char2 == '+')
{
    $agnomn = $num1 + $num2;
}
else
{
    $agnomn = $num1 * $num2;
}
echo $agnomn;
?>
</textarea>

<p>Cookie</p>
<?php
var_dump($cookie);
?>

<p>发送POST1</p>
<textarea class="largeInfo">
<?php
function sendPost(){
    global $cookie, $cookie_file, $agnomn;
    $ch = curl_init();
    $header = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Encoding: gzip, deflate, br',
        'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
        'Connection: keep-alive',
        'Content-Length: 110',
        'Content-Type: application/x-www-form-urlencoded',
        'Host: zhjw.neu.edu.cn',
        'Referer: https://zhjw.neu.edu.cn/ACTIONLOGON.APPPROCESS?mode=',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0'
    );
    //设置post数据
    $post_data = array(
        'WebUserNO' => '20165086',
        'applicant' => 'ACTIONQUERYSTUDENTSCHEDULEBYSELF',
        'Password' => 'neu5086',
        'Agnomen' => $agnomn,
        'submit7' => '%B5%C7%C2%BC'
    );
    print_r($post_data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);//加入data
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);//加入header
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//插入cookie
    curl_setopt($ch, CURLOPT_URL, 'https://zhjw.neu.edu.cn/ACTIONLOGON.APPPROCESS?mode=');//设置目标地址
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POST, true);//发送POST请求
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    var_dump($ch);
    $data = curl_exec($ch);
    echo curl_getinfo($ch, CURLINFO_HEADER_OUT);
    ?>
    <?php
    //var_dump($cookie);
    ?>
    <?php
    curl_close($ch);
    print_r($data);
}
sendPost();
?>
</textarea>
<p>发送GET</p>
<?php
function getInfo2(){
    global $cookie, $cookie_file;
// 1. 初始化
    $ch = curl_init();
    $login_url = 'https://zhjw.neu.edu.cn/ACTIONQUERYSTUDENTSCHEDULEBYSELF.APPPROCESS';
// 2. 设置选项，包括URL
    //$header = array();
    //$header[] = 'Cookie:'.$cookie;
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    //curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    if($output === FALSE ){
        echo "CURL Error:".curl_error($ch);
    }
    $info = curl_getinfo($ch);
    echo ' 获取 '.$info['url'].'耗时'.$info['total_time'].'秒';
// 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
echo getInfo2();
?>
<textarea class="largeInfo">

</textarea>
</body>
</html>
