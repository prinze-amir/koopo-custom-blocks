<?php
use KoopoBlocks\Classes\Koopo;
class WyzBusinessPostOverride
{
	/**    functions overrides here     **/

	//example:
	/*public static function wyz_create_post( $value, $is_current_user_author = false, $is_wall = false ) {
		whatever is written here will override the function displaying the business posts in the wall and business wall
	}*/
	public static function wyz_get_business_header( $is_grid ){
		$id = get_the_ID();
		$sticky = is_sticky();
		ob_start();?>
		<div class="sin-busi-post<?php echo $is_grid ? ' bus-post-grid' : '';
									   echo $sticky ? ' bus-sticky' : '';?> sin-busi-item">
			<div class="head fix">
			<?php if ( $sticky ) {?>
					<div class="sticky-notice featured-banner"><span class="wyz-primary-color wyz-prim-color"><?php esc_html_e( 'FEATURED', 'koopo-online' );?></span></div>
			<?php }
			
			echo '<a href="' . get_the_permalink() . '" class="post-logo">' . WyzHelpers::get_post_thumbnail( get_the_ID(), 'business', 'medium' ) . '</a>';
			?>

				<h3><a href="<?php echo get_the_permalink();?>"><?php echo wp_trim_words(the_title(), 5, '...');?></a></h3>
				<?php // if ( ! $is_grid ) { ?>
				<!--div-- class="bus-term-tax"><?php echo get_the_term_list( get_the_ID(), 'wyz_business_category', '', ', ', '' );?>...</!--div-->
				<?php // }?>
			</div>
		<?php
		return ob_get_clean();
	}

	public static function wyz_get_business_content( $business_data, $is_grid ){

		ob_start();
		$id = get_the_ID();
		$status = WyzHelpers::open_close($id);
		$rep_img = WyzHelpers::get_image( $id, false );
		$excerpt_len = $is_grid ? 80 : 180;?>
		<div class="content">
			<?php 
				if ( $is_grid && '' != $business_data['banner_image'] ) { ?>
					<div class="single-grid-banner-container" >
					<a href="<?php echo get_the_permalink();?>">
					<img src="<?php echo $business_data['banner_image'] ?>" class="lazyload"></a>
					</div>
				<?php 	
					if ( '' != $business_data['description'] ) { ?>
					<p><?php echo WyzHelpers::substring_excerpt( $business_data['description'], $excerpt_len );//substr( $business_data['description'] , 0, $excerpt_len );?>...</p>
					<a class="read-more wyz-secondary-color-text" href="<?php echo esc_attr( get_permalink() );?>"><?php esc_html_e( 'more', 'koopo-online' )?></a>
				<?php } else { ?>
					<p>...</p>
					<a class="read-more wyz-secondary-color-text" href="<?php echo esc_attr( get_permalink() );?>"><?php esc_html_e( 'more', 'koopo-online' )?></a>
					<?php }
				} else { ?>
					<div class="single-grid-banner-container" >
					<?php
						if ( ! empty( $rep_img ) ) { echo ' <a href="' . get_the_permalink() . '"> <img src="'. $rep_img .'" class="busi-post-thumbnail lazyload" /></a>';
						} else {
							echo ' <a href="' . get_the_permalink() . '"> <img src="'.WyzHelpersOverride::get_default_image('business_banner').'" class="busi-post-thumbnail lazyload" /></a>';
						}
							?>
					</div>
				<?php 	
					if ( '' != $business_data['description'] ) { ?>
					<p><?php echo WyzHelpers::substring_excerpt( $business_data['description'], $excerpt_len );//substr( $business_data['description'] , 0, $excerpt_len );?>...</p>
					<a class="read-more wyz-secondary-color-text" href="<?php echo esc_attr( get_permalink() );?>"><?php esc_html_e( 'more', 'koopo-online' )?></a>
				<?php } else { ?>
					<p>...</p>
					<a class="read-more wyz-secondary-color-text" href="<?php echo esc_attr( get_permalink() );?>"><?php esc_html_e( 'more', 'koopo-online' )?></a>
					<?php }
				}

				global $post;
				if ( function_exists( 'dokan' )){
					$seller_id = $post->post_author;
					$has_products = WyzHelpers::business_has_products( $business_data['id'], $seller_id );
						if ( dokan_is_user_seller( $seller_id ) && $has_products ){
						
							echo '<a class="go-store-btn float-right" href="'. dokan_get_store_url( $seller_id ).'" target="_blank">Shop</a>';

						}
				}
				if (!empty($status['txt']) && $status['txt'] == 'Open Now'):
						echo '<p style="color:#2ab562;border:solid 1px #2ab562" class="open-close">'.$status['txt'].'</p>';
				elseif (!empty($status['txt']) && $status['txt'] == 'Closed Now'):
						echo '<p style="color:red;border:solid 1px red" class="open-close">'.$status['txt'].'</p>';
				endif;

			 if ( '' !== $business_data['category']['icon'] ) { ?>
				<a class="busi-post-label" style="background-color:<?php echo esc_attr( $business_data['category']['color'] );?>;" href="<?php echo esc_url( $business_data['category']['link'] );?>">
					<img src="<?php echo esc_url( $business_data['category']['icon'] );?>" alt="<?php echo esc_attr( $business_data['category']['name'] );?>" />
				</a>
			<?php }?>
			</div>
		<?php
		return ob_get_clean();
	}

	public static function wyz_get_business_footer( $business_data ){

			ob_start();?>
			<div class="footer fix">
			<?php if ( '' !== $business_data['country_name'] ) { ?>
				<a href="<?php echo esc_url( $business_data['country_link'] );?>" class="post-like link">
					<i class="fas fa-map-marker" aria-hidden="true"></i> <?php echo esc_html( $business_data['country_name'] ); ?>
				</a>
			<?php }
			/*
			if ( '' !== $business_data['website'] && WyzHelpers::wyz_sub_can_bus_owner_do(  get_the_author_meta( 'ID' ),'wyzi_sub_business_show_website_url') ) { ?>
				<div class="post-like">
					<a target="_blank" class="link" href="<?php echo esc_url( $business_data['website'] );?>"><i class="fa fa-globe" aria-hidden="true"></i> <?php echo esc_html( 'Check Out Website' );?></a>
				</div>
			<?php } 
			*/	?>
				
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
						WyzPostShare::the_favorite_button( $business_data['id'] ); 
						$id = $business_data['id'];
						$sticky = is_sticky();
					//	WyzHelpersOverride::show_paid_in_my_city($id);
					if(class_exists('KoopoBlocks\Classes\Koopo'))
					echo Koopo::show_top_picks_bagde($id);
				
				?>
				<style>.fav-bus span{display:none}</style>

			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function wyz_create_business( $isArchive = true ) {

		$is_grid = false;
	//	if ( $isArchive )
		$is_grid =  'off' === get_option( 'wyz_archives_grid_view','off' ) ? false : true;

		$business_data = WyzBusinessPost::wyz_get_business_data( get_the_ID() );
	//	return	do_shortcode('[elementor-template id="88501"]');
		return WyzBusinessPost::wyz_get_business_header( $is_grid ) . WyzBusinessPost::wyz_get_business_content( $business_data, $is_grid ) . WyzBusinessPost::wyz_get_business_footer( $business_data );
	}

}
