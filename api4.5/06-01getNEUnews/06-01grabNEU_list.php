
<?php
//第一次GET
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
$page = $_GET['page'];
$class = $_GET['class'];
$type='';

function getInfo(){
    global $page, $class, $type;
    // 1. 初始化
    $ch = curl_init();
    switch ($class){
        case 1:
            $type='ddyw';
            if($page==0){
                $login_url = 'http://neunews.neu.edu.cn/campus/part/DDYW.html';
            }
            else{
                $login_url = 'http://neunews.neu.edu.cn/campus/part/DDYW_'.$page.'.html';
            }
            break;
        case 2:
            $type='mtdd';
            if($page==0){
                $login_url = 'http://neunews.neu.edu.cn/campus/part/MTDD.html';
            }
            else{
                $login_url = 'http://neunews.neu.edu.cn/campus/part/MTDD_'.$page.'.html';
            }
            break;
        case 3:
            $type='bwjx';
            if($page==0){
                $login_url = 'http://neunews.neu.edu.cn/campus/part/BWJX.html';
            }
            else{
                $login_url = 'http://neunews.neu.edu.cn/campus/part/BWJX_'.$page.'.html';
            }
            break;
        case 4:
            $type='xshd';
            if($page==0){
                $login_url = 'http://neunews.neu.edu.cn/campus/part/XSHD.html';
            }
            else{
                $login_url = 'http://neunews.neu.edu.cn/campus/part/XSHD_'.$page.'.html';
            }
            break;
        case 5:
            $type='xkjs';
            if($page==0){
                $login_url = 'http://neunews.neu.edu.cn/campus/part/XKJS.html';
            }
            else{
                $login_url = 'http://neunews.neu.edu.cn/campus/part/XKJS_'.$page.'.html';
            }
            break;
        case 6:
            $type='zsjy';
            if($page==0){
                $login_url = 'http://neunews.neu.edu.cn/campus/part/ZSJY.html';
            }
            else{
                $login_url = 'http://neunews.neu.edu.cn/campus/part/ZSJY_'.$page.'.html';
            }
            break;
        case 7:
            $type='kaoyan';
            if($page==0){
                $login_url = 'http://neunews.neu.edu.cn/kaoyan/';
            }
            else{
                $login_url = 'http://neunews.neu.edu.cn/kaoyan/list_'.$page.'.html';
            }
            break;
    }

    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    if($httpcode == '404'){
        echo '404';
        exit;
    }
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
$output = getInfo();

preg_match_all('#<li><span>(.+?)</span><a href=#',$output,$matchedTime);
preg_match_all('#\" >(.+?)</a></li>#',$output,$matchedName);
for($i=0; $i<30; $i++){
    $matchedName[$i]=str_replace('<em>','',$matchedName[$i]);
    $matchedName[$i]=str_replace('</em>','',$matchedName[$i]);
    $matchedName[$i]=str_replace('&ldquo;','',$matchedName[$i]);
    $matchedName[$i]=str_replace('&rdquo;','',$matchedName[$i]);
    $matchedName[$i]=str_replace('&mdash;','',$matchedName[$i]);
    $matchedName[$i]=str_replace('·','',$matchedName[$i]);
}
preg_match_all('#</span><a href=\"(.+?)\" >#',$output,$matchedHref);
for($i=0; $i<30; $i++){
    $item[$i]['type']=$type;
    $item[$i]["time"]=$matchedTime[1][$i];
    $item[$i]["name"]=$matchedName[1][$i];
    $item[$i]["href"]=$matchedHref[1][$i];
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

echo json_encode(array_to_object($item),JSON_UNESCAPED_UNICODE);
?>
