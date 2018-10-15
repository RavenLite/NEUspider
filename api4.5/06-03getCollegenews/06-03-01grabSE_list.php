
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
            $type='tzgg';
            if($page==0){
                $login_url = 'http://sc.neu.edu.cn/index/tzgg.htm';
            }
            else{
                $login_url = 'http://sc.neu.edu.cn/index/tzgg/'.$page.'.htm';
            }
            break;
        case 2:
            $type='xyxw';
            if($page==0){
                $login_url = 'http://sc.neu.edu.cn/index/xyxw.htm';
            }
            else{
                $login_url = 'http://sc.neu.edu.cn/index/xyxw/'.$page.'.htm';
            }
            break;
        case 3:
            $type='bkstz';
            if($page==0){
                $login_url = 'http://sc.neu.edu.cn/index/bkstz.htm';
            }
            else{
                $login_url = 'http://sc.neu.edu.cn/index/bkstz/'.$page.'.htm';
            }
            break;
        case 4:
            $type='yjstz';
            if($page==0){
                $login_url = 'http://sc.neu.edu.cn/index/yjstz.htm';
            }
            else{
                $login_url = 'http://sc.neu.edu.cn/index/yjstz/'.$page.'.htm';
            }
            break;
        case 5:
            $type='xsgz';
            if($page==0){
                $login_url = 'http://sc.neu.edu.cn/index/xsgztz.htm';
            }
            else{
                $login_url = 'http://sc.neu.edu.cn/index/xsgztz/'.$page.'.htm';
            }
            break;
        case 6:
            $type='rcpy';
            if($page==0){
                $login_url = 'http://sc.neu.edu.cn/rcpy1.htm';
            }
            else{
                $login_url = 'http://sc.neu.edu.cn/rcpy1/'.$page.'.htm';
            }
            break;
        case 7:
            $type='xsyd';
            if($page==0){
                $login_url = 'http://sc.neu.edu.cn/xsyd.htm';
            }
            else{
                $login_url = 'http://sc.neu.edu.cn/xsyd/'.$page.'.htm';
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
//echo $output;

preg_match_all('#style=\"float:right\">(.+?)</span></li>#',$output,$matchedTime);
preg_match_all('#htm\">(.+?)</a><span#',$output,$matchedName);
preg_match_all('#\"><a href=\"..(.+?)\">#',$output,$matchedHref);

for($i=0; $i<10; $i++){
    $item[$i]["type"]=$type;
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
