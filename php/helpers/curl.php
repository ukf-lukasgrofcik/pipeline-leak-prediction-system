<?php

function curlPost($url, $data): mixed
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response, true);
}

function curlGet($url): mixed
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response, true);
}
