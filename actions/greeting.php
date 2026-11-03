<?php
require_once __DIR__ . '/../includes/functions.php';
require_app();

function handle_greeting(): array
{
    $result = ['submitted' => false, 'errors' => [], 'name' => '', 'greeting' => ''];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['form'] ?? '') !== 'greeting') {
        return $result;
    }

    $result['submitted'] = true;
    $name = clean($_POST['greeting_name'] ?? '');
    $result['name'] = $name;

    if ($name === '') {
        $result['errors'][] = 'Please enter your name before submitting.';
    } elseif (!valid_name($name)) {
        $result['errors'][] = 'Name must be 2-60 characters and contain only letters, spaces, dots, hyphens or apostrophes.';
    }

    if (!$result['errors']) {
        $hour = (int) date('G');
        $part = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
        $result['greeting'] = "$part, $name! Welcome to the IT Laboratory.";
    }

    return $result;
}
