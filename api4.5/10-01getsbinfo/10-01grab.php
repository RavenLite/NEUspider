
<?php
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
$cookie = '';
$cookie_file = tempnam('./temp','cookie');
$stuID = $_GET["stuID"];
$stuPass = $_GET["stuPass"];

//第一次GET
function getInfo(){
    global $cookie, $cookie_file;
    // 1. 初始化
    $ch = curl_init();
    $login_url = 'https://zhjw.neu.edu.cn/index.jsp';
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
//echo $output;
//第一次POST
function sendPost(){
    global $cookie_file, $stuID, $stuPass;
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
        'Referer: https://zhjw.neu.edu.cn/index.jsp',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'
    );
    //设置body数据
    $post_data = array(
        'WebUserNO' => $stuID,
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
    //print_r($data);
    //print_r($post_data);
    //print_r($header);
    //关闭请求
    curl_close($ch);
}
sendPost();

//第二次GET
function getInfo2(){
    global $cookie, $cookie_file;

    $ch = curl_init();
    $login_url = 'https://zhjw.neu.edu.cn/ACTIONQUERYBASESTUDENTINFO.APPPROCESS';
    // 设置选项，包括URL
    //$header = array();
    //$header[] = 'Cookie:'.$cookie;
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    if($output === FALSE ){
    }
    $info = curl_getinfo($ch);
    //删除cookie临时文件
    unlink($cookie_file);
    //转码
    $contents = mb_convert_encoding($output, 'utf-8', 'GBK,UTF-8,ASCII');
    // 4. 释放curl句柄
    curl_close($ch);
    return $contents;
}

function analyze($html){
    $rule = array(
        'name' => array('td','text')
    );
    $data = QueryList::Query($html,$rule,'','','',true)->data;

    $info['姓名']=$data[5]['name'];
    $info['性别']=$data[9]['name'];
    $info['生源省市']=$data[11]['name'];
    $info['出生日期']=$data[13]['name'];
    $info['民族']=$data[15]['name'];
    $info['身份证号']=$data[17]['name'];
    $info['英文姓名']=$data[19]['name'];
    $info['入学日期']=$data[23]['name'];
    $info['层次']=$data[25]['name'];
    $info['学习形式']=$data[27]['name'];
    $info['学制']=$data[29]['name'];
    $info['院校名称']=$data[31]['name'];
    $info['学院']=$data[33]['name'];
    $info['考生号']=$data[35]['name'];
    $info['专业名称']=$data[37]['name'];
    $info['班号']=$data[39]['name'];
    $info['当前所在级']=$data[41]['name'];
    $info['学号']=$data[43]['name'];
    $info['录取年份']=$data[45]['name'];
    $info['录取年份']=$data[45]['name'];
    $info['录取批次']=$data[47]['name'];
    $info['培养方式']=$data[49]['name'];
    $info['学历类别']=$data[51]['name'];
    $info['总分']=$data[53]['name'];
    $info['招生季节']=$data[55]['name'];
    $info['学籍状态']=$data[57]['name'];

    return $info;
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

$courseInfo = getInfo2();
$info = analyze($courseInfo);

echo json_encode(array_to_object($info),JSON_UNESCAPED_UNICODE);
?>
