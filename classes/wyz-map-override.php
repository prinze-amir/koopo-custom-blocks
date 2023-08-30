<?php
class WyzMapOverride
{
	/**    functions overrides here     **/

	//example:
	/*public static function wyz_create_post( $value, $is_current_user_author = false, $is_wall = false ) {
		whatever is written here will override the function displaying the business posts in the wall and business wall
	}*/
	public static function wyz_initialize_map_scripts( $map_type ) {

		$language = get_bloginfo( 'language' );
		/*wp_register_script( 'wyz_map_api', '//maps.googleapis.com/maps/api/js?libraries=places&language='.$language.'&key=' . get_option( 'wyz_map_api_key' ) . '&callback=wyz_init_load_map_callback#asyncload', array( 'jquery' ) );*/
		if (function_exists('dokan')) {
        dokan()->scripts->load_gmap_script();
		

		WyzMap::$map_js = array( 'wyz_marker_cluster', 'wyz_spiderfy', 'wyz_range_slider' );
		if ( 3 != $map_type )
		WyzMap::$map_js[] = 'google-maps';
		} else {

			WyzMap::$map_js = array( 'wyz_marker_cluster', 'wyz_spiderfy', 'wyz_range_slider' );
		if ( 3 != $map_type )
			WyzMap::$map_js[] = 'wyz_map_api';
		}
		/*if( 'on' == get_option( 'wyz_map_lockable', 'off' ) ) {
			wp_register_script( 'wyz_map_layer', plugin_dir_url( __FILE__ ) . 'js/map-layer.js', '', '', true );
			self::$map_js[] = 'wyz_map_layer';
		}*/
		if(!$map_type)return;
		if ( 2 == $map_type ) { // Single business map.
			wp_register_script( 'wyz_single_bus_map', plugins_url( 'wyz-toolkit/templates-and-shortcodes/' ) . 'js/single-business-map.js', WyzMap::$map_js, '', true );
		} elseif ( 1 == $map_type ) { // Global map.
			wp_register_script( 'wyz_map_cluster', plugins_url( 'wyz-toolkit/templates-and-shortcodes/' ) . 'js/map-cluster.js', WyzMap::$map_js, '', true );
		} else { // Contact map. 
			wp_register_script( 'wyz_contact_map', plugins_url( 'wyz-toolkit/templates-and-shortcodes/' ) . 'js/contact-map.js', WyzMap::$map_js, '', true ); 
		}
    }
    
    public static function wyz_get_business_header_image( $id ) {
		
		$img = get_post_meta( $id, 'wyz_business_header_image', true );
		$bg_color = get_post_meta( $id, 'wyz_business_logo_bg', true );
		echo '<div id="page-header-image" class="business-header-image' . ( '' == $img ? ' business-header-no-image' : '' ) . '" style="min-height:'.get_option( 'wyz_businesses_map_height' ). 'px;' . ( '' == $img ? 'background-color: #ccc;' : 'background-image: url(' . $img . ')').'">';
		if ( WyzHelpers:: wyz_is_current_user_author( $id ) )
			echo '<div class="container-2"><span class="icon"><i class="fa fa-photo"></i></span><button id="business-header-no-image-btn" class="business-set-header-image-btn" >' . esc_html__( 'Upload Cover Photo', 'koopo-online' ) . '</button></div>';
		if ( 'off' == get_option( 'wyz_hide_header_busienss_logo_case_of_image_header', 'off' ) && WyzHelpers::wyz_sub_can_bus_owner_do(WyzHelpers::wyz_the_business_author_id(),'wyzi_sub_show_business_logo') ) {
			echo '<div class="header-image-logo col-lg-3 col-md-4 col-sm-4 col-xs-6" style="background-color: ' . $bg_color . ';">';
			if ( is_singular( 'wyz_offers' ) ) {
				echo WyzHelpers::get_post_thumbnail( $id, 'business', 'medium', array( 'class' => 'logo' ) );
			} else {
				echo WyzHelpers::get_post_thumbnail( get_the_ID(), 'business', 'medium', array( 'class' => 'logo' ) );
			}

			echo '</div>';
		}			echo do_shortcode('[TheChamp-Sharing]'); 
 
		echo '</div>';
	}
}
