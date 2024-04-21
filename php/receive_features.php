<?php

require_once __DIR__ . '/helpers/sql.php';
require_once __DIR__ . '/helpers/url.php';
require_once __DIR__ . '/helpers/curl.php';

$postParams = postParams(['F1', 'F2', 'P1', 'P2', 'timestamp']);

$sql = sprintf("INSERT INTO points (f1, f2, p1, p2, timestamp) VALUES ('%e', '%e', '%e', '%e', '%s')", ...$postParams);

$id = insertSQL($sql);

$result = selectSQL("SELECT * FROM points ORDER BY timestamp DESC LIMIT 100;");

$features = array_merge(...array_map(fn ($col) => array_reverse(array_column($result, $col)), ['f1', 'f2', 'p1', 'p2']));

if (sizeof($features) != 400) return;

$data = [ 'features' => array_map('floatval', $features) ];

$prediction = curlPost("http://host.docker.internal:5000/predict", $data)['reconstruction_error'];

updateSQL("UPDATE points SET prediction = '$prediction' WHERE id = $id");