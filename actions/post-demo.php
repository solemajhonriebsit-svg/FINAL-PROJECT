<?php
require_once __DIR__ . '/../includes/functions.php';
require_app();

function handle_post_demo(): array
{
    $result = [
        'submitted' => false, 'errors' => [],
        'student' => '', 'title' => '', 'description' => '', 'received_at' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['form'] ?? '') !== 'post') {
        return $result;
    }

    $result['submitted'] = true;
    $student = clean($_POST['student_name'] ?? '');
    $title   = clean($_POST['activity_title'] ?? '');
    $desc    = trim((string) ($_POST['activity_description'] ?? ''));

    $result['student'] = $student;
    $result['title'] = $title;
    $result['description'] = $desc;

    if ($student === '') {
        $result['errors'][] = 'Student name is required.';
    } elseif (!valid_name($student)) {
        $result['errors'][] = 'Student name contains invalid characters.';
    }

    if ($title === '') {
        $result['errors'][] = 'Activity title is required.';
    } elseif (mb_strlen($title) > 100) {
        $result['errors'][] = 'Activity title must be 100 characters or fewer.';
    }

    if ($desc === '') {
        $result['errors'][] = 'Activity description is required.';
    } elseif (mb_strlen($desc) > 1000) {
        $result['errors'][] = 'Activity description must be 1000 characters or fewer.';
    }

    if (!$result['errors']) {
        $result['received_at'] = date('F j, Y \a\t g:i:s A');
    }

    return $result;
}
