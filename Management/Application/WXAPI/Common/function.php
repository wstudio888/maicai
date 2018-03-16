<?php

function jsonReturn($status = 0, $msg = '', $data = ''){
    if (empty($data))
        $data = '';
    $info['status'] = $status ? 1 : $status;
    $info['msg']    = $msg;
    $info['result'] = $data;
    exit(json_encode($info));
}

/**
 * @param string $url get请求地址
 * @param int    $httpCode 返回状态码
 * @return mixed
 */
function curl_get($url, &$httpCode = 0){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * @param string $url post请求地址
 * @param array  $params
 * @return mixed
 */
/**
 * @param string $url post请求地址
 * @param array  $params
 * @return mixed
 */
function wx_curl_post($url, array $params = array(), $imgPath){
    $data_string = json_encode($params);
    $ch          = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json'
        )
    );
    $data = curl_exec($ch);
    file_put_contents($imgPath, $data);
    return ($data);
}