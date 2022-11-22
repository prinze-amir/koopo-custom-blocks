<?php
/**
 * Plugin Name: Koopo Blocks
 * Plugin URI: http://www.docs.koopoonline.com/
 * Description: Custom blocks and shortcodes for advance features.
 * Version: 1.0
 * Author: Plu2oprinze
 * Author URI: http://www.koopoonline.com
 */

define( 'KOOPO_BLOCK_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', 'kb_load_textdomain' );

function kb_load_textdomain() {
	load_plugin_textdomain( 'koopo-blocks', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

/**
 * template that displays Audio Archive and Single.
 *
 * @param string $template_path path to our template file.
 */
function kb_include_audio_templates( $template_path ) {
	global $template_type;
	/*if ( 'dzsap_items' === get_post_type() && is_single() ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/audio-single.php';
		}
*/
		if ( 'koopo_music' === get_post_type() && is_single() ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/audio-single.php';
		}
		
		if ( 'artists' === get_post_type() && is_single() ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/artist-single.php';
		}
		
		if ( 'albums' === get_post_type() && is_single() ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/album.php';
        }
        
		/*if ( is_post_type_archive('dzsap_items') || is_tax('genre') || is_tax('music_tags') ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/audio-archive.php';
		}*/

		if ( is_post_type_archive('koopo_music') || is_tax('genre') || is_tax('music_tags') ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/audio-archive.php';
		}

		if ( is_post_type_archive('artists') || is_post_type_archive('albums') || is_tax('genre') || is_tax('music_tags') ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/audio-archive.php';
		}
	
	return $template_path;
}
add_filter( 'template_include', 'kb_include_audio_templates' );

/**
 * template that displays video Archive and Single.
 *
 * @param string $template_path path to our template file.
 */
function kb_include_video_templates( $template_path ) {
	global $template_type;
	if ( 'kvidz' === get_post_type() && is_single() ) {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/video-single.php';
        }
        
		if ( is_post_type_archive('kvidz') || is_tax('kvidz_categories') || is_tax('kmedia_tags')) {
		 
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/video-archive.php';
		}
	
	return $template_path;
}
add_filter( 'template_include', 'kb_include_video_templates' );
 
require_once( plugin_dir_path( __FILE__ ) . 'includes/elementor.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php' );
require_once( plugin_dir_path( __FILE__ ) . 'classes/koopo.php' );
require_once( plugin_dir_path( __FILE__ ) . 'classes/business.php' );

add_action('wp_insert_post', 'create_new_biz_activity_posts', 100, 2);

function create_new_biz_activity_posts($post_id, $post){
	
	$type = $post->post_type;
	$types = ['post', 'ajde_events', 'product', 'job_listing', 'attachment', 'wyz_business'];
	if (!in_array($type, $types) || $post->post_status != 'publish' ){
		return;
	}
	if ( !empty( get_post_meta($post_id, 'biz_post_update_once') ) ){
		return;
	}
	$author = $post->post_author;
	update_post_meta($post_id, 'biz_post_update_once', true);
	if ($type == 'ajde_events'){
		return KoopoBlocks\Classes\KoopoBusiness::add_new_event_activity($post_id, $author);
	}
		
	return	KoopoBlocks\Classes\KoopoBusiness::new_business_activity($post_id, $author);
}

add_action( 'wp_enqueue_scripts' , 'enqueu_koopo_styles');

function enqueu_koopo_styles(){

	wp_enqueue_style( 'koopo-frontend-snippet', '/wp-content/plugins/koopo-custom-blocks/assets/css/frontend-snippet.css' );
	wp_enqueue_style( 'koopo-loader', '/wp-content/plugins/koopo-custom-blocks/assets/css/koopo-loader.css' );
	wp_register_script( 'koopo-header-snippet', plugin_dir_url( __FILE__ ) . 'assets/js/sticky-header.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'koopo-header-snippet', '/wp-content/plugins/koopo-custom-blocks/assets/js/sticky-header.js', array( 'jquery' ), false, true );
//	wp_register_script( 'koopo-snippet', '/wp-content/plugins/koopo-custom-blocks/assets/js/snippets.js', array( 'jquery' ), false, true ); removed added to footer of templates

}

add_action( 'admin_enqueue_scripts', function(){

	wp_enqueue_style( 'koopo-extra',  '/wp-content/plugins/koopo-custom-blocks/assets/css/backend.css' );

});

add_action('wp_footer', function(){
	
	echo '<script> jQuery(window).on("load", function(){
		jQuery("#page-loader-init").fadeOut("fast");
	});</script>';

});
