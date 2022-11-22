<?php 
/*
 *
 * filter businesses on location page
 * 
 */ 
add_action( 'elementor/query/loc_biz_filter', function( $query ) {
    // Here we set the query to fetch posts with
    global $template_type;
    $id = get_the_id();
    $childs = array(
        'post_type' => 'wyz_location',
        'child_of' => $id,
    );
    
    $children =  get_pages($childs);
    if ( !empty ($children)) {
        foreach( $children as $child ) {

            $child_ids[]  = $child->ID;
    
        }

        array_push( $child_ids, $id );

        
    } else {
        $child_ids[] = $id;
    }
    $args = array(
        'post_type' => 'wyz_business',
        'meta_query' => array(
            array( 
            'key' => 'wyz_biz_location', 
            'value' => $child_ids,
            'compare' => 'IN',

            ),
        ),
    );
    $query->set('post_type', 'wyz_business');

    $query->set( 'meta_query', [ [
                    'key' => 'wyz_biz_location', 
                    'value' => $child_ids,
                    'compare' => 'IN',
                    ],
            ]);
    } );

    add_action( 'elementor/query/city_filter', function( $query ) {
        // Here we set the query to fetch posts with
        // ordered by comments count
        global $template_type;
    
        $query->set( 'post_parent', get_the_id() );
    } );
    
    
        add_action( "pp_query_loc_biz_filter", function( $query ) {
            // Here we set the query to fetch posts with
            global $template_type;
            $id = get_the_id();
            $childs = array(
                'post_type' => 'wyz_location',
                'child_of' => $id,
            );
            
            $children =  get_pages($childs);
            if ( !empty ($children)) {
                foreach( $children as $child ) {
        
                    $child_ids[]  = $child->ID;
            
                }
        
                array_push( $child_ids, $id );
        
                
            } else {
                $child_ids[] = $id;
            }
            $args = array(
                'post_type' => 'wyz_business',
                'meta_query' => array(
                    array( 
                    'key' => 'wyz_biz_location', 
                    'value' => $child_ids,
                    'compare' => 'IN',
        
                    ),
                ),
            );
    
            $query->set('post_type', 'wyz_business');
        
            $query->set( 'meta_query', [ [
                            'key' => 'wyz_biz_location', 
                            'value' => $child_ids,
                            'compare' => 'IN',
                            ],
                    ]);
            } );

            add_action( 'elementor/query/artist_tracks', function($query){
                global $post;
                $id = $post->ID;
              /*  $query->set( 'meta_query', [ 
                    [
                    'key' => 'link-artist', 
                    'value' => $id,
                    'compare' => 'IN',
                    ],
                ]);*/
                $query->set( 'author', $post->post_author );

            });
            add_action( 'elementor/query/album_tracks', function($query){
                global $post;
                $id = $post->ID;
                $query->set( 'meta_query', [ [
                    'key' => 'link_track_to_album', //this wont work because 
                    'value' => [$id],
                    'compare' => 'IN',
                    ],
            ]);
            });
            add_action( 'elementor/query/artist_albums', function($query){
                global $post;
                $id = $post->ID;
               /* $query->set( 'meta_query', [ [
                    'key' => 'link-artist-album', 
                    'value' => $id,
                    'compare' => 'IN',
                    ],
            ]);*/
            $query->set( 'author', $post->post_author );

            });
            add_action( 'elementor/query/countries', function( $query ) {
                $query->set('post_parent', 0);
            });
            add_action( 'elementor/query/usa', function( $query ) {
                $query->set('post_parent', 65254);
            });
            add_action( 'elementor/query/canada', function( $query ) {
                $query->set('post_parent', 63590);
            });
            /*add_action( 'elementor/query/top_cities', function( $query ) {
            //you have to mannually select ids
               $query->set('post_parent', [57009, 57094, 57125,56445]);
            });*/
            
            add_action( 'elementor/query/user_vidz', function( $query ) {
                
             //  global $post;
                $curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
                $author =  $curauth->ID;
              //  $author = $post->post_author;
                $query->set( 'author', $author );
              //  $query->set( 'post_type' , array('kvidz') );
            
            } );
            /*
            add_action( 'elementor/query/relate_biz', function( $query ) {
                Global $post;
                $bid = get_post_meta($post->ID, 'business_id', true);
            
                $query->set('post_id', $bid);
            
                  $query->set( 'post_type' , array('wyz_business') );
            
               } );*/
            add_action( 'elementor/query/user_posts', function( $query ) {
                global $post;
                $curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
                $author =  $curauth->ID;
                if (empty($author)){
                    $author = $post->post_author;  
                }
              //  $author = $post->post_author;
                $query->set( 'author', $author );
              //  $query->set( 'post_type' , array('kvidz', 'kpicz') );
            
            } );
            
            add_action( 'pp_query_user_posts', function( $query ) {
                global $post;
              //  $curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
                $author =  get_the_author_meta( 'ID' );
                if (empty($author)){
                    $author = $post->post_author;  
                }
              //  $author = $post->post_author;
                $query->set( 'author', $author );
              //  $query->set( 'post_type' , array('kvidz', 'kpicz') );
            
            } );
            
            //custom query for michigan cities
            add_action( 'elementor/query/michigan_filter', function( $query ) {
                // Here we set the query to fetch posts with
                // ordered by comments count
                $query->set( 'post_parent', 56445 );
                $query->set( 'meta_key', '_thumbnail_id' );
            
            } );
            
            add_action( 'elementor/query/location_filter', function( $query ) {
                // Here we set the query to fetch posts with
                // ordered by comments count
                $query->set( 'post_parent', [65254,63590,63574] );
                $query->set( 'meta_key', '_thumbnail_id' );
            
            } );
            
            add_action( 'pp_query_location_filter', function( $query ) {
                // Here we set the query to fetch posts with
                // ordered by comments count
                $query->set( 'post_parent', [65254,63590,63574] );
                $query->set( 'meta_key', '_thumbnail_id' );
            
            } );
            
            /*
            function michigan_filter_function($query_args){
                $query_args['post_parent'] = 56445;
              //  $query_args['meta_value'] = 56445;
                return $query_args;
            }
            add_filter('michigan_filter', 'michigan_filter_function');
            */

            //elementor custom registration form - adds custom meta data for referrer credit

            add_action( 'elementor_pro/forms/new_record',  'elementor_form_new_user_registration' , 10, 2 );

            function elementor_form_new_user_registration($record,$ajax_handler){
                $form_name = $record->get_form_settings('form_name');
                //Check that the form is the "create new user form" if not - stop and return;
                if ('Quick Registration' !== $form_name) {
                    return;
                }
                $form_data = $record->get_formatted_data();
                $username=$form_data['Email']; //Get tne value of the input with the label "User Name"
                $password = wp_generate_password( 10, false ); //Get tne value of the input with the label "Password"
                $email=$form_data['Email'];  //Get tne value of the input with the label "Email"
                $first_name=$form_data["First Name"]; //Get tne value of the input with the label "First Name"
                $last_name=$form_data["Last Name"]; //Get tne value of the input with the label "Last Name"
               // $user = wp_create_user($username,$password,$email); // Create a new user, on success return the user_id no failure return an error object
                $userdata = array(
                    'user_login'    => $username,
                    'user_pass'     => $password,
                    'user_email'    => $email,
                    'role'          => 'business_owner',
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                );
            
                $user_id = wp_insert_user( $userdata );
                if (is_user_logged_in() && is_affiliate()) {
                    $useron =  get_current_user_id();
                    update_user_meta( $user_id, 'referred_by', $useron );
                }
            
                /*if (is_wp_error($user_id)){ // if there was an error creating a new user
                    $ajax_handler->add_error_message("Failed to create new user: ".$user->get_error_message()); //add the message
                    $ajax_handler->is_success = false;
                    return;
                }*/
                /* Automatically log in the user and redirect the user to the home page */
                $creds= array( // credientials for newley created user
                    "user_login"=>$username,
                    "user_password"=>$password,
                    "remember"=>true
                );
                if ( ! is_user_logged_in() ) {
                $signon = wp_signon($creds); //sign in the new user
                }
                if (is_user_logged_in()) {
                    $ajax_handler->add_response_data( 'redirect_url', get_home_url('/user-account/?udpage=subscription') );
                } // optinal - if sign on succsfully - redierct the user to the home page
                wp_new_user_notification($user_id, $deprecated = null, $notify = 'both');
            
            }
?>