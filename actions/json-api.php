<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    json_out(['success' => false, 'error' => 'Only GET requests are allowed on this endpoint.'], 405);
}

$users = [
    1 => ['id' => 1, 'name' => 'Jhonrie Solema', 'email' => 'jhonrie.solema@example.com', 'status' => 'active'],
    2 => ['id' => 2, 'name' => 'Maria Santos',   'email' => 'maria.santos@example.com',   'status' => 'active'],
    3 => ['id' => 3, 'name' => 'Jose Cruz',      'email' => 'jose.cruz@example.com',      'status' => 'inactive'],
];

$id = 1;
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        json_out(['success' => false, 'error' => 'The id parameter must be an integer.'], 400);
    }
}

if (!isset($users[$id])) {
    json_out(['success' => false, 'error' => "User with id $id was not found."], 404);
}

json_out(['success' => true, 'user' => $users[$id]]);
