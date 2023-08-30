<?php 
//fix color picker error in admin area
if( is_admin() ){
	add_action( 'wp_default_scripts', 'wp_default_custom_scripts' );
	function wp_default_custom_scripts( $scripts ){
		$scripts->add( 'wp-color-picker', "/wp-admin/js/color-picker.js", array( 'iris' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'wp-color-picker',
			'wpColorPickerL10n',
			array(
				'clear'            => __( 'Clear' ),
				'clearAriaLabel'   => __( 'Clear color' ),
				'defaultString'    => __( 'Default' ),
				'defaultAriaLabel' => __( 'Select default color' ),
				'pick'             => __( 'Select Color' ),
				'defaultLabel'     => __( 'Color value' ),
			)
		);
	}
}

add_filter( 'is_protected_meta', '__return_false' ); 
//add_filter('postmeta_form_limit', 'customfield_limit_increase');
function customfield_limit_increase($limit) {
  $limit = '100';
  return $limit;
}

//custome function to include all users in author meta box
add_filter('wp_dropdown_users_args', 'assign_any_users_author_box_func', 10, 2);
/*add_action('admin_bar_menu', 'removes_from_admin_bar', 100);

function removes_from_admin_bar($wp_adminbar) {
    if ( ! is_admin() ) {

   // $wp_admin_bar->remove_node('elementor_inspector');
    $wp_adminbar->remove_node('aioseo-main');
    }
  }*/

function assign_any_users_author_box_func($query_args, $r){
    $query_arg['who'] = 'any';
    $query_arg['order'] = 'ASC';
    $query_arg['show'] = 'email';
	$query_arg['orderby'] = 'display_name';
    return $query_arg;
}

add_filter( 'users_list_table_query_args', 'change_default_user_sort' );

function change_default_user_sort( $args ) {
    if ( empty( $args['orderby'] ) )
        $args['orderby'] = 'ID';
    if ( empty( $args['order'] ) )
        $args['order'] = 'DESC';
    return $args;
}

function wyz_pagination( $return = false ) {
    if ( $return )ob_start();
  if ( is_singular() ) {
      echo '<div class="blog-pagination fix"><ul>';
      
      the_post_navigation( array(
          'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next post:', 'koopo-online' ) . '</span> ' .
                          '<li class="next-page float-right">%title</li><i class="fa fa-arrow-right"></i>',
          'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'koopo-online' ) . '</span> ' .
                          '<li class="prev-page float-left">%title</li><i class="fa fa-arrow-left"></i>'
          ) );
      echo '</ul></div>';
      return $return ? ob_get_clean() : '';
  }

  global $wp_query;

  // Stop execution if there's only 1 page.
  if ( $wp_query->max_num_pages <= 1 ) {
      if ( $return )
          ob_get_clean();
      return;
  }

  $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
  $max   = intval( $wp_query->max_num_pages );

  // Add current page to the array.
  if ( $paged >= 1 ) {
      $links[] = $paged;
  }

  // Add the pages around the current page to the array.
  if ( $paged >= 3 ) {
      $links[] = $paged - 1;
      $links[] = $paged - 2;
  }

  if ( ( $paged + 2 ) <= $max ) {
      $links[] = $paged + 2;
      $links[] = $paged + 1;
  }

  echo '<div class="blog-pagination fix"><ul>' . "\n";

  // Previous Post Link.
  if ( get_previous_posts_link() ) {
      printf( '<li class="prev-page float-left">%s</li>' . "\n", get_previous_posts_link( '<i class="fa fa-angle-left"> </i>' . esc_html__( 'Previous Page', 'koopo-online' ) ) );
  }

  // Link to first page, plus ellipses if necessary.
  if ( ! in_array( 1, $links, true ) ) {
      $class = 1 === $paged ? ' class="active"' : '';

      printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

      if ( ! in_array( 2, $links, true ) ) {
          echo '<li>...</li>';
      }
  }

  //	Link to current page, plus 2 pages in either direction if necessary.
  sort( $links );
  foreach ( (array) $links as $link ) {
      $class = $paged === $link ? ' class="active"' : '';
      printf( '<li><a %s href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), esc_html( $link ) );
  }

  //	Link to last page, plus ellipses if necessary.
  if ( ! in_array( $max, $links, true ) ) {
      if ( ! in_array( $max - 1, $links, true ) ) {
          echo '<li>' . apply_filters( 'wyz_pagination_separator','...') .'</li>' . "\n";
      }

      $class = $paged === $max ? ' class="active"' : '';
      printf( '<li><a %s href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), esc_html( $max ) );
  }

  //	Next Post Link.
  if ( get_next_posts_link() ) {
      printf( '<li class="next-page float-right">%s</li>' . "\n", get_next_posts_link( esc_html__( 'Next Page', 'koopo-online' ) . '<i class="fa fa-angle-right"></i>' ) );
  }

  echo '</ul></div>' . "\n";
  if ( $return ) return ob_get_clean();
}



/**
* Return the result of wyz_pagination
*
* @return string paginated links
*/
function wyz_get_pagination() {
  $result = '';
  if ( is_singular( 'post' ) ) {
      $result .= '<div class="blog-pagination fix"><ul>';
      
      $result .= get_the_post_navigation( array(
          'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next post:', 'koopo-online' ) . '</span> ' .
                          '<li class="next-page float-right">%title</li>',
          'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'koopo-online' ) . '</span> ' .
                          '<li class="prev-page float-left">%title</li>'
          ) );
      $result .= '</ul></div>';
      return $result;
  }

  global $wp_query;

  // Stop execution if there's only 1 page.
  if ( $wp_query->max_num_pages <= 1 ) {
      return '';
  }

  $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
  $max   = intval( $wp_query->max_num_pages );

  // Add current page to the array.
  if ( $paged >= 1 ) {
      $links[] = $paged;
  }

  // Add the pages around the current page to the array.
  if ( $paged >= 3 ) {
      $links[] = $paged - 1;
      $links[] = $paged - 2;
  }

  if ( ( $paged + 2 ) <= $max ) {
      $links[] = $paged + 2;
      $links[] = $paged + 1;
  }

  $result .= '<div class="blog-pagination fix"><ul>' . "\n";

  // Previous Post Link.
  if ( get_previous_posts_link() ) {
      $result .= '<li class="prev-page float-left">' . get_previous_posts_link( '<i class="fa fa-angle-left"> </i>' . esc_html__( 'Previous Page', 'koopo-online' ) ) . '</li>' . "\n";
  }

  // Link to first page, plus ellipses if necessary.
  if ( ! in_array( 1, $links, true ) ) {
      $class = 1 === $paged ? ' class="active"' : '';

      $result .= '<li' . $class . '><a href="' . esc_url( get_pagenum_link( 1 ) ) . '">1</a></li>' . "\n";

      if ( ! in_array( 2, $links, true ) ) {
          $result .= '<li>...</li>';
      }
  }

  //	Link to current page, plus 2 pages in either direction if necessary.
  sort( $links );
  foreach ( (array) $links as $link ) {
      $class = $paged === $link ? ' class="active"' : '';
      $result .= '<li><a' . $class . ' href="' . esc_url( get_pagenum_link( $link ) ) . '">' . esc_html( $link ) . '</a></li>' . "\n";
  }

  //	Link to last page, plus ellipses if necessary.
  if ( ! in_array( $max, $links, true ) ) {
      if ( ! in_array( $max - 1, $links, true ) ) {
          $result .= '<li>...</li>' . "\n";
      }

      $class = $paged === $max ? ' class="active"' : '';
      $result .= '<li><a' . $class . ' href="' . esc_url( get_pagenum_link( $max ) ) . '">'. esc_html( $max ) . '</a></li>' . "\n";
  }

  //	Next Post Link.
  if ( get_next_posts_link() ) {
      $result .= '<li class="next-page float-right">' . get_next_posts_link( esc_html__( 'Next Page', 'koopo-online' ) . '<i class="fa fa-angle-right"></i>' ) . '</li>' . "\n";
  }

  $result .= '</ul></div>' . "\n";
  return $result;
}


//add_action('init', 'koopo_author_base');//custom author base
add_filter('frontend_load_stockpack', 'stockpack_on_front');
//add_filter('register_post_type_args','modify_job_post_type',10,2);
add_action('frm_after_create_entry', 'autologin_quick_registration',30,2);
//change permalink structure for posts
add_filter( 'dfi_thumbnail_id', 'dfi_skip_page', 10 , 2 );//default image filter post types 

add_filter( 'register_post_type_args', function ($args, $post_type) {
        if ($post_type !== 'post') {
            return $args;
        }

        $args['rewrite'] = [
            'slug' => 'kpost',
            'with_front' => true,
        ];

        return $args;
    },10,2 );

    add_filter( 'pre_post_link', function ($permalink, $post) {
            if ($post->post_type !== 'post') {
                return $permalink;
            }
    
            return '/kpost/%postname%/';
        },10, 2 );

function koopo_author_base() {
    global $wp_rewrite;
    $author_slug = 'we'; // change slug name
    $wp_rewrite->author_base = $author_slug;
}
function modify_job_post_type($args, $name){

    if('job_listing' == $name){

        $args['show_in_nav_menus'] = true;
        $args['query_var'] = true;
        $args['taxonomies'] = ['job_listing_category','job_listing_type'];

    }

    return $args;

}

function stockpack_on_front(){
       $load = false;
   if( current_user_can('influencer') && koopo_level(1) || current_user_can('administrator') ){ 
        $load = true;
   }
   return $load;
}

function dfi_skip_page ( $dfi_id, $post_id ) {
    $type = get_post_type($post_id);

    if ( !in_array($type, array('post','kvidz') ) ) {
      return 0; // invalid id
    }
    return $dfi_id; // the original featured image id
  }
  add_filter( 'dfi_thumbnail_id', 'dfi_skip_page', 10 , 2 );//default image filter post types 
  

add_filter('bnfw_notification_disabled', function( $id, $setting ) {
    if($id == 209877)
    return true;
},10,2);

function autologin_quick_registration($entry_id,$form_id){

    if($form_id == 8){ //quick registration
        if(isset($_POST['item_meta'][82])){ 
            $email = $_POST['item_meta'][82];
        }

      }

      if ($form_id == 19){
          if (isset($_POST['item_meta'][212])){
            $email = $_POST['item_meta'][212];
          }
      }

      if(empty($email)){
          return;
      }

      $user = get_user_by( 'email', $email);
        //   wp_redirect( home_url( '/seller-access' ) );
        //  $password = wp_generate_password( 10, false ); //Get tne value of the input with the label "Password"
        add_filter('bnfw_notification_disabled', '__return_false');
          wp_new_user_notification($user->ID, $deprecated = null, $notify = 'both');
        
    /*if ( ! is_user_logged_in() ) {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        exit; 
    }*/
}

function _new_user_auto_log_in($user_id){
    if(!is_user_logged_in()){
      wp_set_current_user( $user_id );
      wp_set_auth_cookie( $user_id );
      wp_redirect( home_url( '/user-account/?udpage=subscription' ) );
     // exit(); 
    }
}

function is_mobile_screen(){
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    $is_mobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent);

    $is_mobile_new = preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
    if ($is_mobile || $is_mobile_new){
    return true;
    }
}

/*
===========================================================================
 * Wyz Specific Module
=========================================================================
*/
add_action( 'widgets_init', 'add_wyz_sidebars' );
add_action( 'wp_ajax_biz_url',  'biz_url_check' );
add_action( 'wp_ajax_nopriv_biz_url', 'biz_url_check' );
add_action('wp_insert_post','add_to_bizwall', 10, 3);

add_action('wyz_before_user_profile', 'show_roles');

//theme options
function wyz_get_option( $option ) {
	if ( function_exists( 'ot_get_option' ) ) {
		return ot_get_option( $option );
	}
	return '';
}

function wyz_get_theme_template(){
    //return template 1 only
    return 1;
}

function show_roles ($user_id){
    global $wp_roles;
    $user_meta=get_userdata($user_id);
    $user_roles=$user_meta->roles;
    echo '<div id="user-type" style="display:inline-block">';
    echo '<h3 class="title"><i class="fa fa-users"></i> User Type</h3>';
    foreach ( $user_roles as $role ) {
        
        $role_icon = '';
        $role_help = '';
        if ($role == 'administrator') {
            $role_icon = 'fa fa-cogs';
            $role_help = 'You have the power, use it wisely';

        }
        if ($role == 'seller') {
            $role_icon = 'fas fa-shopping-bag';
            $role_help = 'Sellers can setup stores and create products/services to sell';

        }
        if ($role == 'business_owner') {
            $role_icon = 'fas fa-briefcase';
            $role_help = 'List your business, market services and deals post jobs';

        }
        if ($role == 'customer') {
            $role_icon = 'fa fa-shopping-cart';
            $role_help = 'Customers shop the latest deals and support local retail';

        }
        if ($role == 'affiliate') {
            $role_icon = 'fas fa-comment';
            $role_help = 'Affiliates earn money from referral program';

        }
        if ($role == 'influencer') {
            $role_icon = 'fas fa-share-alt';
            $role_help = 'Influence and inspire at influencers square';
        }
        ?>
        <ul style="list-style:none">
            <li title="<?php echo $role_help ?>" class="title" style="text-transform:capitalize;cursor:pointer;font-size:18px;color:#222"><i class="<?php echo $role_icon ?>"></i> <?php echo $wp_roles->roles[ $role ]['name']; ?></li>
        </ul>
       
  <?php } 

    echo '</div>';
}

function add_to_bizwall($post_id, $post, $update){

    if ($post->post_type != 'wyz_business_post') {
        return;
    }
    $business_id = get_post_meta($post_id, 'business_id', true);
        if (!empty($business_id)){
        $business_posts = get_post_meta( $business_id, 'wyz_business_posts', true );
        if ( '' === $business_posts || ! $business_posts ) {
            $business_posts = [];
        }
        if (!in_array($post_id, $business_posts) ) {
            array_push( $business_posts, $post_id );
            update_post_meta( $business_id, 'wyz_business_posts', $business_posts );
        }
    }
}

//add the main businesses sidebar for business listings
function add_wyz_sidebars() {
	
	register_sidebar( array(
		'name' => esc_html__( 'Business Listing Sidebar', 'koopo-online' ),
		'id' => 'wyz-business-listing-sb',
		'description' => esc_html__( 'Shown on Business Listing template', 'koopo-online' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Single Business', 'koopo-online' ),
		'id' => 'wyz-single-business-sb',
		'description' => esc_html__( 'Shown on the single business page', 'koopo-online' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

function biz_url_check() {

    $nonce = isset( $_POST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'biz_reviews' ) ) {
        wp_send_json_error( [
            'type'    => 'nonce',
            'message' => __( 'Are you cheating?', 'dokan-lite' )
        ] );
    }

    $url_slug = isset( $_POST['url_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['url_slug'] ) ) : '';
    $check    = true;
    $business = get_page_by_title( $url_slug, OBJECT, 'wyz_business' );
    $id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
    if ( $id == $business->ID) {
         $check = true;
     }

    if ( '' != $business && $business->ID != $id ) {
        $check = false;
    }

    if ( $check ) {
        wp_send_json_success( [
            'message' => __( 'Available', 'dokan-lite' ),
            'url'     => sanitize_user( $url_slug )
        ] );
    }
}



/*
===========================================================================
 * Events Module
=========================================================================
*/
add_filter('evoau_form_fields', 'koopo_fields_to_form', 10, 1);// only for frontend
add_action('evoau_frontform_evobus',  'evoaubus_fields', 10, 6);// business intergration
add_action('evoau_frontform_evoloc',  'klocation_fields', 10, 6);// location intergration
add_action('evoau_frontform_evo_photos',  'evoa_photos_fields', 10, 6);//add photos
add_action('evoau_save_formfields',  'evoaubiz_save_values', 10, 3);
add_filter( 'eventon_eventcard_boxes','addfield_001' , 10, 1);
add_filter('eventon_eventcard_array', 'addfield_002', 10, 4);
add_filter('evo_eventcard_adds', 'addfield_003', 10, 1);
add_filter('eventon_eventCard_extra1', 'addfield_004', 10, 2);
add_action('save_post', 'update_eventloc', 10,3);

function koopo_fields_to_form($array){
    $array['evobus']=['Select Business', 'evobus', 'evobus','custom',''];
    $array['evoloc']=['Select Region/City', 'evoloc', 'evoloc','custom',''];
    $array['evo_photos']=['Event Photos', 'evo_photos', 'evo_photos', 'custom',''];
	return $array;
}

// Frontend showing fields and saving values  
function klocation_fields($field, $event_id, $default_val, $EPMV, $opt2, $lang){
    echo "<div class='row evoloc'><p>";
    $_koopo_location = [];
    $_koopo_location[''] = sprintf( esc_html__( 'No %s selected', 'koopo-online' ), 'Region' );
    echo '<p class="label"><label for="evoloc">Select Region/City</label></p><select  id="_koopo_location" name="_koopo_location">';
       
    $places = WyzHelpers::get_business_locations_dropdown_format(true);


        foreach ( $places as $place ) {
            $select = get_post_meta($event_id, '_koopo_location', true );
            $selected = ($select == $place) ? 'selected' : '';
            $value = !empty($place)?$place:'';
            echo '<option value="'. $place .'" '. $selected .'>'. get_the_title($value) .'</option>';
        }
    echo "</select></p></div>";
  
}

// Frontend showing fields and saving values  
function evoaubus_fields($field, $event_id, $default_val, $EPMV, $opt2, $lang){
        echo "<div class='row evobus'><p>";
        $business_id = [];
        $business_id[''] = sprintf( esc_html__( 'Not related to a %s Listing', 'koopo-online' ), WYZ_BUSINESS_CPT );
        echo '<p class="label"><label for="evobus">Connect Business</label></p><select  id="business_id" name="business_id">';

        if (current_user_can ('administrator')){
            $bargs = [
                'post_type' => 'wyz_business',
                'post_status' => 'publish',
                'posts_per_page' => - 1,
            ];

            $allbiz = get_posts($bargs);
            foreach ($allbiz as $id) {
                $business_id[$id->ID] =  $id->ID;
            }


            foreach ( $business_id as $biz ) {
                $select = get_post_meta($event_id, 'business_id', true );
                $selected = ($select == $biz) ? 'selected' : '';
                $value = !empty( get_the_title($biz) )?get_the_title($biz):'Not related to a business';
                echo '<option value="'. $biz .'" '. $selected .'>'. $value .'</option>';
            }

        } else {

            $businesses = WyzHelpers::get_user_businesses( get_current_user_id() );

            foreach ($businesses['published'] as $id) {
                $business_id[ $id ] =  $id;
            }


            foreach ( $business_id as $biz ) {
                $select = get_post_meta($event_id, 'business_id', true );
                $selected = ($select == $biz) ? 'selected' : '';
                $value = !empty( get_the_title($biz) )?get_the_title($biz):'Not related to a business';
                echo '<option value="'. $biz .'" '. $selected .'>'. $value .'</option>';
            }
    }

    echo "</select></p></div>";

}
function evoa_photos_fields($field, $event_id, $default_val, $EPMV, $opt2, $lang){
    global $post, $eventon_ep, $eventon;
        wp_nonce_field( plugin_basename( __FILE__ ), 'evoep_nonce' );
    
     $saved = get_post_meta( $event_id, '_evo_images', true);

        $wp_date_format = get_option('date_format');

        echo "<div class='row'><div class='evo_event_images'>
        <input type='hidden' name='_evo_images' value='". $saved."'/>
        <div class='evo_event_image_holder'>";

            $imgs = explode(',', $saved);
            $imgs = array_filter($imgs);
            foreach($imgs as $img){
                $caption = get_post_field('post_excerpt',$img);
                $url = wp_get_attachment_thumb_url($img);
                
                echo "<span data-imgid='{$img}'><b class='remove_event_add_img'>X</b><img title='{$caption}' data-imgid='{$img}' src='{$url}'></span>";
            }

        echo "</div>
    </div>";

    do_action('evo_more_images_before_btn', $post);
    echo "<span class='evo_btn formBtnS evo_add_more_images'>".__('Add Additional Images','eventon') ."</span></div>";

    echo "<span class='evo_event_images_notice'></span>";

    do_action('evo_more_images_end', $post);

    ?>
    
    <script>
        jQuery(document).ready(function(){
            
            /* Event Images */
		var file_frame, 
			BOX;
	  
	    jQuery('body').on('click','.evo_add_more_images',function(event){

	    	var obj = jQuery(this);
	    	BOX = obj.siblings('.evo_event_images');

	    	event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {	file_frame.open();		return;	}
			
			// Create the media frame.
			file_frame = wp.media.frames.downloadable_file = wp.media({
				title: 'Choose an Image',
				button: {text: 'Use Image',},
				multiple: true
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {

				var selection = file_frame.state().get('selection');
		        selection.map( function( attachment ) {
		            attachment = attachment.toJSON();
		            loadselectimage(attachment, BOX);
		           
	            });

                //attachment = file_frame.state().get('selection').first().toJSON();
                //loadselectimage(attachment, BOX);
			});

			// Finally, open the modal.
			file_frame.open();
	    }); 

		function loadselectimage(attachment, BOX){
			imgURL = (attachment.sizes.thumbnail && attachment.sizes.thumbnail.url !== undefined)? attachment.sizes.thumbnail.url: attachment.url;

			caption = (attachment.caption!== undefined)? attachment.caption: 'na';

			imgEL = "<span data-imgid='"+attachment.id+"'><b class='remove_event_add_img'>X</b><img title='"+caption+"' data-imgid='"+attachment.id+"' src='"+imgURL+"'></span>";

						
			BOX.find('.evo_event_image_holder').append(imgEL);
			update_image_ids(BOX);

			jQuery('body').trigger('evo_event_images_notice',[ 'Image Added!', 'good', BOX]);
				
		}

	    // remove image from gallery
		    jQuery('body').on('click', '.remove_event_add_img', function(){
		    	BOX = jQuery(this).closest('.evo_event_images');
		    	jQuery(this).parent().remove();
		    	update_image_ids(BOX);
		    });

		// drggable and sorting image order
			jQuery('.evo_event_image_holder').sortable({
				update: function(e, ul){
					BOX = jQuery(this).closest('.evo_event_images');
					update_image_ids(BOX);
				}
			});

		// update the image ids 
		    function update_image_ids(obj){
		    	var imgIDs='',
		    		INPUT = obj.find('input');

		    	C= 1;
		    	obj.find('img').each(function(index){
		    		imgid = jQuery(this).attr('data-imgid');
		    		if(imgid){
		    			imgIDs = (imgIDs? imgIDs:'') + imgid+',';
		    			C++;
		    		}
		    	});
		    	INPUT.val(imgIDs);
	    	
		    }

            jQuery('body').on('evo_event_images_notice', function(event, MSG, TYPE, BOX){
                if( TYPE == 'bad') BOX.siblings('.evo_event_images_notice').addClass('bad');
                BOX.siblings('.evo_event_images_notice').html( MSG ).addClass('show').delay(2000)
                    .queue(function(next){
                        jQuery(this).removeClass('show');
                        if( TYPE == 'bad') jQuery(this).removeClass('bad');
                        next();
                    });
            });
        });
    </script>
        <?php
}
function evoaubiz_save_values($field, $fn, $created_event_id){
	if( $field =='evobus'){
		
		// for each above fields
        foreach([ 'business_id', ] as $field){
			if(!empty($_POST[$field]))
                update_post_meta($created_event_id, $field, $_POST[$field]);
                $bloc = get_post_meta( $_POST[$field], 'wyz_biz_location', true );
                update_post_meta( $created_event_id, 'evo_bloc', $bloc );       
        }
    }
    if( $field =='evo_photos'){
        
        foreach([ '_evo_images', ] as $field){
			if(!empty($_POST[$field]))
                update_post_meta($created_event_id, $field, $_POST[$field]);
        }   
    }

    if( $field =='evoloc'){

        foreach([ '_koopo_location', ] as $field){
			if(!empty($_POST[$field]))
                update_post_meta($created_event_id, $field, $_POST[$field]);
        }   
    }
}

function addfield_001($array){
	$array['extra1']= ['evoextra1',__('Business Box','eventon')];
	return $array;
}

function addfield_002($array, $pmv, $eventid, $__repeatInterval){
	$array['extra1']= [
		'event_id' => $eventid,
		'pmv'=>$pmv,
		'__repeatInterval'=>(!empty($__repeatInterval)? $__repeatInterval:0)
	];
	return $array;
}

function addfield_003($array){
	$array[] = 'extra1';
	return $array;
}

function addfield_004($object, $helpers){
    $event_post_meta_values = $object->pmv;
    $busid = !empty($event_post_meta_values['business_id'])?$event_post_meta_values['business_id'][0]:'';
    $link = get_the_permalink($busid);
    $title = '';
    if (!empty($busid)){
        $title = get_the_title($busid);
    }
    $image = class_exists('WyzHelpers')?WyzHelpers::get_post_thumbnail( $busid, 'business', 'medium' ):'';
    if(!empty($title)) {
    ob_start();

	echo  "<div class='evorow evcal_evdata_row bordb evcal_evrow_sm".$helpers['end_row_class']."' data-event_id='".$object->event_id."'>
			<span class='evcal_evdata_icons'><i class='fa fa-briefcase'></i></span>
            <div class='evcal_evdata_cell'>";
		echo "<h3 class='evo_h3'>".__('Business Profile','eventon')."</h3>";
        echo '<a href="' . $link . '#events" target="_blank" class="post-logo" style="display:inline-block;margin-right:25px">' . $image . '</a>';

        echo '<a href="'. $link .'#events" target="_blank" style="display:inline-block;font-weight:600;vertical-align:top;"><h4>'. $title .'</h4></a>';
        
		echo "</div>".$helpers['end'];
	echo "</div>";
    	
    return ob_get_clean();
    }
}

function update_eventloc($post_id, $post, $update){
    if (!is_admin()){
        return;
    }
    if ($post->post_type != 'ajde_events' ) {
        return;
    }
    $biz = get_post_meta($post_id, 'business_id', true);
    $bloc = get_post_meta( $biz, 'wyz_biz_location', true );
    update_post_meta( $post_id, 'evo_bloc', $bloc );  
}

/*
===========================================================================
 * Woocommerce Custom Code
=========================================================================
*/

//remove tax from local pickup shipping option
add_filter( 'woocommerce_apply_base_tax_for_local_pickup', '__return_false' );
//remove woocommerce sort order form on shop page
//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
//add share to thank you page
add_action('woocommerce_before_thankyou', 'add_social_share');
// First, remove Add to Cart Button
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// Second, add View Product Button
add_action( 'woocommerce_after_shop_loop_item', 'koopo_view_product_button', 10 );
//change tabs order
add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );
/**change breadcrumb home url to shop page*/
add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadcrumb_home_url' ); 
/* Disable Ajax Call from WooCommerce*/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11); 
//Function to add delete account option to users */
add_filter( 'woocommerce_account_orders_columns', 'new_orders_columns', 10, 2 );
//auto delete images when deleting products
add_action( 'before_delete_post', 'delete_product_images', 10, 1 );
// Automatically shortens WooCommerce product titles on the main shop, category, and tag pages
add_action( 'init', function(){

    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    add_action( 'woocommerce_shop_loop_item_title', 'trim_woocommerce_loop_title', 20 );
    remove_action( 'woocommerce_after_single_product_summary','woocommerce_upsell_display',15 );
remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',10 );
add_action( 'woocommerce_after_single_product_summary','woocommerce_upsell_display',10 );
add_action( 'woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',15 );

});

add_action( 'woocommerce_no_products_found', 'show_products_on_no_products_found', 30 );
//add_action( 'woocommerce_after_shop_loop', 'show_products_on_no_products_found' );
//function to add product condition settings 
add_action( 'init', 'create_custom_ktax' );
//add_action ('woocommerce_product_meta_start', 'add_condition_label' );
add_filter ( 'woocommerce_account_menu_items', 'koopo_my_account_items' );
add_filter('woocommerce_short_description', 'add_koopo_tagline');
add_filter( 'woocommerce_add_cart_item_data', 'add_shipping_rule_cart_item', 10, 2 );
//add_filter( 'woocommerce_get_item_data', 'display_shipping_rule_cart_text', 10, 2 );
add_filter( 'woocommerce_cart_item_name', 'display_shipping_rule_cart_text', 10, 2 );
add_action( 'woocommerce_checkout_create_order_line_item', 'add_shipping_rule_text_to_order_items', 10, 4 );
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 10, 2 );
add_filter( 'woocommerce_subscriptions_update_users_role', '__return_false', 100 );

add_action( 'woocommerce_before_shop_loop_item_title', 'top_picks_badge');
add_shortcode( 'koopo-top-picks', 'top_picks_badge');
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );

function custom_woocommerce_get_catalog_ordering_args( $args ) {
    $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

    if ( 'random_list' == $orderby_value ) {
    $args['orderby'] = 'rand';
    $args['order'] = '';
    $args['meta_key'] = '';
    }
    return $args;
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );

function custom_woocommerce_catalog_orderby( $sortby ) {
    $sortby['random_list'] = 'Random';
    return $sortby;
}

function top_picks_badge(){
 $badge = KoopoBlocks\Classes\Koopo::show_top_picks_bagde(get_the_ID());
    echo $badge;
}

//added like buttton to products
add_action( 'woocommerce_single_product_summary', function(){
    echo '<div style="display:inline-block">';
    echo do_shortcode('[INSERT_ELEMENTOR id="216578"]');
    echo '</div>';
},70 );

add_filter('woocommerce_product_upsells_products_heading', function(){

    return 'We Work Well Together';
});
// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'Buy Now', 'woocommerce' ); 
}

// To change add to cart text on product archives(Collection) page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
function woocommerce_custom_product_add_to_cart_text() {
    return __( 'Buy Now', 'woocommerce' );
}
//add_filter('woocommerce_available_payment_gateways', 'hide_stripe_gateway', 10, 1);//hide woocommerce stripe gateway unless subscription.

function hide_stripe_gateway( $available_gateways ) {
    // Not in backend (admin)
    if( is_admin() ) 
        return $available_gateways;
        $prod_subscription = false;

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Get the WC_Product object
        $product = wc_get_product($cart_item['product_id']);
        // Get the product types in cart (example)
        if($product->is_type('subscription')) $prod_subscription = true;
    }
    // Remove Cash on delivery (cod) payment gateway for simple products
    if( !$prod_subscription ) {
        unset($available_gateways['stripe']); // unset 'stripe'
    } elseif ( $prod_subscription ){
        unset($available_gateways['dokan-stripe-connect']); // unset 'stripe'
    }
    return $available_gateways;
}

function hide_shipping_when_free_is_available( $rates, $package ) {

    $instore_only =false;
    $instore_online = false;
    $online_only = false;

    foreach( $package['contents'] as $cart_item ) {
        $product_id = $cart_item['product_id'];
        $shipping_rule = get_post_meta( $product_id, 'shipping_rule', true );

        if (!empty($shipping_rule) && $shipping_rule == 'instore_only'){
            $instore_only =true;
        }
        elseif (!empty($shipping_rule) && $shipping_rule == 'instore_online'){
            $instore_online = true;
        }
        else {
            $online_only = true;
        }  
         //   break; 
    }

 /*   if ( $instore_only == false || $online == false ){
        return $rates;
    }*/

    if ( $online_only == true && !empty($shipping_rule) ) {
        // Loop through your active shipping methods
         foreach( $rates as $rate_id => $rate ) {
            // Remove all other shipping methods other than your defined shipping method
            if ( strpos( $rate_id, 'local_pickup' ) !== false ){
                unset( $rates[$rate_id] );
            }
        }    
    }

    if ( $online_only == false && $instore_online == false ) {
        // Loop through your active shipping methods
        foreach( $rates as $rate_id => $rate ) {
           // Remove all other shipping methods other than your defined shipping method
           if ( strpos( $rate_id, 'flat_rate' ) !== false ){
               unset( $rates[$rate_id] );
           }
       }    
   }
  
  return $rates;
}
function add_shipping_rule_cart_item($cart_item_data, $product_id){
    
    $shipping_rule = get_post_meta( $product_id, 'shipping_rule', true );
    if (empty($shipping_rule)){
        return $cart_item_data;
    }
    $product = wc_get_product( $product_id );
    if ( $product->is_type( 'product_pack' )){
        return $cart_item_data;
    }
    if (!empty($shipping_rule) && $shipping_rule == 'instore_only'):
    $rule = __('Pickup Only','dokan');
    elseif (!empty($shipping_rule) && $shipping_rule == 'instore_online'):
    $rule = __('Pickup and Delivery Available','dokan');
    else:  $rule = __('Not Available For Pickup','dokan');
    endif;

	$cart_item_data['shipping_rule'] = $rule;

	return $cart_item_data;
}

function display_shipping_rule_cart_text($cart_item, $cart_item_key){
   if (isset($cart_item_key['shipping_rule'])) {
        $rules = $cart_item_key['shipping_rule'];
        $cart_item .= '<br><h3 style="color:#24a324;margin-top:8px;margin-bottom: 0px">'. $rules.'</h3>';
   }
	return $cart_item;
}

function add_shipping_rule_text_to_order_items($item, $cart_item_key, $values, $order){
    if ( empty( $values['shipping_rule'] ) ) {
		return;
	}

	$item->add_meta_data( __( 'Shipping', 'dokan' ), $values['shipping_rule'], true );
}

function add_koopo_tagline($description){
    $seller =  get_the_author_meta('ID');

    $tagline = '<p>“Fashion + Beauty + Tech + Home Décor + Health + Food + Visit & More!”';
    $word = 'www.koopoonline.com</p>';
        if ( strpos($description, $word) == false && $seller == 1001026 ) {
        return $description.$tagline;
        }
        return $description;
}


function koopo_my_account_items( $items ){

    unset($items['dashboard']);
    $newItem = [
        'user-account' => __('Dashboard', 'woocommerce'),
    ];
    $items['following'] = __('Favorite Sellers', 'dokan'); 
    $items['subscriptions'] = __('My Subscriptions', 'woocommerce'); 

    $newItems = $newItem + array_slice( $items, 0, count($items), true);
	return $newItems;
 
}
    
add_filter( 'woocommerce_get_endpoint_url', 'misha_hook_endpoint', 10, 4 );
function misha_hook_endpoint( $url, $endpoint, $value, $permalink ){
 
	if( $endpoint === 'user-account' ) {
 
		// ok, here is the place for your custom URL, it could be external
		$url = site_url('/user-account/');
	}
	return $url;
 
}

//remove count from sidebar products category
add_filter( 'facetwp_facet_dropdown_show_counts', function( $return, $params ) {
    if ( 'sidebar_categories' == $params['facet']['name'] ) {
        $return = false;
    }
    return $return;
}, 10, 2 );

function show_products_on_no_products_found() {
    echo '<h2>' . __( 'You may be interested in...', 'domain' ) . '</h2>';
    echo '<div style="clear:both;box_shadow:1px 1px 1px 1px">';
    echo do_shortcode( '[products limit="4" columns="4" category="koopo-social-media-shop-all-instagram-picks"]' );
    echo '</div>';
} 

function trim_woocommerce_loop_title() {
    $p_title = '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h2>'; 
    echo wp_trim_words ($p_title, 9,'...');
}

function dequeue_woocommerce_cart_fragments() { if (is_front_page()) wp_dequeue_script('wc-cart-fragments'); }
/*add store name column to myaccount/orders.php */
function new_orders_columns( $columns = array() ) {   
    // Add new columns
    $columns['store-name'] = __( 'Store', 'woocommerce' );
    return $columns;
}

function woo_custom_breadcrumb_home_url() { 
    return home_url('/shop/'); 
}

function koopo_view_product_button() {
    global $product;
    $link = $product->get_permalink();
    echo '<a href="' . $link . '" class="button view-btn addtocartbutton"><i class="fa fa-eye"></i> VIEW</a>';
}

function woo_reorder_tabs( $tabs ) {

	$tabs['reviews']['priority'] = 15;			// Reviews first
	$tabs['description']['priority'] = 10;			// Description second
	$tabs['seller']['priority'] = 5;	// Additional information third
    unset( $tabs['additional_information'] );

	return $tabs;
}

add_filter( 'woocommerce_product_tabs', function($tabs){
    
    $tabs['seller']['title'] = 'Seller Info';
    $tabs['seller_enquiry_form']['title'] = 'Contact Seller';

    return $tabs;

}, 100 );

function delete_product_images( $post_id )
{
    $product = wc_get_product( $post_id );

    if ( !$product ) {
        return;
    }

    $featured_image_id = $product->get_image_id();
    $image_galleries_id = $product->get_gallery_image_ids();

    if( !empty( $featured_image_id ) ) {
        wp_delete_post( $featured_image_id );
    }

    if( !empty( $image_galleries_id ) ) {
        foreach( $image_galleries_id as $single_image_id ) {
            wp_delete_post( $single_image_id );
        }
    }
}

function create_custom_ktax() {
	register_taxonomy(
		'product_condition',
		'product',
		array(
			'label' => __( 'Product Condition' ),
			'rewrite' => array( 'slug' => 'product_condition' ),
			'hierarchical' => true,
		)
    );
	
}

function add_condition_label (){
	global $post;
	$product_conditions = get_the_term_list( $post->ID, 'product_condition', ' ', ', ', '' );

    if (empty($product_conditions)) {
        return;
    } ?>
					
		<div class="product_condition" id="product_product_condition">
			<p class="product_condition_label"><span></span>
				<?php  echo get_the_term_list( $post->ID, 'product_condition', ' ', ', ', '' ); ?> 
			</p>
			</div>
    <?php 
    }
 

/*
/End custom Woocommerce code
*/

/*
===========================================================================
 * Social Media Sharing
=========================================================================
*/
// Generate the shortcode for the link
add_shortcode('koopo_subscribeauthor', 'wplisten_author_feed');
//add_filter('the_content', 'add_social_share_content' );
//social login hook
//add_action('wyz_before_registration_form', 'social_login_hook_top');
//add_action('wyz_after_login_form', 'social_login_hook');
add_filter('the_excerpt', 'add_social_share_content');
//adding social share icons to woocommerce
//add_action('woocommerce_share', 'add_social_share_counts', 30);not working for some reason.
add_action('single_job_listing_meta_after', 'add_koopo_share_icons' );
//add_action( 'job_listing_meta_end', 'add_koopo_share_icons' );

function add_koopo_share_icons() {
	if ( function_exists('the_champ_init') ){
		echo do_shortcode('[TheChamp-Sharing]');
	}
}
// Generate the feed link
function wplisten_author_feed() {
    $authorfeed = get_author_feed_link( get_the_author_meta('ID'), '');
    $authorfeedlink = '<a class="wyz-button wyz-primary-color" href='. $authorfeed . '><i class="fa fa-rss"></i>Subscribe to Rss Feed</a>' ;
    return $authorfeedlink;
    }
   
   

function add_social_share(){
		if ( ! function_exists( 'ism_shortcode' ) ) {
		return;
	}
    return do_shortcode("[indeed-social-media sm_list='fb,tw,pt,li,tbr,rd,email,sms' sm_template='ism_template_2' sm_list_align='horizontal' sm_display_full_name='false' tc_position='before' tc_theme='dark' ]");
}

function add_social_share_content($content){
	if ( ! function_exists( 'the_champ_init' ) ) {
		return $content;
	}
    $content_share = do_shortcode("[TheChamp-Sharing]");
    $content_share .= $content;

    return $content_share;
}

function add_social_share_counts(){
	if ( ! function_exists( 'ism_shortcode' ) ) {
		return;
	}
    return do_shortcode("[indeed-social-media sm_list='fb,tw,pt,li,tbr,rd,email,sms' sm_template='ism_template_2' sm_list_align='horizontal' sm_display_counts='false' sm_display_full_name='false' tc_position='before' tc_theme='dark' ]");
}

function social_login_hook_top (){
    if (function_exists('the_champ_login_shortcode')){ 
       echo '<div style="text-align:center"><p style="text-transform:uppercase">Connect With:</p>'. do_shortcode('[TheChamp-Login]') . '</div>';
    }
}

function social_login_hook (){
    if (function_exists('the_champ_login_shortcode')){ 
       echo '<div><p style="text-transform:uppercase">Connect With:</p>'. do_shortcode('[TheChamp-Login]') . '</div>';
    }
}

/*
/sharing custom code ends
/ 
*/


/*
===========================================================================
 * Formidable Forms Module
=========================================================================
*/
add_action( 'init', function(){

    add_action( 'frm_after_create_entry', 'create_audio_product_single', 10, 2);
    add_action( 'frm_after_update_entry', 'create_audio_product_single', 10, 2);
    add_action( 'frm_after_create_entry', 'create_album_product',10, 2);
    add_action( 'frm_after_update_entry', 'create_album_product',10, 2);
    add_action( 'frm_after_create_entry', 'create_audio_from_album', 99, 2);//add audio for each file
    add_action( 'frm_after_update_entry', 'create_audio_from_album', 99, 2);
    add_action( 'dokan_product_updated', 'audio_product_dokan_update',10,2);
    add_filter( 'frm_new_post', 'artist_repeat_meta', 10,2);
    add_filter( 'frm_new_post', 'create_a_custom_field', 10, 2 );
    add_filter('frm_field_type', 'restrict_fields', 10, 2);
    // add_filter( 'frm_use_embedded_form_actions', '__return_true' );
});

function restrict_fields($type, $field){
    if(in_array($field->id, array(666,353,618))){ 
        if(!is_admin() && !koopo_level(1)){
            $type = 'hidden'; //hide the fields
        }
    }
    return $type;
  }

function audio_product_dokan_update( $product_id, $data ){

    $frm_entry = get_post_meta( $product_id, 'frm_entry', true );
    
    if (empty($frm_entry)){
        return;
    }

    $form_id = FrmDb::get_var( 'frm_items', array( 'id' => $frm_entry ), 'form_id' );                

    $product = wc_get_product($product_id);
    $excerpt = $product->get_short_description();
    $price = $data['_regular_price'];
    $preview_option = get_post_meta($product_id, 'enable_preview', true);
    $files = get_post_meta($product_id, '_downloadable_files', true);
    $preview = get_post_meta( $product_id, 'preview_file', true );
    $count_files = sizeof($files);
    if ($form_id == 40){
        FrmEntryMeta::update_entry_meta( $frm_entry, 667, null, $price );
    }
    if($form_id == 28){
        FrmEntryMeta::update_entry_meta( $frm_entry, 650, null, $price );
    }

    if (!empty($preview) && $preview_option =='enable_preview'){

        $count_preview = sizeof( $preview );
        if ( $count_preview > 1 ){

            $audio = '[playlist ids="'.$preview.'"]';
        
            wp_update_post( [
                'ID' => $product_id,
                'post_excerpt' => $audio,
            ], true);

        } else {
        
        
            $audio = '[audio mp3="'.$preview.'"]';
            
            wp_update_post( [
                'ID' => $product_id,
                'post_excerpt' => $audio,
            ], true);    
        }
    } else {

    if ($count_files >= 1){
        foreach($files as $la=>$file){

            $file_ids[] = attachment_url_to_postid($file['file']);

        }
        $ids = implode(',',$file_ids);
        $audio = '[playlist ids="'.$ids.'"]';
        
            $product_excerpt = ' [playlist ids="'.$ids.'"]';
            wp_update_post( [
                'ID' => $product_id,
                'post_excerpt' => $product_excerpt,
            ], true);
        
        } else{

            foreach($files as $la=>$file){
                $file_url = $file['file'];
            }
            $audio = '[audio mp3="'.$file_url.'"]';
        
            $strip_excerpt = str_replace($file_url, '...', $excerpt);
            $strip_shortcode = str_replace('[audio mp3="..."]', '...', $strip_excerpt);
            $product_excerpt = $strip_shortcode . ' [audio mp3="'.$file_url.'"]';
            wp_update_post( [
                'ID' => $product_id,
                'post_excerpt' => $product_excerpt,
            ], true);
        }
    }
}

function create_audio_from_album( $entry_id, $form_id ){
    
    if ($form_id == 40){//album form

        if (!isset($_POST['frm_user_id'])){
            return;
        }

        $user               = $_POST['frm_user_id'];
        $userdata           = get_userdata( $user );
        $email              = $userdata->user_email;
        $type               = $_POST['item_meta'][676];
        $title              = $_POST['item_meta'][516];
        $artist_entry_id    = $_POST['item_meta'][528];
        $image              = $_POST['item_meta'][523];

        $banner             = $_POST['item_meta'][551];
        $info               = $_POST['item_meta'][524];
        $parental           = $_POST['item_meta'][675];
        $genre              = array_map( 'intval', $_POST['item_meta'][547]);
        $tags               = $_POST['item_meta'][550];
        /*files*/
        $selected_tracks    = array_map( 'intval', $_POST['item_meta'][552]);
        $select_tracks      = [];
        $upload_option      = $_POST['item_meta'][678];
        $upload_all_files   = $_POST['item_meta'][527];

        if(!empty($upload_all_files)){
            $upload_all     = array_filter($upload_all_files);
        }

        $tracks             = [];
        $add_tracks         = $_POST['item_meta'][519];//track name =521, file = 522, info = 525
        /*selling*/
        $selling            = $_POST['item_meta'][666];
        $price              = '';
        $product_link       = '';
        if( $selling == 'Yes'){
        $price              = $_POST['item_meta'][667];
        $product_link       = $_POST['item_meta'][679];// post meta field 'link_album_to_product'
        }
        /*preview section*/
        $preview_option     = $_POST['item_meta'][668];
        $preview_multi      = $_POST['item_meta'][670];
        $preview_files      = $_POST['item_meta'][671];

        $album_id = FrmDb::get_var( 'frm_items', array( 'id' => $entry_id ), 'post_id' );
        $artist_id = FrmDb::get_var( 'frm_items', array( 'id' => $artist_entry_id ), 'post_id' );

        /*create audio posts from files*/
        if( $upload_option == 'multi_upload' && !empty( $upload_all ) ){

            foreach( $upload_all as $all ){

                $all_url    = wp_get_attachment_url($all);
                $all_name   = basename( get_attached_file($all));
                $all_id     = $all;

                
                $all_data   = [
                    'post_type'     => 'koopo_music',
                    'post_title'    => $all_name,
                    'post_content'  => $info,
                    'post_author'   => $user,
                    'post_status'   => 'publish',
                ];

                $all_meta = [
                    'album_frm_entry'           => $entry_id,
                    'dzsap_meta_playerid'       => $all_id,
                    '_thumbnail_id'             => $image,
                    'dzsap_meta_enable_rates'   => 'on', 
                    'dzsap_meta_enable_likes'   => 'on',
                    'dzsap_meta_type'           => 'detect',
                    'parental_label'            => $parental,
                    'dzsap_meta_productid'      => $product_link,
                    'audio_types'               => $type,
                    'link_track_to_album'       => $album_id,
                    'link_track_to_artist'      => $artist_id,
                ];

                $audio = wp_insert_post($all_data, true);

                foreach ( $all_meta as $key => $value ) {
                update_post_meta( $audio, $key, $value );
                }

                wp_set_object_terms( $audio, $genre, 'genre', false );
                wp_set_object_terms( $audio, $tags, 'music_tags', false );
                update_post_meta($audio, 'dzsap_meta_productid', $product_link);

                FrmEntry::create([
                        'form_id'       => 28,
                        'post_id'       => $audio,
                        'item_key'      => 'entry', //change entry to a dynamic value if you would like
                        'frm_user_id'   => $user,
                        'name'          => $all_name,
                        'item_meta'     => [
                            332 => $all_name,
                            338 => $info,
                            514 => $type,
                            349 => $genre,
                            350 => $tags,
                            343 => 'on',
                            351 => $email,
                            353 => $product_link,
                            352 => $user,
                            345 => 'off',
                            339 => 'detect',
                            344 => 'on',
                            340 => $all_id,
                            363 => 'publish',
                            336 => $image,
                            513 => $parental,
                            662 => $entry_id,
                            677 => $artist_entry_id,
                        ],
                    ]);
                $new_entry_id[] = FrmDb::get_var( 'frm_items', array( 'post_id' => $audio ), 'id' );                
            }
            $the_tracks = $new_entry_id;
            if (empty($selected_tracks)){ 
                $selected_tracks =[];
            }
            $select_tracks = array_merge($the_tracks, $selected_tracks ); 

            $add_these = FrmEntryMeta::add_entry_meta( $entry_id, 552, null, $select_tracks );
            foreach($select_tracks as $selected){

                $select_ids[] = FrmDb::get_var( 'frm_items', array( 'id' => $selected ), 'post_id' );
            }
            update_post_meta($album_id,'select_tracks', $select_ids);

            if ( !empty($add_these) ) {
                FrmEntryMeta::update_entry_meta( $entry_id, 552, null, $select_tracks );
            }
            FrmEntryMeta::delete_entry_meta($entry_id, 527); 

            FrmEntryMeta::update_entry_meta( $entry_id, 527, null, '' );
            update_post_meta($album_id,'upload_all', '');            

        } else {

            foreach( $add_tracks as $r=>$fields ){

                if( is_array($fields)){

                    foreach(array_filter($fields) as $field=>$value){
                        if ( $field == 521 ) {
                        $tracks[$r]['name'] = $value;    
                        } 
                        if ( $field == 522 ) {
                        $tracks[$r]['id'] = $value; 
                        }
                        if ( $field == 525 ) {
                        $tracks[$r]['info'] = $value;    
                        }                         
                    }                        
                }
            }
            
            if(!empty($tracks)){


            foreach($tracks as $track){

                $track_name = $track['name'];
                $track_id = $track['id'];
                $track_info = $track['info'];

                if(empty($track_info)){
                    $track_info = $info;
                }
                if (empty($track_name)){
                    $track_name = basename( get_attached_file($track_id));
                }
                    
                $track_data = [
                    'post_title'  => $track_name,
                    'post_content' => $track_info,
                    'post_status' => 'publish',
                    'post_author' => $user,
                    'post_type' => 'koopo_music',
                ];
    
                $track_meta = [
                    'album_frm_entry' => $entry_id,
                    'dzsap_meta_playerid' => $track_id,
                    '_thumbnail_id' => $image,
                    'dzsap_meta_enable_rates' => 'on', 
                    'dzsap_meta_enable_likes' => 'on',
                    'dzsap_meta_type' => 'detect',
                    'parental_label' => $parental,
                    'audio_types' => $type,
                    'dzsap_meta_productid' => $product_link,
                    'link_track_to_album' => $album_id,
                    'link_track_to_artist' => $artist_id,
                ];
    
                $audio = wp_insert_post($track_data, true);
    
                foreach ( $track_meta as $key => $value ) {
                    update_post_meta( $audio, $key, $value );
                }
                wp_set_object_terms( $audio, $genre, 'genre', false );
                wp_set_object_terms( $audio, $tags, 'music_tags', false );

                update_post_meta($audio, 'dzsap_meta_productid', $product_link);

                FrmEntry::create([
                    'form_id' => 28,
                    'post_id' => $audio,
                    'item_key' => 'entry', //change entry to a dynamic value if you would like
                    'frm_user_id' => $user,
                    'name' => $track_name,
                    'item_meta' => [
                        332  => $track_name,
                        338 => $track_info,
                        514 => $type,
                        349 => $genre,
                        350 => $tags,
                        343 => 'on',
                        344 => 'on',
                        345 => 'off',
                        351 => $email,
                        339 => 'detect',
                        352 => $user,
                        353 => $product_link,
                        340 => $track_id,
                        336 => $image,
                        513 => $parental,
                        662 => $entry_id,
                        363 => 'publish',
                        677 => $artist_entry_id,
                    ],
                ]);

                $new_entry_id[] = FrmDb::get_var( 'frm_items', array( 'post_id' => $audio ), 'id' );
            }
            $the_tracks = $new_entry_id;
            if (empty($selected_tracks)){ 
                $selected_tracks = [];
            }
            $select_tracks = array_merge($the_tracks, $selected_tracks );  

            $add_these = FrmEntryMeta::add_entry_meta( $entry_id, 552, null, $select_tracks );
            foreach($select_tracks as $selected){

                $select_ids[] = FrmDb::get_var( 'frm_items', array( 'id' => $selected ), 'post_id' );
            }
            update_post_meta($album_id,'select_tracks', $select_ids);                
            if ( !empty($add_these) ) {
                FrmEntryMeta::update_entry_meta( $entry_id, 552, null, $select_tracks );
            }
            FrmEntryMeta::delete_entry_meta($entry_id, 519);

        }
    }

    }
}


function create_album_product( $entry_id, $form_id){ 

    if ($form_id == 40 && $_POST['item_meta'][666] === 'Yes' ){  //666 id for product enabled field
        //Get user  
        if (!isset($_POST['frm_user_id'])){
            return;
        }
        $user = $_POST['frm_user_id'];
        //get data

        $user           = $_POST['frm_user_id'];
        $userdata       = get_userdata( $user );
        $email          = $userdata->user_email;
        $type           = $_POST['item_meta'][676];
        $title          = $_POST['item_meta'][516];
        $artist_id      = $_POST['item_meta'][528];
        $image          = $_POST['item_meta'][523];

        $banner         = $_POST['item_meta'][551];
        $info           = $_POST['item_meta'][524];
        $parental       = $_POST['item_meta'][675];
        $genre          = array_map( 'intval', $_POST['item_meta'][547]);
        $tags           = $_POST['item_meta'][550];
        /*files*/
        $selected_tracks    = $_POST['item_meta'][552];
        $select_tracks      = [];
        $upload_option      = $_POST['item_meta'][678];
        $upload_all_files   = $_POST['item_meta'][527];
        $upload_all         = [];

        if(!empty($upload_all_files)){
            $upload_all = array_filter($upload_all_files);
        } 
        $tracks             = [];
        $add_tracks         = $_POST['item_meta'][519];//track name =521, file = 522, info = 525
        /*selling*/
        $selling            = $_POST['item_meta'][666];
        $price              = $_POST['item_meta'][667];
        /*preview section*/
        $preview_option     = $_POST['item_meta'][668];
        $preview_multi      = $_POST['item_meta'][670];
        $preview_files      = $_POST['item_meta'][671];

        $product_link       = $_POST['item_meta'][679];// post meta field 'link_album_to_product'
        $album_id           = FrmDb::get_var( 'frm_items', array( 'id' => $entry_id ), 'post_id' );

        $album_product = wc_get_product($product_link);

        if(!empty($selected_tracks) && empty($add_tracks) && empty($upload_all)){

            foreach($selected_tracks as $selected){
                $selec_posts[] =FrmDb::get_var( 'frm_items', array( 'id' => $selected ), 'post_id' );
            }
            foreach($selec_posts as $spost){
                $selec_ids[] = get_post_meta( $spost, 'dzsap_meta_playerid', true);
            }

            foreach($selec_ids as $select){
                $select_urls[] = wp_get_attachment_url($select);
                $select_names[] = basename( get_attached_file($select));
            }
            
            $count_select_urls = sizeof($select_urls);
            for( $i = 0; $i < $count_select_urls; $i++) {
                if(!empty($select_urls[$i])){
                    $select_files[ md5( $select_urls[$i] ) ] = [
                        'name' => $select_names[$i],
                        'file' => $select_urls[$i],
                    ];
                }
            }

            $the_ids = implode(',', $selec_ids);
            if ($preview_option == 'enable_preview' && !empty($preview_multi)) {
                $the_ids = implode(',', $preview_multi);
            } elseif ($preview_option == 'enable_preview' && !empty($preview_files)) {
                foreach($preview_files as $pr=>$pv){
                    if(is_array($pv)){
                        foreach($pv as $pre=>$vr){
                            if ( $pre == 673 ) {
                                $ptracks[$pr]['name'] = $vr;    
                            } 
                            if ( $pre == 674 ) {
                                $ptracks[$pr]['id'] = $vr; 
                            }                    
                        }
                        foreach($ptracks as $ptrack){
                            $pa_ids[] = $ptrack['id'];
                        }
                    }
                }
                $the_ids = implode(',', $pa_ids);
            }

            $playlist = ' [playlist ids="'.$the_ids.'"]';

            if (!empty($product_link) && get_post_status($product_link) !== false ){
          
                $content = $album_product->get_description();
               
                $downloads = get_post_meta( $product_link, '_downloadable_files', true );
                $product_image = get_post_meta( $product_link,'_thumbnail_id', true);
                if (!empty($downloads)){

                    $diff_files = array_diff($downloads,$select_files);
                    if(!empty($diff_files)){
                        $select_files = array_push($select_files,$diff_files );  
                    }

                }
                if (!empty($product_image)){
                    $image = $product_image;
                }
                if(!empty($content)){
                    $info = $content;
                }
            }

            $ap_data = [
                'post_type' => 'product',
                'post_excerpt' => $playlist,
                'post_title'  => $title,
                'post_content' => $info,
                'post_author' => $user,
                'post_status' => 'publish',
            ];

            $ap_meta = [
                'frm_entry' => $entry_id,
                '_downloadable' => 'yes',
                '_price' => $price,
                '_regular_price' => $price,
                '_tax_status' => 'none',
                '_thumbnail_id' => $image,
                '_virtual' => 'yes',
                'preview_file' => $the_ids,
                '_disable_shipping' => 'yes',
                '_downloadable_files' => $select_files,
            ];

            if (!empty($product_link) && get_post_status($product_link) !== false ){

                wp_update_post([
                    'ID' => $product_link,
                    'post_type' => 'product',
                    'post_excerpt' => $playlist,
                    'post_title'  => $title,
                    'post_content' => $info,
                    'post_author' => $user,
                    'post_status' => 'publish',
                ], true);

                foreach ( $ap_meta as $key => $value ) {
                    update_post_meta( $product_link, $key, $value );
                }

                wp_set_object_terms( $product_link, [6346], 'product_cat', false );
                wp_set_object_terms( $product_link, $tags, 'product_tag', false );

            } elseif(empty($product_link)){

                $a_product = wp_insert_post($ap_data, true); 

                foreach ( $ap_meta as $key => $value ) {
                    update_post_meta( $a_product, $key, $value );
                }

                wp_set_object_terms( $a_product, [6346], 'product_cat', false );
                wp_set_object_terms( $a_product, $tags, 'product_tag', false );

                update_post_meta($album_id, 'link_album_to_product', $a_product);

                foreach($selec_posts as $selec){
                    update_post_meta($selec,'album_productid', $a_product);
                }
                $add_product = FrmEntryMeta::add_entry_meta( $entry_id, 679, null, $a_product );
                
                if ( !empty($add_product) ) {
                    FrmEntryMeta::update_entry_meta( $entry_id, 679, null, $a_product );
                }
            }
        } elseif( $upload_option == 'multi_upload' && !empty($upload_all) ){
            
            $upload_plus = $upload_all;

            if (!empty($selected_tracks)){
                /*get post ids for selected tracks from entry*/

                foreach($selected_tracks as $selected){
                    $selec_posts[] =FrmDb::get_var( 'frm_items', array( 'id' => $selected ), 'post_id' );
                }
                foreach($selec_posts as $spost){
                    $selec_ids[] = get_post_meta( $spost, 'dzsap_meta_playerid', true);
                }

               $upload_plus = array_merge($upload_plus,$selec_ids);//not sure if works
            }

            
            foreach($upload_plus as $all ){
                $all_urls[] = wp_get_attachment_url($all);
                $all_names[] = basename( get_attached_file($all));
            }
            
            $count_all_urls = sizeof($all_urls);
            for( $i = 0; $i < $count_all_urls; $i++) {
                if(!empty($all_urls[$i])){
                    $apfiles[ md5( $all_urls[$i] ) ] = [
                        'name' => $all_names[$i],
                        'file' => $all_urls[$i],
                    ];
                }
            }

            $a_ids = implode(',', $upload_plus);
            if ($preview_option == 'enable_preview' && !empty($preview_multi)) {
                $a_ids = implode(',', $preview_multi);
            } elseif ($preview_option == 'enable_preview' && !empty($preview_files)) {
                foreach($preview_files as $pr=>$pv){
                    if(is_array($pv)){
                        foreach($pv as $pre=>$vr){
                            if ( $pre == 673 ) {
                                $ptracks[$pr]['name'] = $vr;    
                            } 
                            if ( $pre == 674 ) {
                                $ptracks[$pr]['id'] = $vr; 
                            }                    
                        }
                        foreach($ptracks as $ptrack){
                            $pa_ids[] = $ptrack['id'];
                        }
                    }
                }
                $a_ids = implode(',', $pa_ids);
            }

            $playlist = '[playlist ids="'.$a_ids.'"]';

            if (!empty($product_link) && get_post_status($product_link) !== false ){
          
                $content = $album_product->get_description();
               
                $downloads = get_post_meta( $product_link, '_downloadable_files', true );
                $product_image = get_post_meta( $product_link,'_thumbnail_id', true);
                if (!empty($downloads)){

                    $diff_files = array_diff($downloads,$apfiles);
                    if(!empty($diff_files)){
                        $apfiles = array_push($apfiles,$diff_files );  
                    }

                }
                if (!empty($product_image)){
                    $image = $product_image;
                }
                if(!empty($content)){
                    $info = $content;
                }
            }

            $ap_data = [
                'post_type' => 'product',
                'post_excerpt' => $playlist,
                'post_title'  => $title,
                'post_content' => $info,
                'post_author' => $user,
                'post_status' => 'publish',
            ];

            $ap_meta = [
                'frm_entry' => $entry_id,
                '_downloadable' => 'yes',
                '_price' => $price,
                '_regular_price' => $price,
                '_tax_status' => 'none',
                '_thumbnail_id' => $image,
                '_virtual' => 'yes',
                'preview_file' => $a_ids,
                '_disable_shipping' => 'yes',
                '_downloadable_files' => $apfiles,
            ];

            if (!empty($product_link) && get_post_status($product_link) !== false ){

                wp_update_post([
                    'ID' => $product_link,
                    'post_type' => 'product',
                    'post_excerpt' => $playlist,
                    'post_title'  => $title,
                    'post_content' => $info,
                    'post_author' => $user,
                    'post_status' => 'publish',
                ], true);

                foreach ( $ap_meta as $key => $value ) {
                    update_post_meta( $product_link, $key, $value );
                }

                wp_set_object_terms( $product_link, [6346], 'product_cat', false );
                wp_set_object_terms( $product_link, $tags, 'product_tag', false );

            } elseif(empty($product_link)){

                $a_product = wp_insert_post($ap_data, true); 

                foreach ( $ap_meta as $key => $value ) {
                    update_post_meta( $a_product, $key, $value );
                }

                wp_set_object_terms( $a_product, [6346], 'product_cat', false );
                wp_set_object_terms( $a_product, $tags, 'product_tag', false );

                update_post_meta($album_id, 'link_album_to_product', $a_product);
                foreach($selec_posts as $selec){
                    update_post_meta($selec,'album_productid', $a_product);
                }
                $add_product = FrmEntryMeta::add_entry_meta( $entry_id, 679, null, $a_product );
                
                if ( !empty($add_product) ) {
                    FrmEntryMeta::update_entry_meta( $entry_id, 679, null, $a_product );
                }
            }
        } elseif (!empty($add_tracks)) {

            foreach( $add_tracks as $apr=>$apfields ){

                if( is_array($apfields)){

                    foreach(array_filter($apfields) as $apfield=>$apvalue){
                        if ( $apfield == 521 ) {
                            $aptracks[$apr]['name'] = $apvalue;    
                        } 
                        if ( $apfield == 522 ) {
                            $aptracks[$apr]['id'] = $apvalue; 
                        }
                    }
                    
                }
            }
            
            foreach($aptracks as $aptrack){
                if (empty($aptrack['name'])){
                    $aptrack['name'] = basename( get_attached_file($aptrack['id']));
                }
                $aptrack_names[] = $aptrack['name'];
                $aptrack_ids[] = $aptrack['id'];
                $aptrack_urls[] = wp_get_attachment_url($aptrack['id']);

                if (empty($aptrack_names)){
                    $aptrack_names[] = basename( get_attached_file($aptrack['id']));
                }
            }
            if(!empty($selected_tracks)){
                /*get post ids for selected tracks from entry*/

                foreach($selected_tracks as $selected){
                    $selec_posts[] =FrmDb::get_var( 'frm_items', array( 'id' => $selected ), 'post_id' );
                }
                foreach($selec_posts as $spost){
                    $selec_ids[] = get_post_meta( $spost, 'dzsap_meta_playerid', true);
                }

                foreach($selec_ids as $select){
                    $select_urls[] = wp_get_attachment_url($select);
                    $select_names[] = basename( get_attached_file($select));
                }
                
                $aptrack_ids    = array_merge( $aptrack_ids, $selec_ids);//add select ids to array
                $aptrack_urls   = array_merge( $aptrack_urls, $select_urls);//add selec urls to array
                $aptrack_names  = array_merge( $aptrack_names, $select_names);
            }

            $apcount_urls = sizeof($aptrack_urls);
            for( $i = 0; $i < $apcount_urls; $i++) {
                if(!empty($aptrack_urls[$i])){
                    $apfiles[ md5( $aptrack_urls[$i] ) ] = [
                        'name' => $aptrack_names[$i],
                        'file' => $aptrack_urls[$i],
                    ];
                }
            }

            $a_ids = implode(',', $aptrack_ids);
            if ($preview_option == 'enable_preview' && !empty($preview_multi)) {
                $a_ids = implode(',', $preview_multi);
            } elseif ($preview_option == 'enable_preview' && !empty($preview_files)) {
                foreach($preview_files as $pr=>$pv){
                    if(is_array($pv)){
                        foreach($pv as $pre=>$vr){
                            if ( $pre == 673 ) {
                                $ptracks[$pr]['name'] = $vr;    
                            } 
                            if ( $pre == 674 ) {
                                $ptracks[$pr]['id'] = $vr; 
                            }                    
                        }
                        foreach($ptracks as $ptrack){
                            $pa_ids[] = $ptrack['id'];
                        }
                    }
                }
                $a_ids = implode(',', $pa_ids);
            }

            if (!empty($product_link) && get_post_status($product_link) !== false ){
          
                $content = $album_product->get_description();
               
                $downloads = get_post_meta( $product_link, '_downloadable_files', true );
                $product_image = get_post_meta( $product_link,'_thumbnail_id', true);
                if (!empty($downloads)){

                    $diff_files = array_diff($downloads,$apfiles);
                    if(!empty($diff_files)){
                        $apfiles = array_push($apfiles,$diff_files );  
                    }

                }
                if (!empty($product_image)){
                    $image = $product_image;
                }
                if(!empty($content)){
                    $info = $content;
                }
            }

            $playlist = '[playlist ids="'.$a_ids.'"]';

            $ap_data = [
                'post_type' => 'product',
                'post_excerpt' => $playlist,
                'post_title'  => $title,
                'post_content' => $info,
                'post_author' => $user,
                'post_status' => 'publish',
            ];

            $ap_meta = [
                'frm_entry' => $entry_id,
                '_downloadable' => 'yes',
                '_price' => $price,
                '_regular_price' => $price,
                '_tax_status' => 'none',
                '_thumbnail_id' => $image,
                '_virtual' => 'yes',
                'enable_preview' => $preview_option,
                'preview_file' => $a_ids,
                '_disable_shipping' => 'yes',
                '_downloadable_files' => $apfiles,
            ];

            if (!empty($product_link) && get_post_status($product_link) !== false ){

                wp_update_post([
                    'ID' => $product_link,
                    'post_type' => 'product',
                    'post_excerpt' => $playlist,
                    'post_title'  => $title,
                    'post_content' => $info,
                    'post_author' => $user,
                    'post_status' => 'publish',
                ], true);

                foreach ( $ap_meta as $key => $value ) {
                    update_post_meta( $product_link, $key, $value );
                }

                wp_set_object_terms( $product_link, [6346], 'product_cat', false );
                wp_set_object_terms( $product_link, $tags, 'product_tag', false );

            } elseif(empty($product_link)){

                $a_product = wp_insert_post($ap_data, true);

                foreach ( $ap_meta as $key => $value ) {
                    update_post_meta( $a_product, $key, $value );
                }

                wp_set_object_terms( $a_product, [6346], 'product_cat', false );
                wp_set_object_terms( $a_product, $tags, 'product_tag', false );

                update_post_meta($album_id, 'link_album_to_product', $a_product);
                foreach($selec_posts as $selec){
                    update_post_meta($selec,'album_productid', $a_product);
                }

                $add_product = FrmEntryMeta::add_entry_meta( $entry_id, 679, null, $a_product );
                
                if ( !empty($add_product) ) {
                    FrmEntryMeta::update_entry_meta( $entry_id, 679, null, $a_product );
                }
            }   
        }      
    } 
}

function create_audio_product_single( $entry_id, $form_id ){ 
  //  global $audio_product;//assign the new product id to this global then set a check for whether it is empty

    if ( $form_id === 28 && $_POST['item_meta'][618] === 'Yes' ){ //618 id for product enabled field
        //Get user  
        if (!isset($_POST['frm_user_id'])){
            return;
        }
        $user       = $_POST['frm_user_id'];
        //get data
    
        $title      = $_POST['item_meta'][332];
        $info       = $_POST['item_meta'][338];
        $preview    = $_POST['item_meta'][655];
        $price      = $_POST['item_meta'][650];
        $file       = $_POST['item_meta'][340];
        $album      = $_POST['item_meta'][333];
        $album_link = $_POST['item_meta'][662];
        $banner     = $_POST['item_meta']['337'];
        $image      = $_POST['item_meta'][336];
        
        $genre      = $_POST['item_meta'][349];
        $tags       = $_POST['item_meta'][350];
        $pfile      = '';
        $product_link_id    = $_POST['item_meta'][353];
       
        $file_url   = wp_get_attachment_url( $file );
        $parental   = $_POST['item_meta'][513];

        
        $files[ md5( $file_url ) ] = [
        'name' => $title,
        'file' => $file_url,
        ];

        if( $preview == 'enable_preview' ){
            $pfile = $_POST['item_meta'][654];//preview files
            $file_url = wp_get_attachment_url( $pfile );
        }

        $product = wc_get_product($product_link_id);
        
        $audio_player = '[audio mp3="'.$file_url.'"]';

        if (!empty($product_link_id) && get_post_status($product_link_id) !== false ){
            
            $excerpt = $product->get_short_description();
            $content = $product->get_description();
            $strip_excerpt = str_replace($file_url, '.', $excerpt);
            $strip_shortcode = str_replace('[audio mp3="."]', ' ', $strip_excerpt);
            $audio_player = $strip_shortcode . '[audio mp3="'.$file_url.'"]';
            $downloads = get_post_meta( $product_link_id, '_downloadable_files', true );
            $product_image = get_post_meta( $product_link_id,'_thumbnail_id', true);
            if (!empty($downloads)){
                $files = $downloads;
            }
            if (!empty($product_image)){
                $image = $product_image;
            }
            if(!empty($content)){
                $info = $content;
            }
        }

        $adata = [
            'ID' => $product_link_id,
            'post_title'  => $title, 
            'post_content' => $info,
            'post_excerpt' => $audio_player,
            'post_author' => $user,
            'post_type' => 'product',
            'post_status' => 'publish',
        ];

        $product_meta = [  
            'frm_entry' => $entry_id,
            '_downloadable' => 'yes',
            '_price' => $price,
            '_regular_price' => $price,
            '_tax_status' => 'none',
            '_thumbnail_id' => $image,
            '_virtual' => 'yes',
            'enable_preview' => $preview,
            'preview_file' => $file_url,
            '_disable_shipping' => 'yes',
            '_downloadable_files' => $files,
        ];

        if (!empty($product_link_id) && get_post_status($product_link_id) != false ){
        
                wp_update_post( $adata, true );

            foreach ( $product_meta as $key => $value ) {
                update_post_meta( $product_link_id, $key, $value );
            }
            wp_set_object_terms( $product_link_id, [6346], 'product_cat', false );
            wp_set_object_terms( $product_link_id, $tags, 'product_tag', false );

        } elseif(empty($product_link_id)) {
            if ( ! defined('DO_IT_ONCE')) {
                
                define('DO_IT_ONCE', true );        
        
                $new_product = wp_insert_post( $adata, true );
            
            }

            foreach ( $product_meta as $key => $value ) {
            update_post_meta( $new_product, $key, $value );
            }
            
            if( $preview == 'enable_preview' ){
                $pfile = $_POST['item_meta'][654];//preview files
                $file_url = wp_get_attachment_url( $pfile );
                update_post_meta($new_product, 'preview_file', $file_url);
            }

            wp_set_object_terms( $new_product, [6346], 'product_cat', false );
            wp_set_object_terms( $new_product, $tags, 'product_tag', false );

       
            $audio_id = FrmDb::get_var( 'frm_items', array( 'id' => $entry_id ), 'post_id' );
            update_post_meta($audio_id, 'dzsap_meta_productid', $new_product);//update audio item product link
          
         $product_link_id = $new_product;
            $link_product = FrmEntryMeta::add_entry_meta( $entry_id, 353, null, $new_product );
                
            if ( !empty($link_product) ) {
                FrmEntryMeta::update_entry_meta( $entry_id, 353, null, $new_product );
            }

        }
    }
}


/*
* $post (array)
* $args['form'] (object)
* $args['entry'] (object)
* $args['action'] (object)
*/
function artist_repeat_meta($post, $args){

    if ($args['form']->id == 42 ){//artist form
        $social_links = [];
        foreach($_POST['item_meta'][540] as $k => $r ){
            if ( is_array( $r ) ) {
                foreach( $r as $i => $v ) {

                        if ( $i == 543 ) {
                            $social_links[$k]['social'] = $v;    
                        } 
                        
                        if ( $i == 545 ) {
                        $social_links[$k]['link'] = $v;    
                        }
                }
            }
        }
        $post['post_custom']['social_artist_links'] = $social_links;
    }

    return $post;
}

function create_a_custom_field( $post, $args ) {

    $defaultImg = 89131;
    $bookingImg = 481;
    $thumbnail = 336; // change # to the id of the file upload field
    $banner = 337;
    $videoupload = 276;
    $price = 470; //price field in bookable product field
    $entry = $args['entry']->id;
    if ( isset( $_POST['item_meta'][ $thumbnail ] ) ) {
        $field_value = sanitize_text_field( $_POST['item_meta'][ $thumbnail ] );
        $post['post_custom']['dzsap_meta_item_thumb'] = wp_get_attachment_url( $field_value ); 
    }
    //for bookable product set default image if empty
    if ($args['form']->id == 38){
            if ( ! isset( $_POST['item_meta'][ $bookingImg ] ) ) {
                $field_value = $defaultImg;
                $post['post_custom']['_thumbnail_id'] = $field_value; 
            }
        }
    if ( isset( $_POST['item_meta'][ $banner ] ) ) { 
        $field_value = sanitize_text_field( $_POST['item_meta'][ $banner ] );
        $post['post_custom']['dzsap_meta_wrapper_image'] = wp_get_attachment_url( $field_value );
    }

    if ( isset( $_POST['item_meta'][ $videoupload ] ) ) {
        $field_value = sanitize_text_field( $_POST['item_meta'][ $videoupload ] );
        $post['post_custom']['kvid_upload_url'] = wp_get_attachment_url( $field_value ); // change 'custom_field_name_here' to your custom field name
    }

    if ( isset( $_POST['item_meta'][ $price ] ) ) {
        $field_value = sanitize_text_field( $_POST['item_meta'][ $price ] );
        $post['post_custom']['_regular_price'] = $field_value; // change 'custom_field_name_here' to your custom field name
    }
    if ($args['form']->id == 40){
        if ( isset( $_POST['item_meta'][ 552 ] ) ) {//select tracks form (album)
            $track_entries  = $_POST['item_meta'][ 552 ];
                if(is_array($track_entries)){
                foreach( $track_entries as $tentry ){
                    $track = intval($tentry);
                    $tracks[] = FrmDb::get_var( 'frm_items', array( 'id' => $track ), 'post_id' );
                }
            } else{
                $tracks = FrmDb::get_var( 'frm_items', array( 'id' => $track_entries ), 'post_id' );
            }
            foreach($tracks as $song){

                $album = FrmDb::get_var( 'frm_items', array( 'id' => $entry ), 'post_id' );
                update_post_meta($song, 'link_track_to_album', $album);
            }

            $post['post_custom']['select_tracks'] = $tracks; 
        }
    }
    if ($args['form']->id == 28){

        if (isset($_POST['item_meta'][662])){

            $album = FrmDb::get_var( 'frm_items', array( 'id' => $_POST['item_meta'][662] ), 'post_id' );
            $post['post_custom']['link_track_to_album'] = $album;

        }
    }

    if (isset($entry)){
        $field_value = $entry;
        $post['post_custom']['frm_entry_id'] = $field_value;
    }
    return $post;
}

/*End of Formidabled */

/*
===========================================================================
 * Membership Custom Code
=========================================================================
*/
add_action( 'init', function(){
    add_action( 'user_register', 'add_free_access', 10, 1 );
    //functions to add and remove roles based on membership actions
    add_action('ihc_new_subscription_action', 'kbadd_new_roles', 10, 2);
    add_action('ump_on_update_action', 'remove_newkb_roles' );
});


// if affiliate 
function is_affiliate() {
    if (class_exists('UAP_Main')){
    global $indeed_db;
	$uid = indeed_get_uid();
    if (empty($uid)) return false;
    if (current_user_can('administrator')) return true;
	$is_affiliate = $indeed_db->is_user_an_active_affiliate($uid);
	if (empty($is_affiliate)) return false;
				return true;
    }
}

function influence_access() {
    if (! is_user_logged_in() ){
        return;
    }
    $user_id = get_current_user_id();
    $access = str_split(str_replace( ',', '', get_user_meta( $user_id, 'ihc_user_levels', true) ) );
    $access1 = get_user_meta( $user_id, 'ihc_user_levels', true );
    if ( array_search( '7', $access ) || current_user_can('administrator') || $access1 == 7 ) {
        return true;
    }
}

function affiliate_access() {
    if (! is_user_logged_in() ){
        return;
    }
    $user_id = get_current_user_id();
    $access = str_split(str_replace( ',', '', get_user_meta( $user_id, 'ihc_user_levels', true) ) );
    $access1 = get_user_meta( $user_id, 'ihc_user_levels', true );
    if ( array_search( '4', $access ) || current_user_can('administrator') || $access1 == 4 ) {
        return true;
    }
}

function remove_newkb_roles($user_id){
    $u = get_userdata( $user_id );
    $access = explode(',', get_user_meta($user_id, 'ihc_user_levels', true) );
    $access1 = get_user_meta( $user_id, 'ihc_user_levels', true );
   // print_r($access);

        if (!in_array( 7, $access ) || $access1 !== '7') {
            $u->remove_role( 'influencer' );
        }
        if (!in_array( 4, $access ) || $access1 !== '4') {
            $u->remove_role( 'affiliate' );
        }
}
function kbadd_new_roles($uid, $lid ){
    $access = explode(',', get_user_meta($uid, 'ihc_user_levels', true) );
 //   print_r($access);
    if ( in_array( 4, $access ) || $lid == '4' ) {
        $u = get_userdata( $uid );
        $u->add_role( 'affiliate' );
    }
    if ( in_array( 7, $access ) || $lid == '7' ) {
        $u = get_userdata( $uid );
        $u->add_role( 'influencer' );      
    }

}

function add_free_access( $user_id ) {
    $user_pack_id = get_user_meta( $user_id, 'product_package_id', true );
	if ($user_pack_id == '' || $user_pack_id == 12092 ){     
        update_user_meta($user_id, 'product_package_id', 12092);
        update_user_meta( $user_id, 'product_pack_startdate', date( 'Y-m-d H:i:s' ) );
        update_user_meta( $user_id, 'product_pack_enddate', 'unlimited' );
        update_user_meta( $user_id, 'dokan_admin_percentage', 10 );
        update_user_meta( $user_id, 'can_post_product', '1' );
        update_user_meta( $user_id, 'product_no_with_pack', 10 );
        update_user_meta( $user_id, '_customer_recurring_subscription', '' );
        if(function_exists('pmpro_changeMembershipLevel')){
            pmpro_changeMembershipLevel( 1, $user_id );
            }
    	}
}

function koopo_level($access){
    global $user;
    $user_id = get_current_user_id();
    $user_pack_id    = get_user_meta( $user_id, 'product_package_id', true );
    $num = '';
    $levels = [
            '0' => [12092,4361,206674,4315,64304,4351,64291,5727,42793],
            '1' => [4315,64304,206674,4351,64291,4361,5727,42793],
            '2' => [4351,64291, 4361,5727,42793],
            '3' => [4361,5727,42793]   
    ];
    foreach ($levels as $l => $level) {

        if (in_array($user_pack_id, $level) ){
            $num = $l;
        } 

        if ($num == $access || current_user_can('administrator') ){
            return true;
        } 
    }  
}


/*
===========================================================================
 * Dokan Custom Code
=========================================================================
*/
if (function_exists('dokan')) {
    add_filter( 'dokan_query_var_filter', 'dokan_load_extra_menus' );
    add_filter( 'dokan_get_dashboard_nav', 'dokan_add_extra_menus', 30,1 );
    add_filter( 'dokan_get_dashboard_settings_nav', 'modify_dashboard_nav', 30,1 );
    add_action( 'dokan_load_custom_template', 'dokan_load_template' );
    //add_action( 'dokan_product_edit_after_options', 'add_design_product_options', 90, 3 );//add design settings to product settings (dokan)
    //add_action('dokan_process_product_meta', array('lumise_woocommerce', 'woo_process_product_meta_fields_save'), 10, 2);//save design for products
    // Save business link to product and is booking fields
    add_action( 'dokan_process_product_meta', 'x_wyzi_fields_save' );
    //add_action( 'dokan_new_product_added', 'x_wyzi_fields_save' );
    //redirect to seller registration after subscription pack is purchased if role is customer-logout
    add_action( 'dokan_dashboard_right_widgets', 'add_dokan_subscription_widget', 10 );
    add_action( 'user_dashboard_right_widgets', 'add_dokan_subscription_widget', 10 );
    //update store location meta for kstreet products
    //add_action('admin_init', 'update_location');
    //add_action( 'dokan_process_product_meta', 'update_location' );
    add_shortcode( 'show_store', 'show_seller_store' );
    }
    add_action( 'template_redirect', 'wc_custom_redirect_after_purchase' );
    add_action('dokan_product_types', 'add_external_type',30);
    add_action('dokan_settings_after_banner','add_extra_dokan_store_settings', 10,2 );
    add_action('dokan_seller_meta_fields', 'add_extra_seller_fields');
    add_action ('dokan_process_seller_meta_fields', 'save_extra_seller_fields');
    add_action('dokan_new_product_added', 'add_product_condition',10,2);
    add_action('dokan_product_updated', 'add_product_condition',10,2);
    add_filter('dokan_dashboard_settings_helper_text','change_help_text', 20,2);
   // add_filter('dokan_email_classes', 'add_new_seller_approved'); this is causing errors
    add_filter('dokan_email_list', 'new_seller_approved_template');
    add_action( 'user_dashboard_right_widgets2', 'get_announcement_widget', 12 );
    add_filter( 'dokan_sub_shortcode', 'custom_dokan_packs',10,2 );
    add_action( 'dokan_product_edit_after_title', 'add_the_external',10,2 ); 
    
    function add_the_external($post, $post_id){
    
        $product = wc_get_product( $post_id );
        
        if ($product->is_type('external')){
            $product_url = $product->get_product_url();
        }
            ?>
            <div class="dokan-form-group dokan-hide show_if_external">
                <label for="_product_url" class="form-label"><?php esc_html_e( 'External Link', 'dokan-lite' ); ?></label>
                <input type="url" placeholder="https://example.com" name="_product_url" class="dokan-form-control" value="<?php echo isset($product_url)?$product_url:'' ?>"/>
                <div class="dokan-product-title-alert dokan-hide">
                    <?php esc_html_e( 'Please enter external product link!', 'dokan-lite' ); ?>
                </div>
            </div>
        <?php
    }

    function add_external_type($types){
        if (koopo_level(3)){
        $types['external'] = __('External Product', 'koopo');
        }
        return $types;
    }
    
    
    function custom_dokan_packs($contents, $subscription_packs){
        global $post;
        $user_id            = dokan_get_current_user_id();
        $helper = new DokanPro\Modules\Subscription\Helper();
        ob_start();
        ?>
    
        <div class="dokan-subscription-content">
            <?php
                $subscription = dokan()->vendor->get( $user_id )->subscription;
            ?>

            <?php if ( $subscription && $subscription->has_pending_subscription() ) : ?>
                <div class="seller_subs_info">
                    <?php printf(
                            __( 'The <span>%s</span> subscription is inactive due to payment failure. Please <a href="?add-to-cart=%s">Pay Now</a> to active it again.', 'dokan' ),
                            $subscription->get_package_title(),
                            $subscription->get_id()
                        );
                    ?>
                </div>
            <?php elseif ( $subscription && $subscription->can_post_product() ) : ?>
                <div class="seller_subs_info">
                    <p>
                    <?php
                        if ( $subscription->is_trial() ) {
                            $trial_title = $subscription->get_trial_range() . ' ' . $subscription->get_trial_period_types();

                            printf( __( 'Your are using <span>%s (%s trial)</span> package.', 'dokan' ), $subscription->get_package_title(), $trial_title  );
                        } else {
                            printf( __( 'Your are using <span>%s</span> package.', 'dokan' ), $subscription->get_package_title() );
                        }
                    ?>
                    </p>
                    <p>
                    <?php
                        $no_of_product = '-1' !== $subscription->get_number_of_products() ? $subscription->get_number_of_products() : __( 'unlimited', 'dokan' );

                        if ( $subscription->is_recurring() ) {
                            printf( __( 'You can add <span>%s</span> products', 'dokan' ), $no_of_product );
                        } elseif ( $subscription->get_pack_end_date() === 'unlimited' ) {
                            printf( __( 'You can add <span>%s</span> product(s) for <span> unlimited</span> days.', 'dokan' ), $no_of_product );
                        } else {
                            printf( __( 'You can add <span>%s</span> product(s) for <span>%s</span> days.', 'dokan' ), $no_of_product, $subscription->get_pack_valid_days() );
                        }
                    ?>
                    </p>
                    <p>
                        <?php
                            if ( $subscription->has_active_cancelled_subscrption() ) {
                                $date   = date_i18n( get_option( 'date_format' ), strtotime( $subscription->get_pack_end_date() ) );
                                $notice = sprintf( __( 'Your subscription has been cancelled! However it\'s is still active till %s', 'dokan' ), $date );
                                printf( "<span>{$notice}</span>" );
                            } else {
                                if ( $subscription->is_trial() ) {
                                    // don't show any text
                                } elseif ( $subscription->is_recurring() ) {
                                    echo sprintf( __( 'You will be charged every %d', 'dokan' ), $subscription->get_recurring_interval() ) . ' ' . $helper->recurring_period( $subscription->get_period_type() );
                                } elseif ( $subscription->get_pack_end_date() === 'unlimited' ) {
                                    printf( __( 'You have a lifetime package.', 'dokan' ) );
                                } else {
                                    printf( __( 'Your package will expire on <span>%s</span>', 'dokan' ), date_i18n( get_option( 'date_format' ), strtotime( $subscription->get_pack_end_date() ) ) );
                                }
                            }
                        ?>
                    </p>

                    <?php
                        if ( ! ( ! $subscription->is_recurring() && $subscription->has_active_cancelled_subscrption() ) ) {
                            ?>
                            <p>
                                <form action="" method="post">
                                    <?php
                                        $maybe_reactivate = $subscription->is_recurring() && $subscription->has_active_cancelled_subscrption();
                                        $notice           = $maybe_reactivate ? __( 'activate', 'dokan' ) : __( 'cancel', 'dokan' );
                                        $nonce            = $maybe_reactivate ? 'dps-sub-activate' : 'dps-sub-cancel';
                                        $input_name       = $maybe_reactivate ? 'dps_activate_subscription' : 'dps_cancel_subscription';
                                        $btn_class        = $maybe_reactivate ? 'btn-success' : 'btn-danger';
                                        $again            = $maybe_reactivate ? __( 'again', 'dokan' ) : '';
                                    ?>

                                    <label><?php _e( "To {$notice} your subscription {$again} click here &rarr;", "dokan" ); ?></label>

                                    <?php wp_nonce_field( $nonce ); ?>
                                    <input type="submit" name="<?php echo esc_attr( $input_name ); ?>" class="<?php echo esc_attr( "btn btn-sm {$btn_class}" ); ?>" value="<?php echo esc_attr( ucfirst( $notice ) ); ?>">
                                </form>
                            </p>
                            <?php
                        }
                    ?>
                </div>
                <?php  else :  ?>
        <div class="seller_subs_info"> 
            <p><b>Your account is not fully activated.</b></p>
            <p class="add-text" style="text-align:left">Please select a subscription package to fully activate your account to unlock your Koopo Store features.</p> 
            </div>	
            <?php endif; ?>

            <?php if ( $subscription_packs->have_posts() ) {
                ?>

                <?php if ( isset( $_GET['msg'] ) && 'dps_sub_cancelled' === $_GET['msg'] ) : ?>
                    <div class="dokan-message">
                        <?php
                            if ( $subscription && $subscription->has_active_cancelled_subscrption() ) {
                                $date   = date_i18n( get_option( 'date_format' ), strtotime( $subscription->get_pack_end_date() ) );
                                $notice = sprintf( __( 'Your subscription has been cancelled! However the it\'s is still active till %s', 'dokan' ), $date );
                            } else {
                                $notice = __( 'Your subscription has been cancelled!', 'dokan' );
                            }
                        ?>

                        <p><?php printf( $notice ); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ( isset( $_GET['msg'] ) && 'dps_sub_activated' === $_GET['msg'] ) : ?>
                    <div class="dokan-message">
                        <?php
                            esc_html_e( 'Your subscription has been re-activated!', 'dokan' );
                        ?>
                    </div>
                <?php endif; ?>

                <div class="pack_content_wrapper">

                <?php
                while ( $subscription_packs->have_posts() ) {
                    $subscription_packs->the_post();

                    // get individual subscriptoin pack details
                    $sub_pack           = dokan()->subscription->get( get_the_ID() );
                    $is_recurring       = $sub_pack->is_recurring();
                    $recurring_interval = $sub_pack->get_recurring_interval();
                    $recurring_period   = $sub_pack->get_period_type();
                    ?>

                    <div class="product_pack_item <?php echo ( $helper->is_vendor_subscribed_pack( get_the_ID() ) || $helper->pack_renew_seller( get_the_ID() ) ) ? 'current_pack ' : ''; ?><?php echo ( $sub_pack->is_trial() && $helper->has_used_trial_pack( get_current_user_id(), get_the_id() ) ) ? 'fp_already_taken' : ''; ?>">
                        <div style="line-height:1" class="pack_price">

                            <span class="dps-amount">
                                <?php echo wc_price( $sub_pack->get_price() ); ?>
                            </span>

                            <?php if ( $is_recurring && $recurring_interval === 1 ) { ?>
                                <span class="dps-rec-period">
                                    <span class="sep">/</span><?php echo $helper->recurring_period( $recurring_period ); ?>
                                </span>
                            <?php } ?>
                        </div><!-- .pack_price -->

                        <div class="pack_content">
                    <!--h2><?php echo $sub_pack->get_package_title(); ?></h2-->
                    <?php  the_post_thumbnail( 'large' );?>

                            <?php // the_content();

                            $no_of_product = $sub_pack->get_number_of_products();

                            ?><div class="pack_data_option"><?php

                            if ( '-1' === $no_of_product ) {
                                printf( __( '<strong>Unlimited</strong> Products <br />', 'dokan' ) );
                            } else {
                                printf( __( '<strong>%d</strong> Products <br />', 'dokan' ), $no_of_product );
                            }

                            ?>

                            <?php if ( $is_recurring && $sub_pack->is_trial() && $helper->has_used_trial_pack( get_current_user_id() ) ) : ?>
                                <span class="dps-rec-period">
                                    <?php printf( __( 'for %d %s(s)', 'dokan' ), $recurring_interval, $helper->recurring_period( $recurring_period ) ); ?>
                                </span>
                            <?php elseif ( $is_recurring && $sub_pack->is_trial() ) : ?>
                                <span class="dps-rec-period">
                                    <?php printf( __( 'for %d %s(s) <p class="trail-details">%d %s(s) trial </p>', 'dokan' ), $recurring_interval, $helper->recurring_period( $recurring_period ), $sub_pack->get_trial_range(), $helper->recurring_period( $sub_pack->get_trial_period_types() ) ); ?>
                                </span>
                            <?php elseif ( $is_recurring && $recurring_interval >= 1) : ?>
                                <span class="dps-rec-period">
                                    <?php printf( __( 'for %d %s(s)', 'dokan' ), $recurring_interval, $helper->recurring_period( $recurring_period ) ); ?>
                                </span>
                            <?php else :
                                if ( $sub_pack->get_pack_valid_days() == 0 ) {
                                    printf( __( 'For<br /><strong>Unlimited</strong> Days', 'dokan' ) );
                                } else {
                                    $pack_validity = $sub_pack->get_pack_valid_days();
                                    printf( __( 'For<br /><strong>%s</strong> Days', 'dokan' ), $pack_validity );
                                }
                            endif; ?>
                        <h3 style="text-align:center"><strong>Koopo Online Fees</strong></h3>
                        <strong>2.9% + 30¢</strong> Payment Process Fee<br>
                        <?php $admin_fee = get_post_meta(get_the_id(), '_subscription_product_admin_commission', true);
                        echo '<strong>' . $admin_fee . ' %</strong> Referral Fee</div>';
                                ?>
                        </div>

                        <div class="buy_pack_button">
                            <?php if ( $helper->is_vendor_subscribed_pack( get_the_ID() ) ): ?>

                                <a href="<?php echo get_permalink( get_the_ID() ); ?>" class="dokan-btn dokan-btn-theme buy_product_pack"><?php _e( 'Your Access', 'dokan' ); ?></a>

                            <?php elseif ( $helper->pack_renew_seller( get_the_ID() ) ): ?>

                                <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="dokan-btn dokan-btn-theme buy_product_pack"><?php _e( 'Renew', 'dokan' ); ?></a>

                            <?php else: ?>

                                <?php if ( $sub_pack->is_trial() && $helper->vendor_has_subscription( dokan_get_current_user_id() ) && $helper->has_used_trial_pack( dokan_get_current_user_id() ) ): ?>
                                    <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="dokan-btn dokan-btn-theme buy_product_pack"><?php _e( 'Switch Plan', 'dokan' ); ?></a>
                                <?php elseif ( $sub_pack->is_trial() && $helper->has_used_trial_pack( dokan_get_current_user_id() ) ) : ?>
                                    <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="dokan-btn dokan-btn-theme buy_product_pack"><?php _e( 'Select', 'dokan' ); ?></a>

                                <?php elseif ( ! $helper->vendor_has_subscription( dokan_get_current_user_id() ) ) : ?>
                                    <?php if ( $sub_pack->is_trial() ) : ?>
                                        <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="dokan-btn dokan-btn-theme buy_product_pack trial_pack"><?php _e( 'Start Free Trial', 'dokan' ); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="dokan-btn dokan-btn-theme buy_product_pack"><?php _e( 'Select', 'dokan' ); ?></a>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <a href="<?php echo do_shortcode( '[add_to_cart_url id="' . get_the_ID() . '"]' ); ?>" class="dokan-btn dokan-btn-theme buy_product_pack"><?php _e( 'Switch Plan', 'dokan' ); ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<h3>' . __( 'No subscription found!', 'dokan' ) . '</h3>';
            }

            wp_reset_postdata();
            ?>
            <div class="clearfix"></div>
            </div>
        </div>
        <?php

        $contents = ob_get_clean();

        return $contents;
    }
     
    add_filter('dps_get_subscription_pack_arg', 'hide_subs');
    
    function hide_subs($args){
        $args['post__in'] = [12092,4361,4315,64304,4351,64291,5727];//this hides these product packs from the list the post__not_in not working.  
      return  $args;
    }
    
    add_filter('dokan_vendor_biography_title', function(){
    
       return 'About Seller';//this changes the store about tab
         
    });
    //add_action('add_fav_sellers', 'add')
    function get_announcement_widget() {
        if ( !current_user_can( 'dokan_view_overview_menu' ) ) {
            return;
        }
    
        if ( !current_user_can( 'dokan_view_announcement' ) ) {
            return;
        }
    
        if (!function_exists('dokan_pro')){
            return;
        }
        $template_notice = dokan_pro()->notice;
        $query           = $template_notice->get_announcement_by_users( apply_filters( 'dokan_announcement_list_number', 3 ) );
    
        $args = array(
            'post_type'   => 'dokan_announcement',
            'post_status' => 'publish',
            'orderby'     => 'post_date',
            'order'       => 'DESC',
            'meta_key'    => '_announcement_type',
            'meta_value'  => 'all_seller',
        );
    
        $template_notice->add_query_filter();
    
        $all_seller_posts = new \WP_Query( $args );
    
        $template_notice->remove_query_filter();
    
        $notices = array_merge( $all_seller_posts->posts, $query->posts );
    
        dokan_get_template_part( 'dashboard/announcement-widget', '', array(
            'pro'              => true,
            'notices'          => $notices,
            'announcement_url' => dokan_get_navigation_url( 'announcement' ),
        )
        );
    }
    
add_action('init', function(){

    if (class_exists('WeDevs\Dokan\Rewrites')){

        $rewrite = new WeDevs\Dokan\Rewrites();

        $rewrite->full_template = add_filter('template_include', function($template){
        //adding full width template
        $store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
        $store_info   = $store_user->get_shop_info();
        $full_layout = false;
        
        if( isset( $store_info['full_layout'] ) AND $store_info['full_layout'] == 'yes' ) { 
            $full_layout = true;
        }

        $rewrite = new WeDevs\Dokan\Rewrites();

        $store_name = get_query_var( $rewrite->custom_store_url );

        if ( ! $rewrite->is_woo_installed() || get_query_var('toc') ) {
            return $template;
        }

        if ( ! empty( $store_name ) && $full_layout ) {
            $store_user = get_user_by( 'slug', $store_name );

            // Bell out for Vendor Stuff extensions
            if ( ! is_super_admin( $store_user->ID ) && user_can( $store_user->ID, 'vendor_staff' ) ) {
                return get_404_template();
            }

            // no user found
            if ( ! $store_user ) {
                return get_404_template();
            }

            // check if the user is seller
            if ( ! dokan_is_user_seller( $store_user->ID ) ) {
                return get_404_template();
            }

            return dokan_locate_template( 'store-full.php' );
        }

        return $template;

        });
    }
});

/*function add_new_seller_approved($wc_emails){

  //  require( esc_url(plugins_url('/classes/NewSellerApproved.php', dirname(__FILE__) ) ));

    $wc_emails['Dokan_Email_New_Seller_Approved'] = new \KoopoBlocks\Classes\NewSellerApproved(); 
    return $wc_emails;
} */

function new_seller_approved_template($dokan_emails){

    $new = ['new-seller-approved.php'];
    return array_merge($new,$dokan_emails);
}

function change_help_text( $help_text, $query_vars ){
    
        //   global $wp;
        $enable_shipping       = ( isset( $dokan_shipping_option['enabled'] ) ) ? $dokan_shipping_option['enabled'] : 'yes';

        $help_text = '';

        if ( isset( $query_vars ) && $query_vars == 'payment' ) {
            $help_text = __( 'Below are the payment methods available for your store.  Please keep this account information updated.  You must setup a Stripe account prior to selling on Koopo if you want to receive payment instantly.', 'dokan-lite' );
        }

        if ( $query_vars == 'shipping' ) {

            $help_text = sprintf ( '<p>%s %s  <a href="%s">%s</a></p><p>%s</p>',
                __( 'Use shipping zones below (a geographic region where a certain set of shipping methods are offered). This will match customers to a single zone using their shipping address. Good for local delivery.', 'dokan' ),
                __( 'For basic shipping settings,', 'dokan' ),
                esc_url( dokan_get_navigation_url('settings/regular-shipping' ) ),
                __( 'Click Here.', 'dokan' ),
                __( 'Sellers are responsible for their shipping. So, it is important to setup shipping with care.  If you need help, check out <a href="https://koopoonline.com/koopo-guide-section/shipping-guide/">Shipping Guide</a>.', 'dokan' )			

            );
            /*
            if ( 'yes' == $enable_shipping ) {
                $help_text .= sprintf ( '<p>%s <a href="%s">%s</a></p>',
                __( 'If you want to use basic shipping system then', 'dokan' ),
                    esc_url( dokan_get_navigation_url('settings/regular-shipping' ) ),
                    __( 'Click Here', 'dokan' )
                );
            }*/
        }

        return $help_text;
    
}
add_filter('dokan_profile_completion_values', function($progress_values){
    $progress_values = [
        'banner_val'          => 15,
        'profile_picture_val' => 15,
        'store_name_val'      => 10,
        'address_val'         => 10,
        'phone_val'           => 10,
        'map_val'             => 15,
        'payment_method_val'  => 15,
        'social_val'          => [
            'fb'       => 4,
            'twitter'  => 2,
            'youtube'  => 2,
            'linkedin' => 2,
        ],
        ];

        return $progress_values; 
});
function add_product_condition($product_id, $postdata){

    if ( ! is_user_logged_in() ) {
        return;
    }

    if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
        return;
    }

    if ( ! isset( $_POST['dokan_add_new_product_nonce'] ) ) {
        return;
    }

    $postdata = wp_unslash( $_POST );

    if ( ! wp_verify_nonce( sanitize_key( $postdata['dokan_add_new_product_nonce'] ), 'dokan_add_new_product' ) ) {
        return;
    }

    $condition = -1;
    $condition 	= intval( $postdata['product_condition'] );
    if( isset( $postdata['product_condition'] ) && !empty( $postdata['product_condition'] ) ) {
    
    wp_set_object_terms( $product_id, (int) $postdata['product_condition'], 'product_condition' );	
        
    }
}
    
function add_extra_seller_fields($user){

    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }

    if ( ! user_can( $user, 'dokandar' ) ) {
        return;
    }

    $store_settings        = dokan_get_store_info( $user->ID );
    $website               = ! empty( $store_settings['website'] ) ? $store_settings['website'] : '';
    $description           = ! empty( $store_settings['description'] ) ? $store_settings['description'] : '';

    ?>
    
    <!--these fields below were added to show additional seller information-->
    <tr>
        <th><?php _e( 'Business Website', 'dokan-lite' ); ?></th>
        <td>
            <input type="text" name="website_name" class="regular-text"  value="<?php echo esc_attr($website); ?>">
        </td>
    </tr>
    
    <tr>
        <th><?php _e( 'Store Description', 'dokan-lite' ); ?></th>
        <td>
            <textarea type="text" rows="5" cols="30" name="store_description"  class="regular-text"><?php echo esc_attr( $description ); ?></textarea> 
            <p><?php _e( 'Describe the store and provide details about what types of products will be sold.', 'dokan-lite' ); ?></p>
        </td>
    </tr>
        
<?php

}
    
function save_extra_seller_fields($user_id){

    $store_settings        = dokan_get_store_info( $user_id );

    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }

    $post_data = wp_unslash( $_POST );

    if ( isset( $post_data['dokan_update_user_profile_info_nonce'] ) && ! wp_verify_nonce( $post_data['dokan_update_user_profile_info_nonce'], 'dokan_update_user_profile_info' ) ) {
        return;
    }
    if ( ! isset( $post_data['dokan_enable_selling'] ) ) {
        return;
    }
    $store_settings['description'] = sanitize_text_field( $post_data['store_description'] );
    $store_settings['website'] = sanitize_text_field( $post_data['website_name'] );

        update_user_meta( $user_id, 'dokan_profile_settings', $store_settings );
        update_user_meta( $user_id, 'storedescription', $store_settings['description'] );	   
        update_user_meta( $user_id, 'website', $store_settings['website'] );
}

function add_extra_dokan_store_settings( $user_id, $profile_info ){
    if (!$user_id){
        return;
    }

    $under_construction 	= isset( $profile_info['under_construction'] ) ? $profile_info['under_construction'] : 'no';
    $full_layout 	= isset( $profile_info['full_layout'] ) ? $profile_info['full_layout'] : 'no';
    $description	= isset( $profile_info['description'] ) ? $profile_info['description'] : '';
    ?>
        <div style="background: #0f0f0fb3;margin: 10px auto;max-width: 900px;border-radius: 5px;">
            <h3 style="color:#fff;font-size:18px;padding:5px"><i class="fa fa-warning"></i> Is Your Store Ready or Under Construction</h3>
        </div>
        <div class="dokan-form-group" style="margin: 0 auto; max-width: 800px;">
            <label class="dokan-w3 dokan-control-label">Under Construction</label>
            <div class="dokan-w5 dokan-text-left">
                <div class="checkbox">
                
                    <label>
                    <input type="hidden" name="under_construction_setting" value="no">
                        <input type="checkbox" name="under_construction_setting" value="yes" <?php checked( $under_construction, 'yes' ); ?> /> <?php _e( 'Select if your store is under construction. Leave blank if your store is live and ready.', 'dokan-lite' ); ?> 
                    </label>
                </div>
            </div>
        </div>

        <div style="background: #0f0f0fb3;margin: 20px auto;max-width: 900px;border-radius: 5px;">
                    
            <h3 style="color:#fff;font-size:18px;padding:5px"><i class="far fa-newspaper"></i> Layout Settings</h3>
        </div>
        <div class="dokan-form-group" style="margin: 30px auto">
            <label class="dokan-w3 dokan-control-label"><?php _e( 'Default/Full-Width Banner', 'dokan-lite' ); ?></label>
            <div class="dokan-w5 dokan-text-left">
                <div class="checkbox">
                    <label>
                    <input type="hidden" name="full_layout_setting" value="no">
                        <input type="checkbox" name="full_layout_setting" value="yes"<?php checked( $full_layout, 'yes' ); ?>> <?php _e( 'Select for full-width banner layout', 'dokan-lite' ); ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="dokan-form-group">
            <label class="dokan-w3 dokan-control-label" for="dokan_store_description"><?php esc_html_e( 'Store Description', 'dokan-lite' ); ?></label>

            <div class="dokan-w5 dokan-text-left">
                <textarea id="dokan_store_description"  type="text" name="dokan_store_description" placeholder="<?php esc_html_e( 'Enter short description', 'dokan-lite'); ?>" class="dokan-form-control"><?php echo esc_html_e( $description ); ?></textarea>
            <p style="font-size:12px"> This will be displayed on your store page.</p>
            </div>
        </div>

    <?php

}
add_action('dokan_store_profile_saved','save_extra_dokan_store_settings', 10, 2);
function save_extra_dokan_store_settings($store_id, $dokan_settings){
    if (!$store_id){
        return;
    }
    $dokan_settings = get_user_meta( $store_id, 'dokan_profile_settings', true );

    $data = [
            'description'				   => isset( $_POST['dokan_store_description'] ) ? sanitize_text_field( $_POST['dokan_store_description'] ):'',
            'full_layout'				   => isset( $_POST['full_layout_setting'] ) ? sanitize_text_field( $_POST['full_layout_setting'] ):'no',
            'under_construction'		   => isset( $_POST['under_construction_setting'] ) ? sanitize_text_field( $_POST['under_construction_setting'] ):'no'
            ];

    $settings_data = wp_parse_args( $data, $dokan_settings);
    //  $dokan_settings = array_merge( $prev_dokan_settings, $dokan_settings );

    update_user_meta( $store_id, 'dokan_profile_settings', $settings_data );
        
}
function dokan_load_extra_menus( $query_vars ) {
    $query_vars['help'] = 'help';
    $query_vars['listings'] = 'listings';
    //   $query_vars['inbox'] = 'inbox';
    return $query_vars;
}

function dokan_add_extra_menus( $urls ) {
    
    $urls['help'] = array(
        'title' => __( 'Seller Help', 'dokan'),
        'icon'  => '<i class="fa fa-question"></i>',
        'url'   => dokan_get_navigation_url( 'help' ),
        'pos'   => 85
    );
    if (current_user_can('business_owner') || current_user_can('administrator') ){
        $urls['listings'] = array(
            'title' => __( 'Main Dashboard', 'dokan'),
            'icon'  => '<i class="fas fa-long-arrow-alt-left"></i>',
            'url'   => get_home_url( null,'/user-account#my-business' ),
            'pos'   => 2
        );
    }
    if (class_exists('WC_Product_Booking')){
    $urls['booking'] = array(
        'title' => __( 'Advance Booking', 'dokan' ),
        'icon'  => '<i class="fa fa-calendar"></i>',
        'url'   => dokan_get_navigation_url( 'booking' ),
        'pos' 	=> 180
        );
    }
    if ( !koopo_level(2) ) {

            unset($urls['booking']);
            unset($urls['staffs']);
            unset($urls['support']);
    }
    
    if ( !koopo_level(1) ) {
        unset($urls['coupons'] );
        unset($urls['tools'] );
        unset($urls['followers']);

        }
    
    return $urls;
}
function modify_dashboard_nav ($sub_settins){
    if ( !koopo_level(1) ) {
        unset($sub_settins['seo'] );
        }

        $sub_settins['shipping']['title'] = __( 'Shipping/Delivery', 'dokan' );

        return $sub_settins;
}

function dokan_load_template( $query_vars ) {
    if ( isset( $query_vars['help'] ) ) {
        dokan_get_template_part( 'help/help', '', array( 'pro'=>true ) );
            return;
    }
}

    
add_action( 'dokan_vendor_purchased_subscription', 'add_pmpro_koopo_sub', 10, 1 );
function add_pmpro_koopo_sub($customer){

    $sub = get_user_meta($customer,'product_package_id', true);

    $levels = [
        '1' => [12092],//starter
        '2' => [4361,5727,42793],//all access
        '3' => [4351,64291],//business
        '4' => [4315,64304, 206674] //entrepreneuer  
    ];
    foreach ($levels as $num => $packs) {

        if (in_array($sub, $packs) ){
            $level = $num;
        } 
    }  
    if (!empty($level)){
        if(function_exists('pmpro_changeMembershipLevel')){
            pmpro_changeMembershipLevel( $level, $customer );
        }
    }

}


function add_design_product_options () {
    if (!class_exists('lumise_woocommerce')) {
        return;
    }
    if (  koopo_level(2) ) {
                dokan_get_template_part( 'products/design' );
        } else { ?>
            <div class="dokan-other-options dokan-edit-row dokan-clearfix <?php echo $class; ?>">
                <div class="dokan-section-heading" data-togglehandler="dokan_other_options">
                    <h2><i class="html5_video" aria-hidden="true"></i> <?php _e( 'Design Options', 'dokan-lite' ); ?></h2>
                    <p><?php _e( 'Make Product Customizable', 'dokan-lite' ); ?></p>
                    <a href="#" class="dokan-section-toggle ">
                        <i class="fa fa-sort-desc fa-flip-vertical" aria-hidden="true"></i>
                    </a>
                    <div class="dokan-clearfix"></div>
                </div>

                <div class="dokan-section-content">
                <?php
            dokan_get_template_part('global/dokan-error', '', array( 'deleted' => false, 'message' => __( 'You do not have access to this feature.  Upgrade your subscription to "All Access" to add videos to your products.', 'dokan-lite' ) ) );
            ?> </div></div><?php
            return;
        }
}								   

function x_wyzi_fields_save( $post_id ){
        
    $wyz_listing = $_POST['ns-listing-connected'];
    $wyz_bookable = $_POST['ns-booked_appointment'];
    $shipping_rule = $_POST['shipping_rule'];
    $tags = $_POST['product_tag'];
    $product_url = $_POST['_product_url'];

    if( !empty( $wyz_listing ) ) {
    update_post_meta($post_id, 'business_id', $_POST["ns-listing-connected"]);
    } else {
        update_post_meta($post_id, 'business_id', '');
    }
    if( !empty( $wyz_bookable ) ) {
    update_post_meta($post_id, '_booked_appointment', $_POST["ns-booked_appointment"]);
    }
    if( !empty( $shipping_rule ) ) {
        update_post_meta($post_id, 'shipping_rule', $_POST['shipping_rule']);
    }

    if (!empty($product_url)){
        $product= wc_get_product($post_id);
            $product->update_meta_data('_product_url', $product_url);
            $product->save();
    }
    
    if(!empty($tags)){

        foreach($tags as $term) {
            if (is_numeric($term)){
            $tag = get_term($term, 'product_tag');
            $seotags[] = $tag->name;
            } else {
                $seotags[] = $term;
            }
        }
        $seotags = array_slice( $seotags,0,4);//limit to 4
        $seotags = implode( ',', $seotags);
        update_post_meta( $post_id, '_aioseop_keywords', $seotags ); 
    }
    
}

function wc_custom_redirect_after_purchase() {
    if (!function_exists('woocommerce')) {
        return;
    }
    if ( ! is_wc_endpoint_url( 'order-received' ) ) return;

    // Define the product IDs in this array
    $product_ids = array( 12092, 4361, 4351, 4315, 64304, 64291, 5727 ); // or an empty array if not used
    
    $redirection = false;

    global $wp;
    $order_id =  intval( str_replace( 'checkout/order-received/', '', $wp->request ) ); // Order ID
    $order = wc_get_order( $order_id ); // Get an instance of the WC_Order Object
    $user = wp_get_current_user();
    if ( !in_array( 'seller', (array) $user->roles ) ) {
    // Iterating through order items and finding targeted products
        foreach( $order->get_items() as $item ){
            if( in_array( $item->get_product_id(), $product_ids ) ) {
                $redirection = true;
                break;
            }
        }
    }

    // Make the custom redirection when a targeted product has been found in the order
    if( $redirection ){
        wp_redirect( home_url( '/user-account/?udpage=vendor-form' ) );
        exit;
    }
}

//add subscription widget to dokan dashboard
function add_dokan_subscription_widget() {
        if ( ! current_user_can( 'dokan_view_sales_report_chart' ) || !class_exists('DokanPro\Modules\Subscription\SubscriptionPack') ) {
            return;
        }
        dokan_get_template_part( 'dashboard/subscription-widget', '' );
    }
    

function dokan_exists() {
    if (! function_exists('dokan')) {
        return;
    }
    return dokan_is_store_page();
}

function update_location(){
    global $post;
    $args = array(
        'post_type' => 'product',
        'posts_per_page'   => 20,
        // 'post_author' => '',
    );

    $posts = get_posts($args);
    foreach ( $posts as $post ) {
        $locate = get_post_meta( $post->ID, '_dokan_geolocation_use_store_settings', false);
        $locate = 'yes';
        $dokan_geo_latitude  = get_user_meta( $post->post_author, 'dokan_geo_latitude', true );
        $dokan_geo_longitude = get_user_meta( $post->post_author, 'dokan_geo_longitude', true );
        $dokan_geo_public    = get_user_meta( $post->post_author, 'dokan_geo_public', true );
        $dokan_geo_address   = get_user_meta( $post->post_author, 'dokan_geo_address', true );

        update_post_meta( $post->ID, '_dokan_geolocation_use_store_settings', $locate );

        update_post_meta( $post->ID, 'dokan_geo_latitude', $dokan_geo_latitude );
        update_post_meta( $post->ID, 'dokan_geo_longitude', $dokan_geo_longitude );
        update_post_meta( $post->ID, 'dokan_geo_public', $dokan_geo_public );
        update_post_meta( $post->ID, 'dokan_geo_address', $dokan_geo_address );
        }
    }

function show_seller_store(){
    // $authoro = get_the_author();
    $seller =  get_the_author_meta('ID');
    if (! dokan_is_user_seller( $seller ) || ! dokan_is_seller_enabled($seller) ){
        return;
    }
    $store_user         = dokan()->vendor->get( get_query_var( 'author' ) );
    $store_info      = dokan_get_store_info( $seller );
    $banner_id       = ! empty( $store_info['banner_id'] ) ? $store_info['banner_id'] : 0;
    $banner_id       = ! empty( $store_info['banner'] ) ? $store_info['banner'] : $banner_id;
    $store_name = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'dokan-lite' );
    $description = isset( $store_info['description'] ) ? esc_html($store_info['description'] ) : '';
    $store_url  = dokan_get_store_url( $seller );
    $store_address  = dokan_get_seller_short_address( $seller );
    $seller_rating  = dokan_get_seller_rating( $seller );
    $banner_url = ( $banner_id ) ? wp_get_attachment_image_src( $banner_id, 'thumbnail' ) : DOKAN_PLUGIN_ASSEST . '/images/default-store-banner.png';
    if( isset( $store_info['under_construction'] ) AND $store_info['under_construction'] == 'yes')  { 
        return;
    }

    ?><h3>Shop My Store</h3>
    <div id="dokan-seller-listing-wrap">
    <div class="seller-listing-content">
        <ul class="dokan-seller-wrap">
            <li style="width:100%" class="dokan-single-seller woocommerce coloum-<?php echo esc_attr( '1' );?> <?php echo ( ! $banner_id ) ? 'no-banner-img' : ''; ?>">

<div style="box-shadow:none" class="store-wrapper">
        <div class="store-content">
            <div class="store-info" style="background-image: url( '<?php echo is_array( $banner_url ) ? esc_attr( $banner_url[0] ) : esc_attr( $banner_url ); ?>');">
                <div class="store-data-container">
                    <div class="featured-favourite">
                        <?php if ( ! empty( $featured_seller ) && 'yes' == $featured_seller ): ?>
                            <div class="featured-label"><?php esc_html_e( 'Featured', 'dokan-lite' ); ?></div>
                        <?php endif ?>

                    </div>

                    <div class="store-data">
                        <h2><a href="<?php echo esc_attr($store_url); ?>"><?php echo esc_html($store_name); ?></a></h2>
                        
                        <p style="font-size:14px;width:75%"><?php echo wp_trim_words($description,5,"...");?></p>

                        <?php if ( !empty( $seller_rating['count'] ) ): ?>
                            <div class="star-rating dokan-seller-rating" title="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'dokan-lite' ), esc_attr( $seller_rating['rating'] ) ) ?>">
                                <span style="width: <?php echo ( esc_attr( ($seller_rating['rating']/5) ) * 100 - 1 ); ?>%">
                                    <strong class="rating"><?php echo esc_html( $seller_rating['rating'] ); ?></strong> <?php _e( 'out of 5', 'dokan-lite' ); ?>
                                </span>
                            </div>
                        <?php endif ?>
                    <br>
                    <?php do_action( 'dokan_seller_listing_after_store_data', $seller, $store_info ); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="store-footer">
            <div class="seller-avatar">
            <img src="<?php echo esc_url( $store_user->get_avatar() ) ?>"
                            alt="<?php echo esc_attr( $store_user->get_shop_name() ) ?>"
                            size="150">
                <?php //echo get_avatar( $seller, 150 ); ?>
            </div>
            <a href="<?php echo esc_url( $store_url ); ?>" class="dokan-btn dokan-btn-theme"><?php esc_html_e( 'Visit Store', 'dokan-lite' ); ?></a>

        </div>
    </div>
    </li>
    <div class="dokan-clearfix"></div>
</ul> <!-- .dokan-seller-wrap -->
</div>
</div>
    <?php

}
//dokan custom code ends
