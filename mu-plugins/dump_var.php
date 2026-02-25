<?php
/**
 * Plugin Name: Dev Dump
 * Description: Simple readable debug dump helper.
 * Author: Martin Stoll
 * Version: 0.1.0
 */

defined('ABSPATH') || exit;

/**
 * Determines if dumping is allowed.
 */
function dump_var_enabled(): bool {

    if ( ! current_user_can('administrator') ) {
        return false;
    }

    if ( defined('WP_DEBUG') && WP_DEBUG ) {
        return true;
    }

    if ( isset($_GET['debug']) ) {
        return true;
    }

    return false;
}

/**
 * Pretty dump wrapper for var_dump().
 *
 * @param mixed  $var
 * @param string $label Optional label
 */
function dump_var( $var, string $label = '' ): void {

    if ( ! dump_var_enabled() ) {
        return;
    }

    static $style_printed = false;

    if ( ! $style_printed ) {
        echo '<style>
            .dev-dump-wrapper {
                background:#1e1e1e;
                color:#d4d4d4;
                padding:16px;
                margin:16px 0;
                border-radius:8px;
                font-size:13px;
                line-height:1.4;
                overflow:auto;
                box-shadow:0 4px 12px rgba(0,0,0,0.2);
            }
            .dev-dump-label {
                color:#4FC3F7;
                font-weight:bold;
                margin-bottom:8px;
                display:block;
            }
        </style>';
        $style_printed = true;
    }

    echo '<div class="dev-dump-wrapper">';

    if ( $label !== '' ) {
        echo '<span class="dev-dump-label">' . esc_html($label) . '</span>';
    }

    echo '<pre>';
    var_dump($var);
    echo '</pre>';

    echo '</div>';
}
