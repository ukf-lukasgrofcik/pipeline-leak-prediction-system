<?php

function config()
{
    return [
        "mysql-db", // servername
        "root", // username
        "root_password", // password
        "your_database_name", // database
    ];
}

function getData($timestamp = null)
{
    $connection = new mysqli(...config());

    $sql_1 = is_null($timestamp)
        ? "SELECT * FROM points WHERE prediction IS NOT null ORDER BY timestamp DESC"
        : "SELECT * FROM points WHERE prediction IS NOT null AND timestamp > '$timestamp' ORDER BY timestamp DESC";
    $sql_2 = "SELECT MAX(timestamp) AS latest_timestamp FROM points WHERE prediction IS NOT null";

    $result_1 = $connection->query($sql_1);
    $result_2 = $connection->query($sql_2);

    if (! $result_1 || ! $result_2) return null;

    $data = [
        'latest_timestamp' => null,
        'data' => [],
    ];

    while ($row = $result_1->fetch_assoc()) {
        $data['data'][] = $row;
    }
    while ($row = $result_2->fetch_assoc()) {
        $data['latest_timestamp'] = $row['latest_timestamp'];
    }

    $connection->close();

    return $data;
}

$timestamp = isset($_GET['latest_timestamp']) ? $_GET['latest_timestamp'] : null;

$data = getData($timestamp);

header('Content-Type: application/json');
echo json_encode($data);