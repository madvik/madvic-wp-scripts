<?php

/**
 * Head cleaning
 */
remove_action('wp_head', 'wp_generator');								// Masquer la version de WordPress
remove_action('wp_head', 'wlwmanifest_link');							// Masquer Manifest
remove_action('wp_head', 'rsd_link');									// Masquer RSD
add_filter( 'xmlrpc_enabled', '__return_false' );						// Masquer RPC


if(isset($DISALLOW_FILE_EDIT)){define('DISALLOW_FILE_EDIT', true);}		// Désactivier l'éditeur

/**
 * Hide connections errors
 */
add_filter('login_errors', create_function('$a', "return null;"));		// Masquer les erreurs de connexion

/**
 * Delete script version
 */
add_filter( 'script_loader_src', 'delete_script_version', 15, 1 );		// Masquer la version wp des scripts
add_filter( 'style_loader_src', 'delete_script_version', 15, 1 );		// Masquer la version wp des styles

/**
 * Masquer la vesion sur script et style
 */
//http://techtalk.virendrachandak.com/how-to-remove-wordpress-version-parameter-from-js-and-css-files/#axzz2nLwGBpkD
function delete_script_version( $src){
	//return preg_replace('/(ver=[^&]+)/', 'ver=' . md5( wp_salt( 'nonce' ) . '$1' ), $src);
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

/**
 * Source : https://wordpress.org/plugins/user-name-security/
 * Author : Daniel Roch
 * Filter body_class in order to hide User ID and User nicename
 * @param array $wp_classes holds every default classes for body_class function
 * @param array $extra_classes holds every extra classes for body_class function
 */
function seomix_sx_security_body_class( $wp_classes, $extra_classes )
{
	if ( is_author() ) {
		// Getting author Information
		$curauth = get_query_var( 'author_name' ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
		// Blacklist author-ID class
		$blacklist[] = 'author-'.$curauth->ID;
		// Blacklist author-nicename class
		$blacklist[] = 'author-'.$curauth->user_nicename;
		// Delete useless classes
		$wp_classes = array_diff( $wp_classes, $blacklist );
	}
	// Return all classes
	return array_merge( $wp_classes, (array)$extra_classes );
}
add_filter( 'body_class', 'seomix_sx_security_body_class', 10, 2 );


/**
 * Plugin Name:  No french punctuation and accents for filename
 * Description:  Remove all french punctuation and accents from the filename of upload for client limitation (Safari Mac/IOS)
 * Plugin URI:   https://gist.github.com/herewithme/7704370
 * Version:      1.0
 * Author:       BeAPI
 * Author URI:   http://www.beapi.fr
 */
add_filter( 'sanitize_file_name', 'remove_accents', 10, 1 );
add_filter( 'sanitize_file_name_chars', 'sanitize_file_name_chars', 10, 1 );
function sanitize_file_name_chars( $special_chars = array() ) {
	$special_chars = array_merge( array( '’', '‘', '“', '”', '«', '»', '‹', '›', '—', 'æ', 'œ', '€' ), $special_chars );
	return $special_chars;
}

?>