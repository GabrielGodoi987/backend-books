<?php
namespace Backend\Products\JWT;

return [
    "secret_key" => "",
    "algorithm" => "",
    "payload" => [
        "iss" => 'localhost:8080',
        "aud" => "*",
        "iat" => time(),
        "nbf" => time(),
        "exp" => time() * 10800
    ]
];