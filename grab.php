<?php
$login_url = 'https://zhjw.neu.edu.cn/ACTIONLOGON.APPPROCESS?mode=1&applicant=ACTIONQUERYSTUDENTSCHEDULEBYSELF';   //登录页面地址

$cookie_file = dirname(".")."/pic.cookie";    //cookie文件存放位置（自定义）

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_exec($ch);
curl_close($ch);