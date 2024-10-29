<?php
namespace Backend\Products\JWT;

return [
    "secret_key" => "c56862bddaf04d1b50fdb4a370fc11b3",
    "algorithm" => "HS256",
    "payload" => [
        "iss" => 'http://localhost:8080',
        "aud" => "http://localhost:9000",
        "iat" => time(),
        "nbf" => time(),
        "exp" => time() + 10800
    ]
];