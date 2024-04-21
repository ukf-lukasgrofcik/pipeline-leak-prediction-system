<?php

require_once __DIR__ . '/queries/get_predictions.php';
require_once __DIR__ . '/queries/get_latest_timestamp.php';
require_once __DIR__ . '/helpers/response.php';
require_once __DIR__ . '/helpers/url.php';
require_once __DIR__ . '/helpers/curl.php';

$timestamp = urlParam('latest_timestamp');

$data = getLatestPredictions($timestamp);

$data['latest_timestamp'] = getLatestTimestamp();

if (! $timestamp) $data['threshold'] = curlGet("http://host.docker.internal:5000/threshold")['threshold'];

response($data);