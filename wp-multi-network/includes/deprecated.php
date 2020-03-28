<?php
/**
 * Deprecated functions.
 *
 * @package WPMN
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_global_admin' ) ) :
	/**
	 * Checks whether the current user is a global administrator.
	 *
	 * @since 2.2.0
	 * @deprecated 2.3.0
	 *
	 * @return bool True if the user is a global administrator, false otherwise.
	 */
	function is_global_admin() {
		return (bool) apply_filters( 'is_global_admin', is_super_admin() );
	}
endif;

if ( ! function_exists( 'wpmn_fix_subsite_upload_path' ) ) {
	/**
	 * Keeps uploads for a newly-created subsite from being stored under the
	 * parent site when ms_files_rewriting is off.
	 *
	 * This is only needed for WP 3.5 - 3.6.1, so it can be removed once support
	 * for those versions is dropped.
	 *
	 * @since 1.4.0
	 * @deprecated
	 *
	 * @param string $value   Upload path option value.
	 * @param int    $blog_id Site ID.
	 */
	function wpmn_fix_subsite_upload_path( $value, $blog_id ) {
		global $current_site, $wp_version;

		if ( version_compare( $wp_version, '3.7', '<' ) ) {
			return $value;
		}

		if ( $blog_id === $current_site->blog_id ) {
			if ( ! get_option( 'WPLANG' ) ) {
				return '';
			}
		}

		return $value;
	}
	add_filter( 'blog_option_upload_path', 'wpmn_fix_subsite_upload_path', 10, 2 );
}

if ( ! function_exists( 'get_network_option' ) ) :
	/**
	 * Gets an option from a given network.
	 *
	 * Switches to the specified network internally to operate on it.
	 *
	 * @since 1.0.0
	 * @deprecated
	 *
	 * @param int    $network_id ID of network.
	 * @param string $key        Option key.
	 * @param mixed  $default    Default value if option doesn't exist.
	 * @return mixed Value set for the option if it exists, `$default` if it doesn't.
	 *               `WP_Error` instance if invalid network ID is passed.
	 */
	function get_network_option( $network_id, $key, $default = false ) {
		if ( ! switch_to_network( $network_id, true ) ) {
			return new WP_Error(
				'wpmn.network_missing',
				__( 'Network does not exist', 'wp-multi-network' ),
				array(
					'status' => 400,
				)
			);
		}

		$result = get_site_option( $key, $default );

		restore_current_network();

		return $result;
	}
endif;

if ( ! function_exists( 'add_network_option' ) ) :
	/**
	 * Adds an option from a given network.
	 *
	 * Switches to the specified network internally to operate on it.
	 *
	 * @since 1.0.0
	 * @deprecated
	 *
	 * @param int    $network_id ID of network.
	 * @param string $key        Option key.
	 * @param mixed  $value      Option value, can be anything.
	 * @return bool|WP_Error True if the option is added, false if not added.
	 *                       `WP_Error` instance if invalid network ID is passed.
	 */
	function add_network_option( $network_id, $key, $value ) {
		if ( ! switch_to_network( $network_id, true ) ) {
			return new WP_Error(
				'wpmn.network_missing',
				__( 'Network does not exist', 'wp-multi-network' ),
				array(
					'status' => 400,
				)
			);
		}

		$result = add_site_option( $key, $value );

		restore_current_network();

		return $result;
	}
endif;

if ( ! function_exists( 'update_network_option' ) ) :
	/**
	 * Updates an option from a given network.
	 *
	 * Switches to the specified network internally to operate on it.
	 *
	 * @since 1.0.0
	 * @deprecated
	 *
	 * @param int    $network_id ID of network.
	 * @param string $key        Option key.
	 * @param mixed  $value      Option value, can be anything.
	 * @return bool|WP_Error True if the option is updated, false if not updated.
	 *                       `WP_Error` instance if invalid network ID is passed.
	 */
	function update_network_option( $network_id, $key, $value ) {
		if ( ! switch_to_network( $network_id, true ) ) {
			return new WP_Error(
				'wpmn.network_missing',
				__( 'Network does not exist', 'wp-multi-network' ),
				array(
					'status' => 400,
				)
			);
		}

		$result = update_site_option( $key, $value );

		restore_current_network();

		return $result;
	}
endif;

if ( ! function_exists( 'delete_network_option' ) ) :
	/**
	 * Deletes an option from a given network.
	 *
	 * Switches to the specified network internally to operate on it.
	 *
	 * @since 1.0.0
	 * @deprecated
	 *
	 * @param int    $network_id ID of network.
	 * @param string $key        Option key.
	 * @return bool|WP_Error True if the option is deleted, false if not deleted.
	 *                       `WP_Error` instance if invalid network ID is passed.
	 */
	function delete_network_option( $network_id, $key ) {
		if ( ! switch_to_network( $network_id, true ) ) {
			return new WP_Error(
				'wpmn.network_missing',
				__( 'Network does not exist', 'wp-multi-network' ),
				array(
					'status' => 400,
				)
			);
		}

		$result = delete_site_option( $key );

		restore_current_network();

		return $result;
	}
endif;

if ( ! function_exists( 'get_networks' ) ) :
	/**
	 * Gets all networks.
	 *
	 * @since 1.0.0
	 * @deprecated
	 *
	 * @param array $args Optional. Network query arguments. Default empty array.
	 * @return array Networks available on the installation.
	 */
	function get_networks( $args = array() ) {

		// Support for WordPress 4.6.0, if you're doing something really weird.
		if ( class_exists( 'WP_Network_Query' ) ) {
			$query = new WP_Network_Query();

			return $query->query( $args );
		}

		// The original get_networks() function.
		return $GLOBALS['wpdb']->get_results( "SELECT * FROM {$GLOBALS['wpdb']->site}" );
	}
endif;
