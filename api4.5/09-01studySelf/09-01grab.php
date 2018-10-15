
<?php
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
//error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');
header('Content-Type: text/html; charset=GBK');
$cookie = '';
$cookie_file = tempnam('./temp','cookie');
$stuID = $_GET["stuID"];
$stuPass = $_GET["stuPass"];
$WeekdayID = $_GET["WeekdayID"];
$ResultWeeks = $_GET["ResultWeeks"];
$STORYNO = $_GET["STORYNO"];


//第一次GET
function getInfo(){
    global $cookie, $cookie_file;
    $ch = curl_init();
    $login_url = 'https://zhjw.neu.edu.cn/index.jsp';
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    $output = curl_exec($ch);
    preg_match('/Set-Cookie:(.*);/iU',$output,$cookie);
    curl_close($ch);
    return $output;
}
$output = getInfo();
//echo $output;

//第一次POST
function sendPost(){
    global $cookie_file, $stuID, $stuPass;
    $ch = curl_init();
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
    $post_data = array(
        'WebUserNO' => $stuID,
        'Password' => $stuPass,
        'Agnomen' => '0',
        'submit7' => '%B5%C7%C2%BC'
    );
    $query = http_build_query($post_data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);//加入data
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//加入header
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//插入cookie
    curl_setopt($ch, CURLOPT_URL, 'https://zhjw.neu.edu.cn/ACTIONLOGON.APPPROCESS?mode=');//设置目标地址
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);//发送POST请求
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $data = curl_exec($ch);
    curl_close($ch);
}
sendPost();

//第二次POST
function getInfo2($StartSection, $EndSection){
    global $cookie_file, $WeekdayID, $ResultWeeks, $STORYNO;
    $ch = curl_init();
    $header = array(
        'Accept: application/json, text/javascript, */*; q=0.01',
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
    $post_data = array(
        'YearTermNO' => '14',
        'WeekdayID' => $WeekdayID,
        'StartSection' => $StartSection,
        'EndSection' => $EndSection,
        'WEEKTYPEID' => '1',
        'ResultWeeks' => $ResultWeeks,
        'STORYNO' => $STORYNO
    );
    $query = http_build_query($post_data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);//加入data
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//加入header
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//插入cookie
    curl_setopt($ch, CURLOPT_URL, 'https://zhjw.neu.edu.cn/ACTIONQUERYCLASSROOMNOUSE.APPPROCESS?mode=2');//设置目标地址
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);//发送POST请求
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function analyze($html){
    $rule = array(
        'name' => array('.color-row td, .color-rowNext td','text')
    );

    $data = QueryList::Query($html,$rule,'','','',true)->data;

    for ($i=0, $j=0; $i<sizeof($data);$i+=8, $j++){
        $classroom[$j]['name']=$data[$i+1]['name'];
        $classroom[$j]['name']=preg_replace('/\D/s', '', $classroom[$j]['name']);
        //$classroom[$j]['type']=$data[$i+2]['name'];
        //$classroom[$j]['number']=$data[$i+5]['name'];
        //$classroom[$j]['floor']=$data[$i+7]['name'];
    }
    return $classroom;
}

function array_to_object($array) {
    //print_r($array);
    if (is_array($array)) {
        $obj = new StdClass();

        foreach ($array as $key => $val){
            $obj->$key = $val;
        }
    }
    else { $obj = $array; }
    //var_dump($obj);
    return $obj;
}

$courseInfo1=getInfo2(1,2);
$courseInfo1=str_replace('&nbsp;','',$courseInfo1);
$classroom1=analyze($courseInfo1);

$courseInfo2=getInfo2(3,4);
$courseInfo2=str_replace('&nbsp;','',$courseInfo2);
$classroom2=analyze($courseInfo2);

$courseInfo3=getInfo2(5,6);
$courseInfo3=str_replace('&nbsp;','',$courseInfo3);
$classroom3=analyze($courseInfo3);

$courseInfo4=getInfo2(7,8);
$courseInfo4=str_replace('&nbsp;','',$courseInfo4);
$classroom4=analyze($courseInfo4);

$courseInfo5=getInfo2(9,10);
$courseInfo5=str_replace('&nbsp;','',$courseInfo5);
$classroom5=analyze($courseInfo5);

$courseInfo6=getInfo2(11,12);
$courseInfo6=str_replace('&nbsp;','',$courseInfo6);
$classroom6=analyze($courseInfo6);

$classroom[0]=$classroom1;
$classroom[1]=$classroom2;
$classroom[2]=$classroom3;
$classroom[3]=$classroom4;
$classroom[4]=$classroom5;
$classroom[5]=$classroom6;

//print_r($classroom);
echo json_encode($classroom,JSON_UNESCAPED_UNICODE);
?>
