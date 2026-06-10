<?php
/**
 * Plugin Name: Local Dev URLs
 * Description: Forces http (not https) for all WordPress-generated URLs when the
 *              site is accessed on localhost. Prevents ERR_CERT_AUTHORITY_INVALID
 *              on admin-ajax.php and other AJAX/asset requests in local XAMPP dev.
 *              Production-safe: only active when the host is localhost.
 */

if ( ! empty( $_SERVER['HTTP_HOST'] ) && strpos( $_SERVER['HTTP_HOST'], 'localhost' ) !== false ) {

	if ( ! function_exists( 'sipn_localdev_to_http' ) ) {
		function sipn_localdev_to_http( $url ) {
			if ( is_string( $url ) ) {
				return preg_replace( '#^https://#i', 'http://', $url );
			}
			return $url;
		}
	}

	foreach ( array( 'admin_url', 'home_url', 'site_url', 'includes_url', 'content_url', 'plugins_url', 'rest_url', 'network_admin_url', 'network_site_url', 'network_home_url' ) as $sipn_url_filter ) {
		add_filter( $sipn_url_filter, 'sipn_localdev_to_http', 999 );
	}
}
