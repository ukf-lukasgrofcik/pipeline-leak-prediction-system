<?php

require_once __DIR__ . '/../helpers/sql.php';

function getLatestPredictionsQuery($timestamp = null, $order_dir = 'ASC'): string
{
    return is_null($timestamp)
        ? "SELECT * FROM points WHERE prediction IS NOT null ORDER BY timestamp $order_dir"
        : "SELECT * FROM points WHERE prediction IS NOT null AND timestamp > '$timestamp' ORDER BY timestamp $order_dir";
}

function getLatestPredictions($timestamp = null): array
{
    $sql = getLatestPredictionsQuery($timestamp);

    $result = selectSQL($sql);

    return [
        'labels' => array_map(fn ($row) => $row['timestamp'], $result),
        'data' => array_map(fn ($row) => $row['prediction'], $result),
    ];
}

function getFullLatestPredictions($timestamp = null): array
{
    $sql = getLatestPredictionsQuery($timestamp, 'DESC');

    return selectSQL($sql);
}