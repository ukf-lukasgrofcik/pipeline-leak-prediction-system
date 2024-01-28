<?php

function getPrediction($features)
{
    $features = array_map('floatval', $features);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => json_encode(compact('features')),
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_URL => "http://host.docker.internal:5000/predict",
        CURLOPT_RETURNTRANSFER => 1,
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response, true)['reconstruction_error'];
}

function config()
{
    return [
        "mysql-db", // servername
        "root", // username
        "root_password", // password
        "your_database_name", // database
    ];
}

function savePoints($f1, $f2, $p1, $p2, $timestamp)
{
    $connection = new mysqli(...config());

    $sql = "INSERT INTO points (f1, f2, p1, p2, timestamp) VALUES ('$f1', '$f2', '$p1', '$p2', '$timestamp')";

    $last_inserted_id = $connection->query($sql) === TRUE
        ? $connection->insert_id
        : null;

    $connection->close();

    return $last_inserted_id;
}

function getTimeframe()
{
    $connection = new mysqli(...config());

    $sql = "SELECT * FROM points ORDER BY timestamp DESC LIMIT 100;";

    $result = $connection->query($sql);

    if (! $result) return null;

    $data = [ 'F1' => [], 'F2' => [], 'P1' => [], 'P2' => [] ];

    while ($row = $result->fetch_assoc()) {
        $data['F1'][] = $row['f1'];
        $data['F2'][] = $row['f2'];
        $data['P1'][] = $row['p1'];
        $data['P2'][] = $row['p2'];
    }

    $f1 = array_reverse($data['F1']);
    $f2 = array_reverse($data['F2']);
    $p1 = array_reverse($data['P1']);
    $p2 = array_reverse($data['P2']);

    $features = array_merge($f1, $f2, $p1, $p2);

    $connection->close();

    return $features;
}

function savePrediction($id, $prediction)
{
    $connection = new mysqli(...config());

    $sql = "UPDATE points SET prediction = '$prediction' WHERE id = $id";

    $connection->query($sql);

    $connection->close();
}

$_POST = json_decode(file_get_contents('php://input'), true);

$f1 = $_POST['F1'];
$f2 = $_POST['F2'];
$p1 = $_POST['P1'];
$p2 = $_POST['P2'];
$timestamp = $_POST['timestamp'];

$id = savePoints($f1, $f2, $p1, $p2, $timestamp);
$features = getTimeframe();
if (sizeof($features) == 400) {
    $prediction = getPrediction($features);
    savePrediction($id, $prediction);
}