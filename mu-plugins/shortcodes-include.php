<?php
/**
 * Plugin Name: BD Shortcodes – Include (MU)
 * Description: Provides [include filepath="..."] to include PHP templates from a safe directory.
 * Version: 0.1.0
 * Author: Martin Stoll
 */

defined('ABSPATH') || exit;

add_shortcode('include', function($atts) {

    $atts = shortcode_atts([
        'filepath' => '',
    ], $atts, 'include');

    $filepath = trim((string) $atts['filepath']);
    if ($filepath === '') {
        return '';
    }

    // Prevent code execution inside wp-admin / block editor previews.
    if (is_admin()) {
        return '[include filepath="' . esc_html($filepath) . '"]';
    }

    // Split optional query string: "file.php?foo=bar"
    $clean_path = $filepath;
    $include_query = [];

    if (strpos($filepath, '?') !== false) {
        [$clean_path, $query_string] = explode('?', $filepath, 2);
        parse_str($query_string, $include_query);
    }

    $clean_path = ltrim($clean_path, '/');

    // Basic path hardening (no traversal, no null bytes, no scheme).
    if (
        $clean_path === '' ||
        str_contains($clean_path, '..') ||
        str_contains($clean_path, "\0") ||
        preg_match('~^[a-zA-Z]+://~', $clean_path)
    ) {
        return '';
    }

    // Choose where include files live (safe allow-list directory).
    $include_dir = WP_CONTENT_DIR . '/bd-includes';

    $base = realpath($include_dir);
    $target = realpath($include_dir . '/' . $clean_path);

    // realpath() returns false if the file doesn't exist.
    if (!$base || !$target) {
        return '';
    }

    // Ensure the resolved target is inside the include directory.
    if (strpos($target, $base . DIRECTORY_SEPARATOR) !== 0) {
        return '';
    }

    if (!is_file($target) || !is_readable($target)) {
        return '';
    }

    // Optional: expose query args to included file:
    // - $include_query (array)
    // - $include_args (variables extracted, prefixed if you want)
    //
    // Keep it simple and predictable:
    // $include_query['foo'] is available.
    // If you want variables too, uncomment extract() below.
    // extract($include_query, EXTR_SKIP);

    ob_start();
    include $target;
    return (string) ob_get_clean();
});
