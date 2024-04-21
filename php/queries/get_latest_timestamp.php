<?php

require_once __DIR__ . '/../helpers/sql.php';

function getLatestTimestampQuery(): string
{
    return "SELECT MAX(timestamp) AS latest_timestamp FROM points WHERE prediction IS NOT null";
}

function getLatestTimestamp(): string|null
{
    $sql = getLatestTimestampQuery();

    $result = selectSQL($sql);

    return isset($result[0]) ? $result[0]['latest_timestamp'] : null;
}