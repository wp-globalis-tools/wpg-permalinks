<?php
/**
 * Plugin Name:         WPG Permalinks
 * Plugin URI:          https://github.com/wp-globalis-tools/wpg-permalinks
 * Description:         Adds some permalinks functions
 * Author:              Pierre Dargham, Matthieu Guerry, Globalis Media Systems
 * Author URI:          https://github.com/wp-globalis-tools/
 *
 * Version:             1.0.0
 * Requires at least:   4.0.0
 * Tested up to:        4.6.0
 */

namespace WPG\Permalinks;

/**
 * Return fallback permalink
 * Filtering the value is possible with wpgp_fallback
 */

function get_permalink_fallback() {
	return apply_filters( 'wpgp_fallback', home_url( '/' ) );
}

/**
 * Return front permalink
 */

function get_permalink_front() {
	return home_url( '/' );
}

/**
 * Return blog permalink
 */

function get_permalink_blog() {
	$id = get_option( 'page_for_posts', false );

    if ( empty( $id ) ) {
        return get_permalink_fallback();
    }

    return get_permalink_by_id( $id );
}

/**
 * Return permalink by page/post id
 */


function get_permalink_by_id( $id ) {
    $permalink = get_permalink( $id );

    if ( empty( $permalink ) ) {
        return get_permalink_fallback();
    }

    return $permalink;
}

/**
 * Return permalink by page/post slug
 */

function get_permalink_by_slug( $slug ) {
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'pagename'       => $slug,
    );

    $pages = new \WP_Query( $args );

    if ( empty( $pages->posts ) || count( $pages->posts ) > 1 ) {
        return get_permalink_fallback();
    }

    return get_permalink( reset( $pages->posts )->ID );
}

/**
 * Return permalink by page template
 */

function get_permalink_by_template( $template ) {
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'template-' . $template . '.php',
    );

    $pages = new \WP_Query( $args );

    if ( empty( $pages->posts ) || count( $pages->posts ) > 1 ) {
        return get_permalink_fallback();
    }

    return get_permalink( reset( $pages->posts )->ID );
}

/**
 * WooCommerce : Adds WooCommerce permalinks functions
 */

if( class_exists( 'WooCommerce'  ) ) {

    /**
     * WooCommerce : Return checkout permalink
     */

    function wc_get_permalink_checkout() {
        $id = get_option( 'woocommerce_checkout_page_id', false );

        if ( empty( $id ) ) {
            return get_permalink_fallback();
        }

        return get_permalink_by_id( $id );
    }

    /**
     * WooCommerce : Return terms permalink
     */

    function wc_get_permalink_terms() {
        $id = get_option( 'woocommerce_terms_page_id', false );

        if ( empty( $id ) ) {
            return get_permalink_fallback();
        }

        return get_permalink_by_id( $id );
    }

    /**
     * WooCommerce : Return account root page permalink
     */

    function wc_get_permalink_account_root_page() {
        $id = get_option( 'woocommerce_myaccount_page_id', false );

        if ( empty( $id ) ) {
            return false;
        }

        return get_permalink_by_id( $id );
    }

    /**
     * WooCommerce : Return shop permalink
     */

    function wc_get_permalink_shop() {
        $id = woocommerce_get_page_id( 'shop' );

        if ( empty( $id ) ) {
            return false;
        }

        return get_permalink_by_id( $id );
    }

    /**
     * WooCommerce : Return edit account page permalink
     */

    function wc_get_permalink_edit_account() {
        $page = get_permalink_account_root_page();

        $endpoint = get_option( 'woocommerce_myaccount_edit_account_endpoint', false );

        if( empty ( $page ) || empty ( $endpoint ) ) {
            return get_permalink_fallback();
        }

        return trailingslashit ( trailingslashit( $page ) . $endpoint );
    }

    /**
     * WooCommerce : Return edit address page permalink
     */

    function wc_get_permalink_edit_address() {
        $page = get_permalink_account_root_page();

        $endpoint = get_option( 'woocommerce_myaccount_edit_address_endpoint', false );

        if( empty ( $page ) || empty ( $endpoint ) ) {
            return get_permalink_fallback();
        }

        return trailingslashit ( trailingslashit( $page ) . $endpoint );
    }

    /**
     * WooCommerce : Return lost password page permalink
     */

    function wc_get_permalink_lost_password() {
        $page = get_permalink_account_root_page();

        $endpoint = get_option( 'woocommerce_myaccount_lost_password_endpoint', false );

        if( empty ( $page ) || empty ( $endpoint ) ) {
            return get_permalink_fallback();
        }

        return trailingslashit ( trailingslashit( $page ) . $endpoint );
    }

    /**
     * WooCommerce : Return logout permalink
     */

    function wc_get_permalink_logout() {
        $page = get_permalink_account_root_page();

        $endpoint = get_option( 'woocommerce_logout_endpoint', false );

        if( empty ( $page ) || empty ( $endpoint ) ) {
            return get_permalink_fallback();
        }

        return trailingslashit ( trailingslashit( $page ) . $endpoint );
    }
}
