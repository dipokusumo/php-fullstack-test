<?php
require '../../vendor/autoload.php';

use Predis\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class RedisService {
    private $redis;

    public function __construct() {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => $_ENV['REDIS_HOST'],
            'port'   => $_ENV['REDIS_PORT'],
        ]);
    }

    public function set($key, $value) {
        $this->redis->setex($key, 3600, json_encode($value));
    }

    public function get($key) {
        $data = $this->redis->get($key);
        return $data ? json_decode($data, true) : null;
    }

    public function delete($key) {
        $this->redis->del([$key]);
    }
}
?>