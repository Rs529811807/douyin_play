<?php
function GetVideos($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25"]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function GetUrl($url)
{
    $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
//URL
$url = $_GET['url'];
    //下面是正则去除中文字符保留http或者https链接
    $str_r= '/(http:\/\/|https:\/\/)((\w|=|\?|\.|\/|&|-)+)/';
    //preg_match_all函数自行百度什么意思
    preg_match_all($str_r,$url,$arr);
    //获得带http或者https链接
    $url=$arr[0][0];

if (empty($url)) {
    echo json_encode(['code' => 0, 'msg' => '请输入正确视频网址']);
}else{
    $data = GetUrl($url);
    //获取
    preg_match('/playAddr: "(?<url>[^"]+)"/i', $data, $url);
    preg_match('/<p class="desc">(?<desc>[^<>]*)<\/p>/i', $data, $name);
    $name = $name['desc'];
    $url = $url['url'];
    if(empty($url))
    {
        echo json_encode(['code' => 0, 'msg' => '解析错误']);
        exit;
    }
    
    preg_match('/s_vid=(.*?)&/', $url, $id);
    $url = 'https://aweme.snssdk.com/aweme/v1/play/?s_vid=' . $id[1] . '&line=0';
    $data_new = GetVideos($url);
    preg_match('/<a href=\"http:\/\/(.*?)\">/', $data_new, $link);
    
    if (empty($link[1])) {
        echo json_encode(['code' => 0, 'msg' => '解析错误']);
        exit;
    }
    
    $link = 'http://' . $link[1];
	echo $link;
    //echo json_encode(['code' => 1, 'name' => $name, 'url' => $link]);
}

// echo $link;
?>