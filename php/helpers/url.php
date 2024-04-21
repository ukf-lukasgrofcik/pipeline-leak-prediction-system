<?php

function urlParam($name, $defaultValue = null): mixed
{
    return $_GET[$name] ?? $defaultValue;
}

function postParams($names): array
{
    $post = json_decode(file_get_contents('php://input'), true);

    return array_map(fn ($param) => $post[$param], $names);
}