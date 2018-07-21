
<?php
/*
require '../support/phpQuery.php';
require '../support/QueryList.php';
use QL\QueryList;
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header('Content-Type:application/json');

function getInfo(){
    $ch = curl_init();
    $url = "https://hdtv.neu6.edu.cn/live-wall";
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
$output = getInfo();

$data = QueryList::Query($output,array(
    'href' => array('#list-wall a', 'href'),
    'img' => array('#list-wall img', 'data-original'),
    'title' => array('#list-wall img', 'title')
))->getData(function($item){
    return $item;
});

//print_r($data);
*/
$channel[0]['title']='CCTV1综合';
$channel[0]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv1hd_s.png';
$channel[0]['addr']='https://media2.neu6.edu.cn/hls/cctv1hd.m3u8';
$channel[1]['title']='CCTV2财经';
$channel[1]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv2hd_s.png';
$channel[1]['addr']='https://media2.neu6.edu.cn/hls/cctv2hd.m3u8';
$channel[2]['title']='CCTV3综艺';
$channel[2]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv3hd_s.png';
$channel[2]['addr']='https://media2.neu6.edu.cn/hls/cctv3hd.m3u8';
$channel[3]['title']='CCTV4中文国际';
$channel[3]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv4hd_s.png';
$channel[3]['addr']='https://media2.neu6.edu.cn/hls/cctv4hd.m3u8';
$channel[4]['title']='CCTV5体育';
$channel[4]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv5hd_s.png';
$channel[4]['addr']='https://media2.neu6.edu.cn/hls/cctv5hd.m3u8';
$channel[5]['title']='CCTV5+体育赛事';
$channel[5]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv5phd_s.png';
$channel[5]['addr']='https://media2.neu6.edu.cn/hls/cctv5phd.m3u8';
$channel[6]['title']='CCTV6电影';
$channel[6]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv6hd_s.png';
$channel[6]['addr']='https://media2.neu6.edu.cn/hls/cctv6hd.m3u8';
$channel[7]['title']='CCTV7军事农业';
$channel[7]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv7hd_s.png';
$channel[7]['addr']='https://media2.neu6.edu.cn/hls/cctv7hd.m3u8';
$channel[8]['title']='CCTV8电视剧';
$channel[8]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv8hd_s.png';
$channel[8]['addr']='https://media2.neu6.edu.cn/hls/cctv8hd.m3u8';
$channel[9]['title']='CCTV9记录';
$channel[9]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv9hd_s.png';
$channel[9]['addr']='https://media2.neu6.edu.cn/hls/cctv9hd.m3u8';
$channel[10]['title']='CCTV10科教';
$channel[10]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv10hd_s.png';
$channel[10]['addr']='https://media2.neu6.edu.cn/hls/cctv10hd.m3u8';
$channel[11]['title']='CCTV11戏曲';
$channel[11]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv11hd_s.png';
$channel[11]['addr']='https://media2.neu6.edu.cn/hls/cctv11hd.m3u8';
$channel[12]['title']='CCTV12社会与法';
$channel[12]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv12hd_s.png';
$channel[12]['addr']='https://media2.neu6.edu.cn/hls/cctv12hd.m3u8';
$channel[13]['title']='CCTV13新闻';
$channel[13]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv13_s.png';
$channel[13]['addr']='https://media2.neu6.edu.cn/hls/cctv13.m3u8';
$channel[14]['title']='CCTV14少儿';
$channel[14]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv14hd_s.png';
$channel[14]['addr']='https://media2.neu6.edu.cn/hls/cctv14hd.m3u8';
$channel[15]['title']='CCTV15音乐';
$channel[15]['img']='https://hdtv.neu6.edu.cn/wall/img/cctv15_s.png';
$channel[15]['addr']='https://media2.neu6.edu.cn/hls/cctv15.m3u8';

$channel[16]['title']='北京卫视';
$channel[16]['img']='https://hdtv.neu6.edu.cn/wall/img/btv1hd_s.png';
$channel[16]['addr']='https://media2.neu6.edu.cn/hls/btv1hd.m3u8';
$channel[17]['title']='湖南卫视';
$channel[17]['img']='https://hdtv.neu6.edu.cn/wall/img/hunanhd_s.png';
$channel[17]['addr']='https://media2.neu6.edu.cn/hls/hunanhd.m3u8';
$channel[18]['title']='浙江卫视';
$channel[18]['img']='https://hdtv.neu6.edu.cn/wall/img/zjhd_s.png';
$channel[18]['addr']='https://media2.neu6.edu.cn/hls/zjhd.m3u8';
$channel[19]['title']='江苏卫视';
$channel[19]['img']='https://hdtv.neu6.edu.cn/wall/img/jshd_s.png';
$channel[19]['addr']='https://media2.neu6.edu.cn/hls/jshd.m3u8';
$channel[20]['title']='东方卫视';
$channel[20]['img']='https://hdtv.neu6.edu.cn/wall/img/dfhd_s.png';
$channel[20]['addr']='https://media2.neu6.edu.cn/hls/dfhd.m3u8';
$channel[21]['title']='安徽卫视';
$channel[21]['img']='https://hdtv.neu6.edu.cn/wall/img/ahhd_s.png';
$channel[21]['addr']='https://media2.neu6.edu.cn/hls/ahhd.m3u8';
$channel[22]['title']='黑龙江卫视';
$channel[22]['img']='https://hdtv.neu6.edu.cn/wall/img/hljhd_s.png';
$channel[22]['addr']='https://media2.neu6.edu.cn/hls/hljhd.m3u8';
$channel[23]['title']='辽宁卫视';
$channel[23]['img']='https://hdtv.neu6.edu.cn/wall/img/lnhd_s.png';
$channel[23]['addr']='https://media2.neu6.edu.cn/hls/lnhd.m3u8';

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
echo json_encode(array_to_object($channel),JSON_UNESCAPED_UNICODE);

?>
