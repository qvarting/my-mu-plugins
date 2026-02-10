<?php
/**
 * Plugin Name: QMD – Query Monitor Debug Helpers (MU)
 * Description: Small helper functions for logging to Query Monitor via qm/debug.
 * Author: Martin Stoll
 * Version: 0.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'qmd_enabled' ) ) {
    /**
     * Determines whether QMD logging is enabled for the current request.
     *
     * Enabled when:
     * - define('QMD', true) is set in wp-config.php, OR
     * - ?debug (any value) is present in the URL
     *
     * And requires:
     * - current_user_can('administrator')
     * - Query Monitor's 'qm/debug' hook to be available
     */
    function qmd_enabled(): bool {
        if ( ! has_action( 'qm/debug' ) ) {
            return false;
        }

        if ( ! current_user_can( 'administrator' ) ) {
            return false;
        }

        if ( defined( 'QMD' ) && QMD ) {
            return true;
        }

        return isset( $_GET['debug'] );
    }
}

if ( ! function_exists( 'qmd' ) ) {
    /**
     * Log a single value to Query Monitor.
     *
     * @param mixed  $value  Any value: string, array, object, WP_Query, etc.
     * @param string $label  Optional label logged before the value.
     */
    function qmd( $value, string $label = '' ): void {
        if ( ! qmd_enabled() ) {
            return;
        }

        if ( $label !== '' ) {
            do_action( 'qm/debug', $label );
        }

        do_action( 'qm/debug', $value );
    }
}

if ( ! function_exists( 'qmds' ) ) {
    /**
     * Log multiple values: qmds($a, $b, $c)
     */
    function qmds( ...$values ): void {
        if ( ! qmd_enabled() ) {
            return;
        }

        foreach ( $values as $v ) {
            do_action( 'qm/debug', $v );
        }
    }
}

if ( ! function_exists( 'qmdp' ) ) {
    /**
     * Force string output using print_r().
     * Useful when you explicitly want text output.
     */
    function qmdp( $value, string $label = '' ): void {
        qmd( print_r( $value, true ), $label );
    }
}

if ( ! function_exists( 'qmd_last_query' ) ) {
    /**
     * Convenience helper: log the last executed SQL query.
     */
    function qmd_last_query( string $label = 'Last SQL query' ): void {
        if ( ! qmd_enabled() ) {
            return;
        }

        global $wpdb;
        if ( isset( $wpdb ) && isset( $wpdb->last_query ) ) {
            qmd( $wpdb->last_query, $label );
        }
    }
}
