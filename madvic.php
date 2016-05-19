<?php
 /*
 * Plugin Name:		madvic-wp-script
 * Plugin URI:		http://madvic.net/
 * Description:		Script to optimize WordPress installation
 * Version:			1.0
 * Author:			Madvic
 * Author URI:		http://madvic.net/
 * License:			GPL2
 * License URI:		https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

define( 'MADVICWPSCRIPTS_FILE'	, __FILE__ );
define( 'MADVICWPSCRIPTS_PATH'	, realpath( plugin_dir_path( MADVICWPSCRIPTS_FILE ) ) . '/madvic-wp-scripts/' );


/*
 * Tell WP what to do when plugin is loaded
 *
 * @since 1.0
 */
add_action( 'plugins_loaded', '_madvic_wp_scripts_init' );

function _madvic_wp_scripts_init() {

	// Nothing to do if autosave
	if ( defined( 'DOING_AUTOSAVE' ) ) {
		return;
	}

	require( MADVICWPSCRIPTS_PATH . 'madvic_wp_scripts_security.php' );
	require( MADVICWPSCRIPTS_PATH . 'madvic_wp_scripts_tunning.php' );

	/**
	 * Fires when madvic is correctly loaded
	 *
	 * @since 1.0
	*/
	do_action( 'madvic_wp_scripts_loaded' );
	
}

?>