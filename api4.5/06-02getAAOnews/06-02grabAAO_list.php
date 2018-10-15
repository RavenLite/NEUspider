
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
            $type='xwzh';
            if($page==0){
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=0';
            }
            else{
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=0&page='.($page+1);
            }
            break;
        case 2:
            $type='tz';
            if($page==0){
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=3';
            }
            else{
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=3&page='.($page+1);
            }
            break;
        case 3:
            $type='gg';
            if($page==0){
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=4';
            }
            else{
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=4&page='.($page+1);
            }
            break;
        case 4:
            $type='jxgl';
            if($page==0){
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=5';
            }
            else{
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=5&page='.($page+1);
            }
            break;
        case 5:
            $type='szjj';
            if($page==0){
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=6';
            }
            else{
                $login_url = 'https://aao.neu.edu.cn/newsList.html?typeId=6&page='.($page+1);
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
    if($httpcode == '400'){
        echo '404';
        exit;
    }
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}
$output = getInfo();

preg_match_all('#da_span2\"> \[(.+?)\]#',$output,$matchedTime);
preg_match_all('#>
						<span>(.+?)</span>#',$output,$matchedName);
preg_match_all('#href=\"(.+?)\">
						<span>#',$output,$matchedHref);

for($i=0; $i<10; $i++){
    $item[$i]["type"]=$type;
    $item[$i]["time"]=$matchedTime[1][$i];
    $item[$i]["name"]=$matchedName[1][$i];
    $item[$i]["href"]=$matchedHref[1][$i];
	$item[$i]["href"]=str_replace('?','\?',$item[$i]["href"]);
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
