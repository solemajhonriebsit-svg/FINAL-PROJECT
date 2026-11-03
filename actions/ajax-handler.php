<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    json_out(['success' => false, 'errors' => ['Only POST requests are allowed.']], 405);
}

usleep(600000);

$name   = clean($_POST['name'] ?? '');
$email  = trim((string) ($_POST['email'] ?? ''));
$course = clean($_POST['course'] ?? '');
$errors = [];

if ($name === '') {
    $errors[] = 'Name is required.';
} elseif (!valid_name($name)) {
    $errors[] = 'Name contains invalid characters.';
}

if ($email === '') {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email address is not valid.';
}

if ($course === '') {
    $errors[] = 'Course is required.';
} elseif (mb_strlen($course) > 80) {
    $errors[] = 'Course must be 80 characters or fewer.';
}

if ($errors) {
    json_out(['success' => false, 'errors' => $errors], 422);
}

json_out([
    'success' => true,
    'message' => "Thanks, $name! Your information was processed by PHP.",
    'data' => ['name' => $name, 'email' => $email, 'course' => $course],
    'server_time' => date('c'),
]);
