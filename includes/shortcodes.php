<?php
//custom shortcodes
add_shortcode('author_social', 'user_social_links');//wepage social links
add_shortcode( 'business_p', 'show_business_p');//business profile widget
add_shortcode( 'biz-favbtn', 'show_biz_fav_button');//fav business button
add_shortcode('biz-cat-icon', 'get_biz_cat_icon'); //business category icon
add_shortcode('biz-cat-term', 'get_biz_cat_term');//business category term
add_shortcode('biz-rating', 'get_biz_rating'); 
add_shortcode('biz-sticky', 'get_biz_sticky'); 
add_shortcode('paid-in-my-city', 'get_biz_paid_badge'); 
add_shortcode('biz-location-link', 'get_biz_location_link'); 
add_shortcode('koopo-cats', 'koopo_tax_widget' );
add_shortcode('parent-location', 'get_location_parents');
add_shortcode('places', 'create_location_list');
add_shortcode('kpost-content', 'content_shortcode');
add_shortcode('mobile-footer-menu', 'add_mobile_footer');
add_shortcode('biz-directions', 'get_biz_directions');
add_shortcode('open-close', 'show_biz_hours');
add_shortcode('buy-audio-btn', 'buy_audio_button');
add_shortcode('buy-audio-link', 'buy_audio_link');//for elementor button
add_shortcode('location-events', 'show_location_events');
add_shortcode( 'user_events', 'user_events_shortcode');
add_shortcode( 'album-tracks', 'album_tracks_loop');
add_shortcode( 'banner-upload', 'banner_button' );
add_shortcode( 'profile-upload', 'profile_button' );
add_shortcode( 'social-artist-links', 'social_artist_links' );
add_shortcode('music-meta', 'get_music_meta');
add_shortcode( 'wyz-paginate', function(){
    return wyz_pagination(); 
});

function get_music_meta( $atts ){
    global $post;
    $file = get_post_meta($post->ID, 'dzsap_meta_playerid', true);
    $like_count = get_post_meta($file, '_dzsap_likes', true);
    $play_count = get_post_meta($file, '_dzsap_views', true);
    $output = '';
    extract(shortcode_atts([
        'likes' => 'yes',
        'plays' => 'no',
    ], $atts));

    if ($likes == 'yes'){
        $output .= '<div><i class="fa fa-heart-o"></i> '.$like_count.'</div>';
    } 
    if ($plays == 'yes') {

        $output .= '<div><i class="fa fa-play"></i> '.$play_count.'</div>';
    } 
    
    return $output;

}
function social_artist_links(){
    
    global $post;
    $social_links =[];
    $social_links = get_post_meta($post->ID, 'social_artist_links', true);
    if ($post->post_type == 'albums'){
    $artist = get_post_meta($post->ID, 'link_album_to_artist', true);
    $social_links = get_post_meta($artist, 'social_artist_links', true);
    }
    if (!empty($social_links)){
        echo '<div class="social-artist-links" >';
            foreach( $social_links as $social_link ){
                
                $link = $social_link['link'];               
                $social = $social_link['social'];
        ?><a href="<?php echo $link ?>"><i class="fa fa-<?php echo $social ?>"></i></a>
        <?php  }
    }

    echo '</div>';
    
} 

function banner_button(){
    if (!is_user_logged_in()){
        return;
    } 
    global $post;
    $user = get_current_user_id();
    if ($user == $post->post_author){
        echo '<a id="newBanner" href="#" title="Upload/Change Banner" class="uploadBtn"><i class="fa fa-camera"></i></a>';
    }
}

function profile_button(){
    if (!is_user_logged_in()){
        return;
    } 
    global $post;
    $user = get_current_user_id();
    if ($user == $post->post_author){
        echo '<a id="newProfile" href="#" title="Upload/Change Profile" class="uploadBtn"><i class="fa fa-camera"></i></a>';
    }
}
//add the comments form to post single

function album_tracks_loop(){
    global $post;
    
    $id = !empty($post)? $post->ID:get_the_ID();
    $tracks = [];
    $tracks =  get_post_meta( $id, 'tracks' );
    foreach($tracks as $array){
        
        foreach($array as $field => $value ){
            $title = $value['track-title'];
            $trackid = $value['track-file'];

            
            $track_url = get_attachment_link( $trackid );
            $trackimg = get_the_post_thumbnail_url( $id, 'thumbnail' );
            $audio = do_shortcode('[zoomsounds_player songname="'.$title.'"  type="detect" dzsap_meta_playerid="'.$trackid.'" dzsap_meta_source_attachment_id="'.$trackid.'"   config="basic-wave" enable_likes="yes" enable_views="yes" enable_rates="yes" play_in_footer_player="on"  enable_download_button="yes"]');
        
            $content = '<div class="audio-track"><div class="track we-audio-player">'.$audio.'</div><div style="width:20%;display:inline-block" class="imgCol"><img src="'.$trackimg.'"/></div></div>'; 
            echo $content; 
        }
    }
    
}
add_shortcode('wp-comments', function(){

	return comments_template();
}); 

function custom_elementor_evofilter($wp_arguments){
    global $template_type;
    $id =  get_the_ID();
    $kids = array(
        'post_type' => 'wyz_location',
        'child_of' => $id,	
    );
    
    $children =  get_pages($kids);
    if ( !empty ($children)) {
        foreach( $children as $child ) {

            $child_ids[]  = $child->ID;
    
        }

        array_push( $child_ids, $id );
        
    } else {
        $child_ids[] = $id;
    }
    $eargs = [
//        'post_type' => 'ajde_events',
        'meta_query' => [
           'relation' => 'OR', 
                [
                    'key' => 'evo_bloc', 
                    'value' => $child_ids,
                    'compare' => 'IN',
                ],
                [
                'key' => '_koopo_location',
                'value' => $child_ids,
                'compare' => 'IN',
                ],
        ],
    ];
    $events = array_merge( $wp_arguments, $eargs );
    $wp_arguments = $events;
    return $wp_arguments;
} 

function show_location_events(){

    echo '<div id="loc_events">';
    add_filter('eventon_wp_query_args','custom_elementor_evofilter');

    echo do_shortcode('[add_eventon_list number_of_months="12" show_limit="no" tile_style="1" event_past_future="future" hide_month_headers="yes" hide_mult_occur="yes" ft_event_priority="yes" tiles="yes" tile_height="430" tile_bg="1"]');
  echo '</div>';
  
  }
  function custom_we_evofilter($wp_arguments){
    global $post;
    $curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
    $author =  $curauth->ID;
    if(empty($author)){
        $author = $post->post_author;
    }
    
    $args = array(
        'author' => $author
    );
    $events = array_merge( (array)$wp_arguments, $args );
    $wp_arguments = $events;
    return $wp_arguments;
} 

  function user_events_shortcode($content) {
     
       add_action('eventon_wp_query_args','custom_we_evofilter');
       $content = do_shortcode('[add_eventon_list tiles="yes" subscriber="yes" tile_count="3" ft_event_priority="yes" tile_height="270" hide_month_headers="yes" tile_bg="1"]');
       return $content;
   }
function buy_audio_link($content){
    global $post;

    $productid = get_post_meta($post->ID,'dzsap_meta_productid',true);

    if(empty($productid)){
        return;
    }
    $content =  '?add-to-cart="'.$productid.'"';

    return $content;
}


function buy_audio_button(){
    global $post;

    $productid = get_post_meta($post->ID,'dzsap_meta_productid',true);
    $sell_this = get_post_meta($post->ID, 'sell_this', true);
    $album_product = get_post_meta($post->ID,'link_album_to_product',true);
    $album_productid = get_post_meta($post->ID,'album_productid',true);//for single track album button
    

    if(empty($productid)){
        return;
    }
    if ( $sell_this !== 'Yes'){
        return;
    }
    echo '<div>';
    if(!empty($productid)){?>
        <a class="button addtocartbutton" href="/?add-to-cart=<?php echo $productid ?>">Buy</a>
    <?php }
    if(!empty($album_productid)){?>
       <a class="button addtocartbutton" href="/?add-to-cart=<?php echo $album_productid ?>">Buy Album</a>
    <?php }
    if(!empty($album_product)){?>
       <a class="button addtocartbutton" href="/?add-to-cart=<?php echo $album_product ?>">Buy <i class="fas fa-cart-plus"></i></a>
    <?php }
    echo '</div>';
}

function show_biz_hours() {

    $id = get_the_ID();
    $status = WyzHelpers::open_close($id);
    
if (!empty($status['txt']) && $status['txt'] == 'Open Now'):
        echo '<p style="color:#2ab562;border:solid 1px #2ab562" class="open-close">'.$status['txt'].'</p>';
elseif (!empty($status['txt']) && $status['txt'] == 'Closed Now'):
        echo '<p style="color:red;border:solid 1px red" class="open-close">'.$status['txt'].'</p>';
endif;
}

function get_biz_directions(){
    global $post;
     $id=$post->ID;
     echo WyzHelpersOverride::wyz_biz_map_directions($id);
 }

function add_mobile_footer(){
ob_start();?>
    <style>

            #selectator_wyz-cat-filter {
                display:none;
            }
            .brand img {
                width:90%;
                max-width:165px;
            }

            ul.footer-mobile-menu {
                display: flex;
                flex-flow:row nowrap;
                gap: 1em;
                list-style: none;
                margin:0;
                overflow-x:scroll;
            }
            .mobile-footer {
                background: #fff;
                z-index: 999;
                position: fixed;
                bottom: 0;
                padding:6px 10px 0;
                width:100%;
                overflow:hidden;
                box-shadow: 1px 1px 17px 2px rgba(0,0,0,0.1);
            }
            li.foot-item {
                text-align: center;
                font-size: 16px;
                padding: 5px 5px 0;
            }
            .mobile-foot-link i {
                font-size:20px;
            }
            .foot-item:active{
                transform: translateY(2px);
                box-shadow: 0px 0px 10px 4px #ffcc01;
                font-size: 14px;
            }
            .mobile-foot-link * {
                display:flex;
                flex-wrap:wrap;
                justify-content:center;
                padding:3px;       
            }
            @media(min-width:875px){
                .mobile-footer{
                    display:none;
                }
            }

			</style>
			<div  class="mobile-footer">
				<ul class="footer-mobile-menu">
					<li class="foot-item"><a class="mobile-foot-link" href="<?php echo home_url()?>"><i class="fas fa-home"></i>Home</a></li>
					<li class="foot-item"><a id="mobile-new-post" class="mobile-foot-link addNewBtn" href="#"><i class="fas fa-plus"></i>Post</a></li>
					<li class="foot-item"><a class="mobile-foot-link" href="<?php echo home_url('/user-account/')?>"><i class="fas fa-user"></i>Account</a></li>
					<li class="foot-item"><a class="mobile-foot-link" href="<?php echo home_url('/shop/')?>"><i class="fas fa-shopping-cart"></i>Shop</a></li>
					<li class="foot-item"><a class="mobile-foot-link" href="<?php echo home_url('/business/')?>"><i class="fas fa-map-marked"></i>Biz</a></li>
					<li class="foot-item"><a class="mobile-foot-link" href="<?php echo home_url('/events/')?>"><i class="far fa-calendar-alt"></i>Events</a></li>
                    <li class="foot-item"><a class="mobile-foot-link" href="<?php echo home_url('/kvidz/')?>"><i class="fa fa-video-camera"></i>Video</a></li>
					<li class="foot-item"><a class="mobile-foot-link searchBtn" href="#"><i class="fas fa-search-plus"></i>Search</a></li>
					<li class="foot-item"><a class="mobile-foot-link" href="<?php echo home_url('/influencers-square/')?>"><i class="far fa-square"></i>Square</a></li>
					<li class="foot-item"><a class="mobile-foot-link" href="https://docs.koopoonline.com"><i class="far fa-question-circle"></i>Help</a></li>
				</ul>
			</div>
            <?php
        return ob_get_clean();	
}

function content_shortcode($content){
    $content = the_content();
    return $content;
}; 

function create_location_list($atts){

    $atts =shortcode_atts( [
        'parent'  => '65254',
        'per_page' => '-1',
    ], $atts, 'list_location' );

    extract($atts);
    $output ='';
    $args = [
        'post_type' => 'wyz_location',
        'posts_per_page' => $per_page,
        'post_parent' => $parent,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ]; 

   // $places = new WP_Query($args);
      $places = get_posts($args);

      if (!empty($places)){
        $output .= '<div class="places-list container"><div class="clear"></div>';

        foreach($places as $place){

            $output .= '<div class="place"><a href="'.get_the_permalink($place->ID).'">'.get_the_title($place->ID).'</a></div>';

        }
        $output .= '</div>';
        wp_reset_postdata();
      }

    return $output;
}

function get_location_parents(){
    global $post;
    $l_id = get_the_ID();
    $states = get_post_ancestors( $l_id );
        if (!empty($states)) { ?>
                <?php
            foreach( $states as $state ) {

                $link = get_the_permalink($state);
                $name = get_the_title($state);
                $image = WyzHelpers::get_post_thumbnail_url( $state, 'location');
                
                echo '<a href="'. $link .'"><h2>In '. $name .'</h2></a>';
    
            } 
        }
}

function koopo_tax_widget( $args= [] ){
    global $post;
    extract(shortcode_atts([
        'type' => 'kvidz_categories'
    ], $args));
  
    $terms = wp_get_post_terms($post->ID, $type );
    if (is_wp_error($terms)){
        return;
    }
        if (!empty($terms)) {
            $output = [];
            foreach ($terms as $term) {
                $output[] = '<a href="' . get_term_link( $term->slug, $type ) . '">' . $term->name . '</a>';
            }
        echo join( '<br> ', $output );
    }
}

function get_biz_location_link(){
    global $post;
    $id = $post->ID;
    $business_data = WyzBusinessPost::wyz_get_business_data($id);

     if ( '' !== $business_data['country_name'] ) { 
       echo esc_url( $business_data['country_link'] );
    }
}

function get_biz_paid_badge() {
    global $post;
    $id=$post->ID;
    $business_data = WyzBusinessPost::wyz_get_business_data($id);

    $sticky = is_sticky();
    $badge = get_post_meta($id, '_paid_in_mycity', true);
    $local_tag = 'Shop Smart. Produce Local';
    if ( $sticky || !empty($badge)):
        echo '<div style="display:inline-block"><p class="local-tag" style="color:#23A455">'.$local_tag.'</p></div>';
        echo '<a href="https://paidinmycity.com" target="_blank"><img class="kbadge" src="https://koopoonline.com/wp-content/uploads/2018/09/paid-indetroit-small.png"></a>';
    endif;
}

function get_biz_sticky(){
    $sticky = is_sticky();

    if ( $sticky ) {?>
            <div class="sticky-notice featured-banner"><span class="wyz-primary-color wyz-prim-color"><?php esc_html_e( 'FEATURED', 'wyzi-business-finder' );?></span></div>
    <?php }
}

function get_biz_rating(){
    global $post;
    $id = $post->ID;
    $business_data = WyzBusinessPost::wyz_get_business_data($id);
    ?>
    
    <?php if ( 0 != $business_data['rate_number'] ) {
        echo '<div class="rate" ><span>';
        $rate = $business_data['rate'];
        for ( $i = 0; $i < 5; $i++ ) {

            if ( $rate > 0 ) {
                echo '<i class="fa fa-star star-checked" aria-hidden="true"></i>';
                $rate--;
            } else {
                echo '<i class="fa fa-star star-unchecked" aria-hidden="true"></i>';
            }
        }
        echo '</span></div>';
    }?>
    
<?php
}

function get_biz_cat_term(){
    global $post;
    $id = $post->ID;
    $business_data = WyzBusinessPost::wyz_get_business_data($id);
    ?>
        <a class="biz-cat-term" href="<?php echo esc_url( $business_data['category']['link'] );?>">
        <?php echo esc_attr( $business_data['category']['name'] ) ?>
                </a>
    <?php 
}

function get_biz_cat_icon(){
    global $post;
    $id = $post->ID;
    $business_data = WyzBusinessPost::wyz_get_business_data($id);
    ?>
        <a class="busi-post-label" style="background-color:<?php echo esc_attr( $business_data['category']['color'] );?>;" href="<?php echo esc_url( $business_data['category']['link'] );?>">
					<img src="<?php echo esc_url( $business_data['category']['icon'] );?>" alt="<?php echo esc_attr( $business_data['category']['name'] );?>" />
				</a>
    <?php 
}

function show_biz_fav_button(){
    if(class_exists('WyzHelpers')){
        global $post;
        $id = $post->ID;
        $business_data = WyzBusinessPost::wyz_get_business_data($id);
        WyzPostShare::the_favorite_button( $business_data['id'] ); 

    }
}

function show_business_p(){
    if (function_exists('dokan')){
        $store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
        $author = $store_user->get_id();
        
        if (empty($author)){
            $seller =  get_the_author_meta('ID');
            $author = $seller;
        }

    } else {
        $auth = get_user_by( 'slug', get_query_var( 'author_name' ) );
        $author =  $auth->ID;
    }

    $args = [
        'post_type' => 'wyz_business',
        'post_status' => 'publish',
        'author' => $author,
        'fields' => '',
        'meta_query' => [
            [
                'key' => 'wyzi_claim_fields_2',
                'value'=> 'showbiz',
                'compare' => 'IN',
            ]
        ],
	];
    
    $user_biz = get_posts($args);

    if (empty($user_biz)){
        return;
    }
        foreach ($user_biz as $biz){
            $bid = $biz->ID;
            if ( empty($bid) ) {
                return;
            }
            if (!class_exists('WyzHelpers')){
                return;
            }
        // $has_products = WyzHelpers::business_has_products($bid, $seller_id);
        
         //   if ( $has_products ){
                $post_type  = 'wyz_business';
                $link       = get_the_permalink($bid);
                $burl       = class_exists('WyzHelpers')?WyzHelpers::get_post_thumbnail_url( $bid, $post_type ):'';
                $emptyurl   = 'https://koopoonline.com/wp-content/uploads/2017/01/koopo_print_WjOGCiw4cJ-5-e1563046973666.png';  
                ?>
               <div class="biz-block" id="business_p"><a href="<?php echo $link ?>"><img src="<?php echo !empty($burl)?$burl:$emptyurl ?>" ></a>
               <h2><?php echo get_the_title($bid) ?></h2>
             <a class="button" href="<?php echo $link ?>">Follow My Business</a></div>
        <?php
     //       }
        } 
    //    $content = ob_get_contents();
    //    ob_end_clean();
        wp_reset_postdata();

    //    return $content;

}
    //user social share/follow buttons
    function user_social_links(){
        $curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
 
       $author =  $curauth->ID;
       $facebook = get_user_meta( $author, 'facebook', true);
       $instagram = get_user_meta( $author, 'instagram', true);
       $twitter = get_user_meta( $author, 'twitter', true);
       $linkedin = get_user_meta( $author, 'linkedin', true);
       $pinterest = get_user_meta( $author, 'pinterest', true);
       $youtube = get_user_meta( $author, 'youtube', true);
       
       $facebook_link = '<a class="user_social" style="background:#4267B2" target="_blank" href="'.  $facebook  .'"><i class="fab fa-facebook-f"></i></a>';
       $instagram_link = '<a class="user_social" style="background-image: radial-gradient(at top right, #0c12b5 15%, #ef6128 100%)" target="_blank" href="'. $instagram .'"><i class="fa fa-instagram"></i></a>';
       $pinterest_link = '<a class="user_social" style="background:#cc2329" target="_blank" href="'. $pinterest .'"><i class="fa fa-pinterest"></i></a>';
       $twitter_link = '<a class="user_social" style="background:#00aced" target="_blank" href="'. $twitter .'"><i class="fa fa-twitter"></i></a>';
       $linkedin_link = '<a class="user_social" style="background:#0077b5" target="_blank" href="'. $linkedin .'"><i class="fa fa-linkedin"></i></a>';
       $youtube_link = '<a class="user_social" style="background:#cb0303" target="_blank" href="'. $youtube .'"><i class="fa fa-youtube"></i></a>';
    
       $html ='';
       if ( !empty($facebook) )
       $html .= $facebook_link;
       if ( !empty($instagram) )
       $html .= $instagram_link;
       if ( !empty($pinterest) )
       $html .= $pinterest_link;
       if ( !empty($twitter) )
       $html .= $twitter_link;
       if ( !empty($linkedin) )
       $html .= $linkedin_link;
       if ( !empty($youtube) )
       $html .= $youtube_link;
    
       return $html;
    
    }


    ?>