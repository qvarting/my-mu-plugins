<?php
/**
 * Plugin Name: SH Log (MU)
 * Description: Small logging helper for the Simple History plugin.
 * Version: 0.1.0
 * Author: Martin Stoll
 */

defined('ABSPATH') || exit;

if ( ! function_exists('sh_log') ) {
    /**
     * Log to Simple History (wp-admin -> Simple History).
     *
     * Log levels: debug, info, notice, warning, error, critical, alert, emergency
     *
     * @param string      $title
     * @param string|null $message
     * @param string      $level
     * @param array       $context Optional extra data (will be JSON-encoded in message).
     */
    function sh_log( string $title, ?string $message = null, string $level = 'info', array $context = [] ): void {

        // Simple History exposes this filter; if it's not present we silently do nothing.
        if ( ! has_filter('simple_history_log') ) {
            return;
        }

        // Keep message readable; optionally append context.
        $parts = [];
        if ($message !== null && $message !== '') {
            $parts[] = $message;
        }
        if (!empty($context)) {
            $parts[] = wp_json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $text = implode("\n", $parts);

        /**
         * Simple History expects:
         * apply_filters('simple_history_log', $title, [ $text ], $level);
         */
        apply_filters(
            'simple_history_log',
            $title,
            [ $text ],
            $level
        );
    }
}
