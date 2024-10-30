<?php
namespace Backend\Products\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

/**
 * Classe para Gerenciamento de Tokens
 * 
 * @method mixed createToken(array $data) Gera o token JWT para o usuário após o login
 */
class TokenManager
{
    private $config;
    private $key;
    private $payload;
    private $algorithm;

    public function __construct()
    {
        $this->config = require __DIR__ . '/TokenConfig.php';
        $this->payload = $this->config['payload'];
        $this->algorithm = $this->config['algorithm'];
        $this->key = $this->config['secret_key'];
    }

    public function createToken(array $data)
    {
        $this->payload['user'] = [
            "username" => $data['name'],
            "email" => $data["email"],
            "userRole" => $data["userRole"],
        ];
        if (!$this->payload['user']['username'] || !$this->payload['user']['email'] || !$this->payload['user']['userRole']) {
            return "Os campos obrigatórios não foram fornecidos corretamente.";
        }

        try {
            $jwt = JWT::encode($this->payload, $this->key, $this->algorithm);
            return $jwt;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function refreshToken(int $user)
    {
        // TODO: Verificar ID do usuário no banco
        // - Se o usuário está bloqueado
        // - Se o e-mail está confirmado
        // Depois das verificações, se for válido, gerar um novo token
    }

    public function verifyToken()
    {
        $headers = getallheaders();
        $authorization = $headers['Authorization'];

        if (!isset($authorization)) {
            return "A requisição não foi autorizada.";
        }

        $jwt = explode(" ", $headers["Authorization"]);

        if (count($jwt) !== 2 || $jwt[0] !== "bearer") {
            http_response_code(401);
            return json_encode([
                "error" => "Formato do token inválido."
            ]);
        }

        try {
            $decoded = JWT::decode($jwt[1], new Key($this->key, $this->algorithm));
            return json_encode( [
                "data" => $decoded
            ]);
        } catch (Exception $e) {
            http_response_code(401);
            return json_encode([
                "error" => "Falha ao validar o token.",
                "message" => $e->getMessage()
            ]);
        }
    }
}
