<?php 
namespace Backend\Products\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenManager{
    private $config;
    private $key;
    private $algorithm;
}