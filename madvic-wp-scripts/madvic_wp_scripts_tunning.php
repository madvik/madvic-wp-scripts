<?php

/**
* Revision setting
* @source https://codex.wordpress.org/Revisions
*/ 
if(!defined('WP_POST_REVISIONS')){
	define('WP_POST_REVISIONS', 5);
}

/**
* Hide update notifications
*/
function hide_wp_update_nag() {
	remove_action( 'admin_notices', 'update_nag', 3 );
}
//add_action('admin_menu','hide_wp_update_nag');

/**
* Does not display the previous and next link
* Economise 3 queries
* <link rel='prev' ...
*/
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); 

/**
* Nettoyage de l'entête <head>
*/
remove_action('wp_head', 'start_post_rel_link');			// Supprime le lien vers le premier post
// remove_action('wp_head', 'feed_links', 2 );				// Supprime le flux RSS général
remove_action('wp_head', 'feed_links_extra', 3 );			// Supprime le flux RSS des catégories
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 ); 	// Supprime la balise lien court <link rel=shortlink
remove_action('wp_head', 'index_rel_link' );  				// Supprime la balise <link rel=index
remove_action('wp_head', 'parent_post_rel_link', 10, 0);  	// Supprime le lien vers la catégorie parente


/**
 * Deactive admin bar
 */
//add_filter('show_admin_bar', '__return_false');	

/**
* Masquer la ligne existant depuis la 2.8 :
* <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style> ajouté en dur après les entetes
*/
function theme_remove_recent_comments_style() {
	global $wp_widget_factory;
	
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
/* désactivé car je désactive le widget */
//add_action( 'widgets_init', 'theme_remove_recent_comments_style' );

/**
* Permet de désactiver l'appel à des widget -> eco SQL
* A paramétrer sur chaque projet
* http://www.cssreflex.com/snippets/remove-default-widgets-in-wordpress/
*/
function unregister_default_wp_widgets() {
		//unregister_widget('WP_Widget_Pages'); // consomme 1 requete
		//unregister_widget('WP_Widget_Calendar'); // consomme 1 requete
		//unregister_widget('WP_Widget_Archives');
		//unregister_widget('WP_Widget_Links');
		//unregister_widget('WP_Widget_Meta');
		//unregister_widget('WP_Widget_Search');
		//unregister_widget('WP_Widget_Text');
		//unregister_widget('WP_Widget_Categories');
		//unregister_widget('WP_Widget_Recent_Posts');
		//unregister_widget('WP_Widget_Recent_Comments'); // consomme 1 requete
		//unregister_widget('WP_Widget_RSS');
		//unregister_widget('WP_Widget_Tag_Cloud'); // consomme 1 requete
		//unregister_widget('WP_Nav_Menu_Widget'); // consomme 1 requete

		unregister_widget('WP_Widget_Pages');
		unregister_widget('WP_Widget_Calendar');
		unregister_widget('WP_Widget_Tag_Cloud');
		unregister_widget('WP_Nav_Menu_Widget');
		unregister_widget('WP_Widget_Recent_Comments');

}
if(!is_admin()){
	add_action('widgets_init', 'unregister_default_wp_widgets', 1);
}



/***
* Optimisation / minification du code HTML
* @source http://www.geekpress.fr/minifier-html-sans-plugin/
*/
function madvic_html_minify_start()  {
	ob_start( 'madvic_html_minyfy_finish' );
}

function madvic_html_minyfy_finish( $html )  {
	// Suppression des commentaires HTML,
	// sauf les commentaires conditionnels pour IE (retire les condition au lieu de les laisser)
	//$html = preg_replace('/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html);

	// Suppression des espaces vides
	$html = str_replace(array("\r\n", "\r", "\n", "\t"), '', $html);
	while ( stristr($html, '  '))
		$html = str_replace('  ', ' ', $html);
	return $html;
} 
if(!WP_DEBUG) {
	add_action('get_header', 'madvic_html_minify_start');
}
/* Fin minification du code HTML */

/**
 *  Remove h1 from the WordPress editor.
 *
 *  @param   array  $init  The array of editor settings
 *  @return  array		 The modified edit settings
 */
function modify_editor_buttons( $init ) {
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;';
	return $init;
}
add_filter( 'tiny_mce_before_init', 'modify_editor_buttons' );


/**
* Add medium format `medium_large` to media in admin
* 
* @param array $format Format list
* @return array $format
*/
function add_medium_large( $format ){
	$format['medium_large'] = __('Medium Large'); 
	return $format;
}
add_filter( 'image_size_names_choose', 'add_medium_large');

/*========================= emoji ============================= */
/**
 * Disable the emoji's
 * @source https://www.keycdn.com/blog/website-performance-optimization/#http
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array  $plugins
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
// Les boutons social sharing ne s'affichent plus comme voulu
/*========================= end emoji ============================= */

/**
* Deactivate API
*/
// Filters for WP-API version 1.x
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');

// Filters for WP-API version 2.x
add_filter('rest_enabled', '__return_false');
add_filter('rest_jsonp_enabled', '__return_false');

// Remove REST API info from head and headers
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

/**
* Dashboard cleaning
* Désactive les meta_boxes inutile au dashboard
*/
function remove_dashboard_widgets() {
	remove_action('welcome_panel', 'wp_welcome_panel',99);

	global $wp_meta_boxes;
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); 
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

/*========================= RSS ============================= */
/**
 * FLUX RSS
 * @source  *  @source https://fr.semrush.com/blog/boostez-votre-referencement-naturel-sur-wordpress/
 */

/**
 *  Plus aucun autre flux RSS sauf celui de l'accueil
 */
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

/**
 *  Plus d'emoji dans les flux RSS
 */
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
/**
 *  plus de flux RSS de commentaire
 */
add_filter( 'feed_links_show_comments_feed', 'seomix_rss_remove_comments' );
function seomix_rss_remove_comments() {
	return false;
}

/**
 *  Redirection 301 de tous les flux RSS d’un WordPress, sauf le flux principal.
 */
add_action( 'template_redirect', 'seomix_template_redirect_feed' );
function seomix_template_redirect_feed() {
	if( is_feed() ) {
		global $wp_query;

		$home = home_url();
		$mainfeedurl = user_trailingslashit ( $home.'/feed' );
		$currenturl = isset($_SERVER['HTTPS']) ? "https" : "http" . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		if ( $currenturl != $mainfeedurl ) {
			wp_redirect( $mainfeedurl );
			die;
		} elseif( $wp_query->post_count === 0 ) { // Si aucun article n'existe, WordPress génère une 404 pour le flux. Dans ce cas, on redirige vers l'accueil.
			wp_redirect( $home );
			die;
		}
	}
}
/*========================= end RSS ============================= */
?>
