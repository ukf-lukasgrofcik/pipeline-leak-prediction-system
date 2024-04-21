<?php

require_once __DIR__ . '/helpers/sql.php';
require_once __DIR__ . '/helpers/curl.php';
require_once __DIR__ . '/helpers/mail.php';
require_once __DIR__ . '/config/monitoring.php';

$today = date('Y-m-d H:i:s', strtotime('today'));
$yesterday = date('Y-m-d H:i:s', strtotime('yesterday'));

$sql = "SELECT AVG(points.prediction) AS 'average_reconstruction_error'
        FROM points WHERE points.timestamp BETWEEN '$yesterday' AND
        '$today' AND points.prediction IS NOT null";

$avgRE = selectSQL($sql)[0]['average_reconstruction_error'];

$expRE = curlGet("http://host.docker.internal:5000/threshold")['threshold'];
$expRE = $expRE / 2;

$deviation = ($avgRE - $expectedRE) / $expectedRE;

if ($deviation < MONITOR_THRESHOLD) return;

sendMail(MONITOR_THRESHOLD, $expectedRE, $avgRE);