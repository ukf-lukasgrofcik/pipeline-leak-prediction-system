<?php

require_once __DIR__ . '/../config/database.php';

function selectSQL($sql): array
{
    $connection = new mysqli(...DB_CONFIG);

    $result = $connection->query($sql);

    $connection->close();

    if (! $result) return [];

    $data = [];

    while ($row = $result->fetch_assoc()) $data[] = $row;

    return $data;
}

function truncateSQL($sql): void
{
    $connection = new mysqli(...DB_CONFIG);

    $connection->query($sql);

    $connection->close();
}

function insertSQL($sql): int
{
    $connection = new mysqli(...DB_CONFIG);

    $last_inserted_id = $connection->query($sql)
        ? $connection->insert_id
        : null;

    $connection->close();

    return $last_inserted_id;
}

function updateSQL($sql): void
{
    $connection = new mysqli(...DB_CONFIG);

    $connection->query($sql);

    $connection->close();
}