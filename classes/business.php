<?php 
namespace KoopoBlocks\Classes;

class KoopoBusiness{

    public static function new_business_activity($id, $author){
        
        $post = get_post($id);
		$product = wc_get_product( $id );

		if ($post->post_status != 'publish') {
			return;
		}

        $bus_id = get_post_meta( $id, 'business_id', true );
        if ( empty( $bus_id ) ){
            $bus_id = get_post_meta($id, '_wyz_job_listing', true);
            if(empty($bus_id) && $post->post_type != 'wyz_business' ){
                return;
            } elseif($post->post_type == 'wyz_business'){
				$bus_id = $id;
			}
        }
        
        $ttl = get_the_title( $id );
		$exrpt = wp_trim_words(get_the_content($id), 30, '...');
		$image = get_post_meta( $id, 'thumbnail_id', true );
		$image_id = get_post_thumbnail_id($id, 'thumbnail');


		if ($product){
			$price = $product->get_price();
			$exrpt = wp_trim_words( $product->get_short_description(), 30, '...');
		}
		
		if ( '' != $image ) {
		    if (filter_var($image, FILTER_VALIDATE_URL)){
		        $image = '<img style="width:100%;height:auto;" src="'.$image.'"/>';
		    }
            else{
    			$image = wp_get_attachment_url( $image );
    			$image = '<img style="width:100%;height:auto;" src="'.$image.'"/>';
            }
		}
		if ( ! $image || empty( $image ) ){

			$image = '<img src="'.wp_get_attachment_url($image_id).'"/>';
        }

		$type = $post->post_type;
		if ($type == 'job_listing'){
			$type = 'New Job Opportunity...';
		}
		if ($type == 'wyz_business'){
			$type = 'Welcome '.$ttl.' to The Community';
		}

		ob_start();?>

		<div id="post-<?php echo $id; ?>" class="activity">
			<?php if ($type == 'product'){
				echo '<h2>Just in...</h2>';
			} else {
			echo '<h2>'. $type .'</h2>';
			}?>
			<div class="image col-xs-12"><a href="<?php echo wp_get_attachment_url($image_id); ?>"><?php echo $image;?></a></div>
			<div class="content col-xs-12">
				<div class="head fix">
					<div class="text">
						<h2><?php echo esc_html( $ttl ); ?></h2>
                        <?php if ( !empty($product) ): ?>
						<h2>$<?php echo $price ?></h2>
						<?php endif; ?>	
					</div>
				</div>
				<p><?php echo $exrpt; ?></p>
				<a href="<?php echo esc_url( get_post_permalink( $id ) ); ?>" class="view-offer wyz-button wyz-secondary-color icon"><?php echo esc_html__( 'View', 'koopo-blocks' );?> <i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<?php

		$content = ob_get_clean();

		$post_status = get_post_status( $bus_id );
		$post_data = array();
		if ( 'publish' != $post_status )
			$post_status = 'pending';

		$post_meta_data = array(
			'wyz_business_post_likes'=> array(),
			'wyz_business_post_likes_count' => 0,
			'business_id' => $bus_id,
			'post_id' => $id,
		);

		$post_data['post_content'] = $content;
		$post_img = '';
		$post_data['post_title'] = esc_html__('New', 'koopo-blocks') . $type . 'from' . get_the_title( $bus_id ) . date_i18n(get_option('date_format'), current_time('timestamp')) .' @ '. date_i18n(get_option('time_format'), current_time('timestamp'));
		$post_data['post_status'] = $post_status;
		$post_data['post_type'] = 'wyz_business_post';
		$bus_comm_stat = get_post_meta( $bus_id, 'wyz_business_comments', true );
		$post_data['comment_status'] = ( 'off' != $bus_comm_stat ? 'open' : 'closed' );

		$new_post_id = wp_insert_post( $post_data, true );

		foreach ( $post_meta_data as $key => $value ) {
			update_post_meta( $new_post_id, $key, $value );
		}

		$business_posts = get_post_meta( $bus_id, 'wyz_business_posts', true );
		if ( '' === $business_posts || ! $business_posts ) {
			$business_posts = array();
		}
		array_push( $business_posts, $new_post_id );
		update_post_meta( $bus_id, 'wyz_business_posts', $business_posts );
    }

    public static function add_new_event_activity( $id, $author_id ) {
		
		$post = get_post( $id );

		if ( $post->post_status != 'publish' ) {

			return;
        }

        $bus_id = get_post_meta( $id, 'evoau_bus_value', true );

        if ( empty( $bus_id ) ){

            return;
        }
		
		ob_start();
		?>
		<div id="post-<?php echo $id; ?>" class="kpost-item">
		<h2>New Event Posted</h2>
		<?php echo do_shortcode('[add_single_eventon show_excerpt="yes" id="'.$id.'" ev_uxval="3" etc_override="no" show_et_ft_img="yes"]');?>
		<a href="<?php echo esc_url( get_post_permalink( $id ) )?>" target="_blank"><h3>Check it out!</h3></a>
		</div>
		<?php

		$post_content = ob_get_clean();

		$post_status = get_post_status( $id );
		$post_data = array();
		if ( 'publish' != $post_status ) 
			$post_status = 'pending';

		$post_data['post_content'] = $post_content;
		$post_img = '';
		$post_data['post_title'] = esc_html__('New from ', 'koopo-blocks') . get_the_author_meta( 'display_name', $author_id ) .'-'. get_the_title( $id ) .  date_i18n(get_option('date_format'), current_time('timestamp')) .' @ '. date_i18n(get_option('time_format'), current_time('timestamp'));
		$post_data['post_status'] = $post_status;
		$post_data['post_author'] = $author_id;
		$post_data['post_type'] = 'wyz_business_post';
		$post_meta_data['update-type'] = $post->post_type;

		$new_post_id = wp_insert_post( $post_data, true );

		foreach ( $post_meta_data as $key => $value ) {
			update_post_meta( $new_post_id, $key, $value );
        }
        $business_posts = get_post_meta( $bus_id, 'wyz_business_posts', true );
		if ( '' === $business_posts || ! $business_posts ) {
			$business_posts = array();
		}
		array_push( $business_posts, $new_post_id );
		update_post_meta( $bus_id, 'wyz_business_posts', $business_posts );
	}

	public static function new_biz_photo($id, $author_id){

		$post = get_post( $id );

        $bus_id = get_post_meta( $id, 'business_id', true );

        if ( empty( $bus_id ) ){

            return;
        }
		
		ob_start();
		?>
		<div id="post-<?php echo $id; ?>" class="kpost-item">
			<a class="sin-photo" href="<?php echo wp_get_attachment_url($id); ?>"><?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?></a>
		
		</div>

		<?php

		$post_content = ob_get_clean();

		$post_status = get_post_status( $id );
		$post_data = array();
		if ( 'publish' != $post_status ) 
			$post_status = 'pending';

		$post_data['post_content'] = $post_content;
		$post_img = '';
		$post_data['post_title'] = esc_html__('New from ', 'koopo-blocks') . get_the_author_meta( 'display_name', $author_id ) .'-'. get_the_title( $id ) .  date_i18n(get_option('date_format'), current_time('timestamp')) .' @ '. date_i18n(get_option('time_format'), current_time('timestamp'));
		$post_data['post_status'] = $post_status;
		$post_data['post_author'] = $author_id;
		$post_data['post_type'] = 'wyz_business_post';
		$post_meta_data['update-type'] = $post->post_type;

		$new_post_id = wp_insert_post( $post_data, true );

		foreach ( $post_meta_data as $key => $value ) {
			update_post_meta( $new_post_id, $key, $value );
        }
        $business_posts = get_post_meta( $bus_id, 'wyz_business_posts', true );
		if ( '' === $business_posts || ! $business_posts ) {
			$business_posts = array();
		}
		array_push( $business_posts, $new_post_id );
		update_post_meta( $bus_id, 'wyz_business_posts', $business_posts );
	}
    
}
