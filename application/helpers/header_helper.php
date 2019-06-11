<?php

function GetStatus($statusCode)
{
    switch ($statusCode) {
        case 200:
            $msg = "OK";
            break;
        case 204:
            $msg = "No Content";
            break;
        case 400:
            $msg = "Bad Request";
            break;
        case 401:
            $msg = "Unauthorized";
            break;
        case 403:
            $msg = "Forbidden";
            break;
        case 404:
            $msg = "Not Found";
            break;
        case 408:
            $msg = "Request Time-out";
            break;
        default:
            $msg = "Error";
    }

    return (object)[
        "statusCode" => $statusCode,
        "message" => $msg,
    ];
}