<?php
/**
 * Shared helper functions for the IT Laboratory website.
 */

date_default_timezone_set('Asia/Manila');

/** Escape a value for safe HTML output. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Trim and normalise a user-supplied string. */
function clean($value): string
{
    if (!is_string($value)) {
        return '';
    }
    return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
}

/** Return true when the value looks like a human name. */
function valid_name(string $name): bool
{
    return mb_strlen($name) >= 2
        && mb_strlen($name) <= 60
        && preg_match("/^[\p{L}\p{M}][\p{L}\p{M}\s.'-]*$/u", $name) === 1;
}

/** Send a JSON response and stop the script. */
function json_out(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** Block direct browser access to include-only files. */
function require_app(): void
{
    if (!defined('LAB_APP')) {
        header('Location: ../index.php');
        exit;
    }
}
