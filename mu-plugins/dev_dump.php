<?php
/**
 * Plugin Name: Dev Dump Toolkit
 * Description: dump_var(), r_print(), and dd() helpers for development.
 * Author: Martin Stoll
 * Version: 0.2.0
 */

defined('ABSPATH') || exit;

/**
 * Check if dumping is allowed.
 */
function dev_dump_enabled(): bool {

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
 * Internal renderer.
 */
function dev_dump_render( string $output, string $label = '' ): void {

    static $style_printed = false;

    if ( ! dev_dump_enabled() ) {
        return;
    }

    if ( ! $style_printed ) {
        echo '<style>
            .dev-dump-wrapper {
                background:#1e1e1e;
                color:#d4d4d4;
                padding:16px;
                margin:16px 0;
                border-radius:8px;
                font-size:13px;
                line-height:1.5;
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
    echo $output;
    echo '</pre>';

    echo '</div>';
}

/**
 * dump_var() – wrapper for var_dump()
 */
function dump_var( $var, string $label = '' ): void {

    ob_start();
    var_dump($var);
    $output = ob_get_clean();

    dev_dump_render($output, $label);
}

/**
 * r_print() – wrapper for print_r()
 */
function r_print( $var, string $label = '' ): void {

    $output = print_r($var, true);
    dev_dump_render($output, $label);
}

/**
 * dd() – dump and die
 */
function dd( $var, string $label = '' ): void {

    dump_var($var, $label);
    die();
}
