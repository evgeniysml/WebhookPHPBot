<?php

const TOKEN = '';

const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';

function send_request($method, $params = [])
{
    $url = BASE_URL . $method;
    if (!empty($params)) {
        $url = BASE_URL . $method . '?' . http_build_query($params);
    }
    return json_decode(file_get_contents($url));
}