<?php
/**
 * 抓取知乎表情包（https://www.zhihu.com/question/35242408）
 * @author 周仕林<875199116@qq.com> 2016-04-17
 */
$savePath = 'f:/zhihu_expression_package';

$result = Curl::request('get', 'https://www.zhihu.com/question/35242408');
preg_match_all('/<img src="(https:\/\/.*?)"/m', $result, $images);

foreach ($images[1] as $image) {
    preg_match('/\/(.*)\/(.*?).(jpg|gif|png|bmp)/', $image, $out);
    $fileContent = file_get_contents($image);

    $saveFile = $savePath . '/' . $out[2] . '.' . $out[3];
    $fp = @fopen($saveFile, 'w');
    @fwrite($fp, $fileContent);
    @fclose($fp);
}

/**
 * Curl请求类
 * @author 周仕林<875199116@qq.com> 2016-04-17
 */
class Curl
{
    public static $cookie = '_zap=8f516686-efb6-4c84-9cd2-af654c278df5; l_n_c=1; q_c1=a9180e05c0d44a6b95c2d6d06190922d|1460868292000
|1460868292000; cap_id="MTBmMDJlNzFjODcwNDE4YTg0NWM5ZjM3MjZjYmM5MGQ=|1460868292|3603954ffed71dcd5ca9db3e086acd70fe9dca4e"
; l_cap_id="OGExODE2MzhmYWU5NDRiZDg1ZTI5NDI0ZDFlZWVkYzk=|1460868292|1a48f0e7d48d87050a1f4705b8a8675f76570d58"
; n_c=1; d_c0="AGDAHfK1yAmPTgzomlw-pukE81VIaD_btzQ=|1460868291"; _za=8d3eb255-ec20-4c22-bbed-9f8e032
de5b8';

    /**
     * 发起请求
     * @param string $method 请求方式
     * @param string $url 请求地址
     * @param array $fields 参数
     * @return mixed
     */
    public static function request($method, $url, $fields = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIE, self::$cookie);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/x-www-form-urlencoded',
            'Connection: keep-alive',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        ));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}