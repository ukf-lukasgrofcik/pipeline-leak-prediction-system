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

function truncatePoints()
{
    $connection = new mysqli(...config());

    $sql = "TRUNCATE TABLE points";

    $connection->query($sql);

    $connection->close();
}

truncatePoints();