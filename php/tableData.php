<?php

require_once __DIR__ . '/helpers/response.php';
require_once __DIR__ . '/helpers/url.php';
require_once __DIR__ . '/queries/get_predictions.php';
require_once __DIR__ . '/queries/get_latest_timestamp.php';

$timestamp = urlParam('latest_timestamp');

$data = [
    'data' => getFullLatestPredictions($timestamp),
    'latest_timestamp' => getLatestTimestamp(),
];

response($data);