<?php
function JwtDataFormate($hash)
{
    $formattedHash = str_replace("=", "", $hash);
    return $formattedHash;
}

function JwtHeaderPayload($header, $payload)
{
    return $header . "." . $payload;
}

function GetToken($header, $payload, $verifySignature)
{
    return $header . "." . $payload . "." . $verifySignature;
}

function GetTokenObject($jwt)
{
    $expJwt = explode(".", $jwt);
    return (object)[
        "header" => $expJwt[0],
        "payload" => $expJwt[1],
        "verifySignature" => $expJwt[2]
    ];
}

function GetUserDataFromPayload($payload)
{
    return (object)[
        "ID" => $payload->ID,
        "Name" => $payload->Name,
        "Auth" => $payload->Auth
    ];
}