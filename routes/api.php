<?php
require '../app/config/database.php';
require '../app/controllers/ClientController.php';

$controller = new ClientController($db);

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {
    echo $controller->getClients();
} elseif ($requestMethod === 'POST') {
    echo $controller->createClient($_POST, $_FILES['client_logo']);
} elseif ($requestMethod === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    echo $controller->updateClient($_GET['slug'], $_PUT, $_FILES['client_logo']);
} elseif ($requestMethod === 'DELETE') {
    echo $controller->deleteClient($_GET['slug']);
}
?>