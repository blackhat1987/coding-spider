<?php
require_once "library/Curl.php";

$curl = new \SmallDuck\Curl();
$curl->setURL('https://coding.net/api/tweet/public_tweets?filter=true&size=20&sort=time');
$curl->setOption(CURLOPT_PROXY, '127.0.0.1:8888'); //使用Fiddler进行抓包
$curl->setOption(CURLOPT_COOKIE, 'sid=bc21196d-2400-41a5-a736-3240639cd8b6; c=coding-cli%3Dfalse%2Cproject_tweet%3Dtrue%2Csvn%3Dtrue%2C09585353; exp=89cd78c2; frontlog_sample_rate=1; code=coding-cli%3Dfalse%2Clint%3Dfalse%2Cproject_tweet%3Dtrue%2Csvn%3Dtrue%2Cteam%3Dfalse%2C080a5450; _ga=GA1.2.641420016.1460517328; _gat=1');
$curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
$result = $curl->exec();
var_dump($result);