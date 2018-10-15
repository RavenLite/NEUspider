
<?php
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
$cookie = '';
$cookie_file = tempnam('./temp','cookie');
$stuID = $_GET["stuID"];
$stuPass = $_GET["libPass"];
$sessionID= '';
//http://202.118.8.7:8991/F/VEHXTJ5MRFCCG1EH6FNBKX54BGU2A61LQSMM59CUM2E3YXA41S-09692?func=file&file_name=login-session
//http://202.118.8.7:8991/F/-?func=bor-info
//第一次GET
function getInfo(){
    global $cookie, $cookie_file, $sessionID;
    // 1. 初始化
    $ch = curl_init();
    $login_url = 'http://202.118.8.7:8991/F/-?func=bor-info';
    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    preg_match('/Set-Cookie:(.*);/iU',$output,$cookie);
	preg_match('#http://202.118.8.7:8991/F/(.+?)";</script>#',$output,$tmp);
	$sessionID = $tmp[1];
    $info = curl_getinfo($ch);
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
$output = getInfo();

//第一次POST
function sendPost(){
    global $cookie_file, $stuID, $stuPass, $sessionID;
    //初始化curl连接
    $ch = curl_init();
    //设置header部分
    $header = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: en-US,en;q=0.9,zh;q=0.8,zh-CN;q=0.7',
        'Connection: keep-alive',
        //'Content-Length: '.$conlength,
        'Cache-Control: no-cache',
        'Origin: http://202.118.8.7:8991',
        'Content-Type: application/x-www-form-urlencoded',
        'Host: 202.118.8.7:8991',
        'Referer: http://202.118.8.7:8991/F/'.$sessionID.'?func=file&file_name=login-session',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',
        'Pragma: no-cache'

    );
    //设置body数据
    $post_data = array(
        'func' => 'login-session',
        'login_source' => 'bor-info',
        'bor_id' => $stuID,
        'bor_verification' => $stuPass,
        'bor_library' => 'NEU50',
    );
    //需要一个转化
    $query = http_build_query($post_data);
    //设置cURL参数
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);//加入data
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//加入header
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//插入cookie
    curl_setopt($ch, CURLOPT_URL, 'http://202.118.8.7:8991/F/'.$sessionID);//设置目标地址
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);//发送POST请求
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //运行
    $data = curl_exec($ch);
    $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //关闭请求
    curl_close($ch);
    return $data;
}
$data=sendPost();
//echo $data;

//第二次GET
function getInfo2(){
    global $cookie_file, $sessionID;
    // 1. 初始化
    $ch = curl_init();
    $login_url = 'http://202.118.8.7:8991/F/'.$sessionID.'?func=bor-loan&adm_library=NEU50';
    // 2. 设置选项，包括URL
    $post_data = array(
        'func' => 'bor-loan',
        'adm_library' => 'NEU50'
    );
    $header = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: en-US,en;q=0.9,zh;q=0.8,zh-CN;q=0.7',
        'Connection: keep-alive',
        //'Content-Length: '.$conlength,
        'Cache-Control: no-cache',
        'Origin: http://202.118.8.7:8991',
        'Content-Type: application/x-www-form-urlencoded',
        'Host: 202.118.8.7:8991',
        'Referer: http://202.118.8.7:8991/F/'.$sessionID.'?func=bor-info',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',
        'Pragma: no-cache'

    );
    //需要一个转化
    $query = http_build_query($post_data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);//加入data
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//加入header
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//插入cookie
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}

$html= getInfo2();
//echo $html;
function analyze($html){
    $rule = array(
        'name' => array('.td1','text')
    );
    $data = QueryList::Query($html,$rule,'','','',true)->data;

    for ($i=1, $j=0; $i<sizeof($data); $i+=11, $j++){
        $list[$j]['No']=$data[$i]['name'];
        $list[$j]['author']=$data[$i+2]['name'];
        $list[$j]['name']=$data[$i+3]['name'];
        $list[$j]['year']=$data[$i+4]['name'];
        $list[$j]['date']=$data[$i+5]['name'];
        $list[$j]['fine']=$data[$i+6]['name'];
        $list[$j]['library']=$data[$i+7]['name'];
        $list[$j]['searchNo']=$data[$i+8]['name'];
    }
    return $list;
}
$list=analyze($html);

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

echo json_encode(array_to_object($list),JSON_UNESCAPED_UNICODE);
?>
