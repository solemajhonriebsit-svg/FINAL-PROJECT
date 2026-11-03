<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    json_out(['success' => false, 'error' => 'Only POST requests are allowed. Send a JSON body.'], 405);
}

$raw = file_get_contents('php://input');

if ($raw === false || trim($raw) === '') {
    json_out(['success' => false, 'error' => 'The request body is empty. Send JSON data.'], 400);
}

$data = json_decode($raw, true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    json_out([
        'success' => false,
        'error'   => 'Invalid JSON: ' . json_last_error_msg() . '.',
    ], 400);
}

$errors = [];
$name  = isset($data['name'])  && is_string($data['name'])  ? clean($data['name'])  : '';
$email = isset($data['email']) && is_string($data['email']) ? trim($data['email'])  : '';
$age   = $data['age'] ?? null;

if ($name === '')  { $errors[] = 'Missing value: name'; }
if ($email === '') { $errors[] = 'Missing value: email'; }
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Invalid email address.'; }
if ($age === null || $age === '') { $errors[] = 'Missing value: age'; }
elseif (filter_var($age, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]) === false) {
    $errors[] = 'Age must be a whole number between 1 and 120.';
}

if ($errors) {
    json_out(['success' => false, 'error' => 'Validation failed.', 'details' => $errors], 422);
}

json_out([
    'success' => true,
    'message' => 'JSON received and decoded from php://input.',
    'received' => ['name' => $name, 'age' => (int) $age, 'email' => $email],
    'bytes_read' => strlen($raw),
]);
