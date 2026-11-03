<?php
require_once __DIR__ . '/../includes/functions.php';
require_app();

function handle_get_demo(): array
{
    $result = ['submitted' => false, 'errors' => [], 'student' => '', 'course' => ''];

    if (!isset($_GET['lab']) || $_GET['lab'] !== 'get') {
        return $result;
    }

    $result['submitted'] = true;
    $student = clean($_GET['student_name'] ?? '');
    $course  = clean($_GET['course'] ?? '');
    $result['student'] = $student;
    $result['course']  = $course;

    if ($student === '') {
        $result['errors'][] = 'Student name is missing or empty.';
    } elseif (!valid_name($student)) {
        $result['errors'][] = 'Student name contains invalid characters.';
    }

    if ($course === '') {
        $result['errors'][] = 'Course is missing or empty.';
    } elseif (mb_strlen($course) > 80) {
        $result['errors'][] = 'Course must be 80 characters or fewer.';
    }

    return $result;
}
