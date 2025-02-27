<?php

use GuzzleHttp\Client;
require_once '../models/Client.php';
require_once '../services/RedisService.php';
require_once '../services/S3Service.php';

class ClientController {
    private $client;
    private $redis;
    private $s3;

    public function __construct($db) {
        $this->client = new Client($db);
        $this->redis = new RedisService();
        $this->s3 = new S3Service();
    }

    public function getClients() {
        $data = $this->client->getAllClients();
        return json_encode($data);
    }

    public function createClient($data, $file) {
        $data['client_logo'] = $this->s3->uploadFile($file);
        $this->client->createClient($data);
        $this->redis->set("client:{$data['slug']}", $data);
        return json_encode(["message" => "Client created"]);
    }

    public function updateClient($slug, $data, $file) {
        $data['client_logo'] = $this->s3->uploadFile($file);
        $this->client->updateClient($slug, $data);
        $this->redis->delete("client:$slug");
        $this->redis->set("client:$slug", $data);
        return json_encode(["message" => "Client updated"]);
    }

    public function deleteClient($slug) {
        $this->client->deleteClient($slug);
        $this->redis->delete("client:$slug");
        return json_encode(["message" => "Client deleted"]);
    }
}
?>