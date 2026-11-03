<?php
require_once __DIR__ . '/../includes/functions.php';

$file = __DIR__ . '/../student_data.xml';

if (!is_readable($file)) {
    json_out(['success' => false, 'error' => 'student_data.xml could not be found or read.'], 404);
}

libxml_use_internal_errors(true);
$xml = simplexml_load_file($file);

if ($xml === false) {
    $messages = array_map(fn($err) => trim($err->message), libxml_get_errors());
    libxml_clear_errors();
    json_out(['success' => false, 'error' => 'The XML file could not be parsed.', 'details' => $messages], 500);
}

$students = [];
foreach ($xml->student as $student) {
    $students[] = [
        'id'     => (string) $student->id,
        'name'   => (string) $student->name,
        'course' => (string) $student->course,
        'year'   => (string) $student->year,
    ];
}

json_out(['success' => true, 'count' => count($students), 'students' => $students]);
