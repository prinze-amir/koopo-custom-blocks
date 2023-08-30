<?php
use KoopoBlocks\Classes\Koopo;

class WyzHelpersOverride
{

	public static function the_business_sidebar( $id ) {
		
		global $current_user;
		global $template_type;

		wp_get_current_user();

		$about = strip_shortcodes( self::new_get_about( $id ) );

		/* Opening/Closing times. */
		$days = WyzHelpers::get_days( $id );
		$days_names = $days[0];
		$days_arr = $days[1];

		$author_id = WyzHelpers::wyz_the_business_author_id();
		//$the_badge = Koopo::show_top_picks_bagde($id);

		$address = self::get_address( $id );
		$extra_address = self::get_extra_address($id);
		$phone = self::get_phone( $id, $author_id );
		$email = WyzHelpers::get_email( $id, $author_id );
		$phone2 = self::get_phone2( $id, $author_id );
		$website = get_post_meta( $id, 'wyz_business_website', true );

		$no_days_data = true;

		for ( $i=0; $i<7; $i++)
			if ( ! empty( $days_arr[ $i ] ) ){
				$no_days_data = false;
				break;
			}


		if ( 'off' == get_option( 'wyz_switch_sidebars_single_bus','off' ) && 'on' !== get_option('wyz_hide_extra_sidebar_single_bus') ) 
			$coulmns_class_name =  'on' === wyz_get_option( 'resp' ) ? 'col-md-3 col-xs-12' : 'col-xs-3';

		elseif( 'off' != get_option( 'wyz_switch_sidebars_single_bus','off' ) && 'on' !== get_option('wyz_hide_extra_sidebar_single_bus'))
			$coulmns_class_name =  'on' === wyz_get_option( 'resp' ) ? 'col-md-3 col-xs-12' : 'col-xs-3';

		elseif ('off' == get_option( 'wyz_switch_sidebars_single_bus','off' ) && 'on' == get_option('wyz_hide_extra_sidebar_single_bus'))
			$coulmns_class_name =  'on' === wyz_get_option( 'resp' ) ? 'col-md-4 col-xs-12' : 'col-xs-4';

		else
			$coulmns_class_name =  'on' === wyz_get_option( 'resp' ) ? 'col-md-4 col-xs-12' : 'col-xs-4';

		if ( $template_type == 2 ) {
			WyzHelpers::the_business_sidebar_2( $id, $days_names, $days_arr, $author_id, $about, $address, $phone, $email, $website, $no_days_data );
		} else {
			self::the_business_sidebar_1( $id, $days, $days_names, $days_arr, $author_id, $about, $address, $extra_address, $phone, $phone2, $email, $website, $no_days_data,$coulmns_class_name );
		}
	}

	public static function the_business_sidebar_1 ( $id, $days, $days_names, $days_arr, $author_id, $about, $address, $extra_address, $phone, $phone2, $email, $website, $no_days_data, $coulmns_class_name ) {
		
		ob_start();
		?>
		<!-- Business Sidebar -->
				
		<div id="sideb" class="business-sidebar <?php echo $coulmns_class_name;?>">
		<div class="flex top-picks">
			<?php 			if(class_exists('KoopoBlocks\Classes\Koopo'))
							echo Koopo::show_top_picks_bagde($id);?>
		</div>
		<?php
		if ( is_sticky() ) {
			echo '<div class="sticky-notice"><span class="wyz-primary-color">' . esc_html__( 'featured', 'koopo-online' ) . '</span></div>';
		}
		$sidebar_order_data = get_option( 'wyz_business_sidebar_order_data' ); 
		
		foreach ( $sidebar_order_data as $key => $tab ) { 
			
			switch ( $tab['type']  ) {

				case 'About':
					// About
					if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_description') ) {
					?>
						<!-- About Business Sidebar -->
						<div class="sin-busi-sidebar widget<?php echo (!empty($tab['cssClass']) ) ? " " . $tab['cssClass'] : '';?>">
							<?php if (!empty($tab['title'])) { ?>
							<h4 class="sidebar-title"><?php echo esc_html( $tab['title']  );?></h4>
							<?php } ?>
							<div class="about-business-sidebar fix">
								<div style="position:relative" class="desc-see-more"><p><?php echo $about;?> </p><?php /* echo $thebadge */?></div>
							</div>
						</div>
						<?php }
				break;

				case 'Opening_Hours':
					//Opening Hours
					if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_opening_hours') ) {
						if ( ! $no_days_data ) { ?>
						<!-- Opening Hours Business Sidebar -->
						<div class="sin-busi-sidebar<?php echo (!empty($tab['cssClass']) ) ? " " . $tab['cssClass'] : '';?>">
							<?php if (!empty($tab['title'])) { ?>
							<h4 class="sidebar-title"><?php echo esc_html( $tab['title']  );?></h4>
							<?php } ?>
							<div class="opening-hours-sidebar fix">
							<?php
							for( $i=0; $i<7; $i++)
								WyzHelpers::wyz_display_time( $days_arr[ $i ], $days_names[ $i ] );
							?>
							</div>
						</div>
						<?php } } 
						do_action( 'wyz_sidebar_after_days', $id, $author_id );
				break;

				case 'Contact_Info':

					self::get_contact_info_widget_template1( $id, $author_id, $phone, $phone2, $address, $extra_address, $website, $email, $tab['title'],$tab['cssClass'] );
					
				break;

				case 'Map':
					// Map
					 if ( 'image' == get_option( 'wyz_business_header_content' ) &&  WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_map') ) { ?>
					 	<?php if (!empty($tab['title'])) { ?>
							<h4 class="sidebar-title"><?php echo esc_html( $tab['title']  );?></h4>
							<?php } ?>
						<div class="sin-busi-sidebar<?php echo (!empty($tab['cssClass']) ) ? " " . $tab['cssClass'] : '';?>">
							<?php WyzMap::wyz_the_business_map( $id, true ); ?>
						</div>
					<?php }
				break;

				case 'Social_Media':
					// Social Media
					if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_social_media') ) {
						WyzHelpers::social_links( $id, $tab['title'], $tab['cssClass'] );
					}
				break;
				
				case 'Tags':
				// Tags
				?>
					<div id="sticky-sidebar">
					<?php 
					if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_business_tags') ) {
						if ( $tags = get_the_term_list( $id, 'wyz_business_tag', '', ', ' ) ) {?>
							<div class="sin-busi-sidebar<?php echo (!empty($tab['cssClass']) ) ? " " . $tab['cssClass'] : '';?>">
								<?php if (!empty($tab['title'])) { ?>
								<h4 class="sidebar-title"><?php echo esc_html( $tab['title']  );?></h4>
								<?php } ?>
								<div class="tags-sidebar"><i class="fas fa-tag"></i>
									<?php echo $tags;?>
								</div>
							</div>
					<?php }
					}
					?>
					</div>
					<?php 
				break;

				case 'Claim':
					//claim
					if ( 'off' != get_option( 'wyz_business_claiming' ) && 'yes' != get_post_meta( $id, 'wyz_business_claimed', true ) ) { ?>
						<?php if (!empty($tab['title'])) { ?>
						<h4 class="sidebar-title"><?php echo esc_html( $tab['title']  );?></h4>
						<?php } ?>
						<div class="single-claim-container sin-busi-sidebar widget<?php echo (!empty($tab['cssClass']) ) ? " " . $tab['cssClass'] : '';?>"> <?php
						echo '<a href="' . home_url( '/claim/?id=' ) . $id .'" class="light-blue-link wyz-primary-color-text">' . esc_html__( 'Claim this Business', 'koopo-online' ) . '</a>'; ?>
						</div> <?php
					}
				break;

				case 'Recent_Ratings':
					$all_business_rates = get_post_meta( $id, 'wyz_business_ratings', true );
					if ( empty($all_business_rates))$all_business_rates = array(-1);
					$args = array(
						'post_type' => 'wyz_business_rating',
						'post__in' => $all_business_rates,
						'posts_per_page' => 3,
						//'paged' => $page,
					);
					$rate_query = new WP_Query( $args );

					$first_id = - 1;
					if ( $rate_query->have_posts() ) {?>
					<!-- Sidebar Widget -->
					<div class="sin-busi-sidebar<?php echo (!empty($tab['cssClass']) ) ? " " . $tab['cssClass'] : '';?>">
						<!--Widget Title-->
						<?php if (!empty($tab['title'])) { ?>
						<h4 class="sidebar-title"><?php echo esc_html( $tab['title']  );?></h4>
						<?php } ?>
						<!-- Rating Widget -->
						<div class="recent-rating-widget">

						<?php while ( $rate_query->have_posts() ) {
							$rate_query->the_post();
							$rate_id = get_the_ID();
							$first_id = $rate_id;
							echo WyzBusinessRating::wyz_create_rating( $rate_id, 2 );
						}
						wp_reset_postdata(); ?>
						</div>
					</div>


						<?php
					}
				break;

				case 'All_Ratings':

					$all_business_rates = get_post_meta( $id, 'wyz_business_ratings', true );
					if ( empty($all_business_rates))$all_business_rates = array(-1);
					$args = array(
						'post_type' => 'wyz_business_rating',
						'post__in' => $all_business_rates,
						'posts_per_page' => 3,
						//'paged' => $page,
					);
					$rate_query = new WP_Query( $args );

					$first_id = - 1;
					if ( $rate_query->have_posts() ) {?>
						<div class="sin-busi-sidebar<?php echo (!empty($tab['cssClass']) ) ? " " . $tab['cssClass'] : '';?>">
						<!--Widget Title-->
						<?php $rate_stats = WyzBusinessRating::get_business_rates_stats( $id );?>
						<?php if (!empty($tab['title'])) { ?>
						<h4 class="sidebar-title" style="float: left;"><?php echo esc_html( $tab['title']  );?></h4>
						<?php } ?>
						<!-- Rating Widget -->
						<div class="rating-widget all-ratings-widget">
							<div class="single-rating fix">
								<div class="head fix">
									<?php echo WyzBusinessRating::get_business_rates_stars( $id, $display_count = true, $rate_stats );?>
								</div>
							</div>

							<?php echo WyzBusinessRating::get_business_rates_cats_perc( $id, $all_business_rates, $rate_stats['rate_nb'] );?>

						</div>
					</div>
				<?php }

				break;

				default:
					 // nothing as defalult
				break;
			}
		}
			//End of Business Sidebar
			do_action( 'wyz_after_the_business_sidebar', $id, $author_id );

			?>
		</div>

		<?php 
		echo ob_get_clean();
	}

	public static function add_product_link( $page ) {
		
		switch ( $page ) {
			case 'wall':
				echo '<a class="wyz-edit-btn btn-blue float-right clear btn-blue wyz-primary-color wyz-prim-color btn-square" style="color:#fff;text-decoration:none;margin-bottom:10px" href="';
					echo esc_url( home_url( '/dashboard/new-product/' ) );
				echo '">'.esc_html__( 'Add New', 'koopo-online' ) . '</a>';
				break;
		}
	}


	/**
	 * Get the business social links.
	 *
	 * @param integer $business_id business id.
	 */
	public static function wyz_get_social_links( $business_id ) {

		if ('on' == get_option( 'wyz_hide_header_social_share', 'off' ))
			return;
		
		$fbid = function_exists( 'wyz_get_option' ) ? wyz_get_option( 'businesses_fb_app_ID' ) : '';

		//WyzPostShare::the_js_scripts();

		ob_start();?>	
		<div class="business-social">
			<?php if ( true ) {?>

			<div class="social social-facebook">
				<div class="front wyz-primary-color wyz-prim-color">
					<i class="fab fa-facebook"></i>
				</div>
				<div class="back wyz-primary-color wyz-prim-color">
					<div class="fb-share-button" data-mobile-iframe="true" data-href="<?php echo get_permalink(); ?>"  data-layout="button_count" data-size="small" data-show-faces="false" data-share="false"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore"><?php esc_html__( 'Share', 'koopo-online');?></a></div>

					
					<div id="fb-root"></div>
					<script>
					//<![CDATA[
					(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=<?php echo esc_js( $fbid );?>";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
					//]]>
					</script>
				</div>
			</div>
			<?php }?>

			<!--div-- class="social social-twitter">
				<div class="front wyz-primary-color wyz-prim-color">
					<i class="fa fa-twitter"></i>
				</div>
				<div class="back wyz-primary-color wyz-prim-color">
					<iframe allowtransparency="true" scrolling="no" src="//platform.twitter.com/widgets/tweet_button.html" style="width:60px; height:20px;"></iframe>
				</div>
			</!--div-->

			<div class="social social-whatsapp">
				<div class="front wyz-primary-color wyz-prim-color">
					<i class="fa fa-whatsapp"></i>
				</div>
				<div class="back wyz-primary-color wyz-prim-color">
					<a target="_blank" class="wtsappsharebtn" href="https://wa.me/?text=<?php urlencode(the_permalink()) ?>"><i class="fa fa-whatsapp"></i> WhatsApp</a>
				</div>
			</div>

			<div class="social social-linkedin">
				<div class="front wyz-primary-color wyz-prim-color">
					<i class="fa fa-linkedin"></i>
				</div>
				<div class="back wyz-primary-color wyz-prim-color">
					<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
					<script type="IN/Share" data-url="<?php the_permalink(); ?>" data-counter="right"></script>
				</div>
			</div>

		</div>
		<?php return ob_get_clean();
	}

	public static function wyz_the_business_subheader( $id ) {
		
		ob_start();
		$prefix = 'wyz_';
		$name = get_the_title( $id );
		/*if ( has_post_thumbnail( $id ) ) {
			$logo = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
		} else {
			$logo = WyzHelpers::get_default_image( 'business' );
		}*/
		WyzHelpers::get_post_thumbnail_url( $id, 'business' );
		$description = get_post_meta( $id, $prefix . 'business_excerpt', true );
		$slogan = get_post_meta( $id, $prefix . 'business_slogan', true );
		$cntr = get_post_meta( $id, 'wyz_biz_location', true );
		$followers = WyzHelpers::get_business_favorite_count( $id );
		$cntr_link = '';
		if ( '' != $cntr && ! empty( $cntr ) ) {
		//	$cntr_link = get_post_type_archive_link( 'wyz_business' ) . '?location=' . $cntr;
			$cntr_link = get_post_permalink($cntr);
			$cntr = get_the_title( $cntr );
		}
		?>
		<div class="business-data-area">
			<div class="container">
				<div class="row">
					<?php 
					if (is_user_logged_in()){
					WyzPostShare::the_favorite_button( $id );
					}
					?>
					<div class="business-data-wrapper col-xs-12">
						<?php
						if ( WyzHelpers::wyz_sub_can_bus_owner_do(WyzHelpers::wyz_the_business_author_id(),'wyzi_sub_show_business_logo') ) {
							if ( is_singular( 'wyz_offers' ) ) {
								echo '<a href="' . get_the_permalink($id) . '">' . WyzHelpers::get_post_thumbnail( $id, 'business', 'medium', array( 'class' => 'logo float-left' ) ) . '</a>';
							} else {
								echo '<a href="' . get_the_permalink($id) . '">' . WyzHelpers::get_post_thumbnail( get_the_ID(), 'business', 'medium', array( 'class' => 'logo float-left' ) ) . '</a>';
							}
						} 
						?>
						<div class="content fix">
							<h1><?php echo esc_html( $name );
								if ( '' != $slogan ) {
									echo ' - ' . $slogan;
								}?></h1>
								<?php if ( $followers > 99 ){ ?>
				<h4 style="display:inline;margin-left:10px">Followers: <?php echo $followers; ?></h4>
								<?php } ?>
							<?php echo WyzHelpers::verified_icon( $id );?>
							<div class="bus-term-tax clear"><?php echo get_the_term_list( $id, 'wyz_business_category', '', ', ', '' );?></div>
							<h2><?php echo wp_trim_words(esc_html( $description ), 25, '...' );?></h2>
							<?php if ( '' !== $cntr ) { ?>
								<a href="<?php echo esc_url( $cntr_link );?>" class="post-like link">
									<i class="fas fa-map-marker" aria-hidden="true"></i> <?php echo esc_html( $cntr ); ?>
								</a>
							<?php } ?>
						</div>
						<?php 
						if ( WyzHelpers::wyz_sub_can_bus_owner_do(WyzHelpers::wyz_the_business_author_id(),'wyzi_sub_business_show_social_shares') ) {
							// echo WyzHelpers::wyz_get_social_links( $id );
						//	echo do_shortcode('[elementor-template id="87788"]'); 
						}?>
					</div>
				</div>
			</div>
			<div id="notification-anchor"></div>
			<a id="show_more_wi" style="margin-left:10px;font-size:36px;color:#ffcc01" href="#sideb" class="show_more_wi"><i class="fas fa-info-circle"></i></a>
			<a id="hide_more_wi" style="margin-left:10px;font-size:36px;color:#000" class="hide_more_wi"><i class="fas fa-times-circle"></i></a>
		</div>

		<?php echo ob_get_clean();
	}

	public static function get_coordinates( $id ) {
		$mapGPS = get_post_meta( $id, 'wyz_biz_maps', true );
			if (!$mapGPS) {
				return;
			}
			$lat = $mapGPS['latitude'];
			$lon = $mapGPS['longitude'];
			
			return $lat . ',' . $lon;
	}
	
	public static function get_map_url( $id ) {
		$base = 'http://maps.google.com/maps/place';
		$args = array(
			'daddr' => urlencode( self::get_coordinates( $id ) ),
		);

		return esc_url( add_query_arg( $args, $base ) );
	}		
	
	public static function wyz_biz_map_directions( $id ) {
			$gps = self::get_coordinates( $id );
			
			if (! $gps ) {
				return;
			}
			
			$output = self::get_map_url( $id );
			
			return $output;
			
		}

		public static function get_contact_info_widget_template1( $id, $author_id, $phone, $phone2, $address, $extra_address, $website, $email, $title, $tabCustomClass ) {
			
					$result = '';
					if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_contact_information_tab') &&
								( '' != $phone || '' != $address || '<a href="mailto:" target="_blank"></a>' != $email || '' != $website) ) {
							$result .= '<!-- Contact Business Sidebar -->'	;
							$result .= '<div class="sin-busi-sidebar widget';
							if (!empty($tabCustomClass) )
							$result .=  " " . $tabCustomClass;
							$result .= '">';
							if (!empty($title)){
								$result .= '<h4 class="sidebar-title">';
								$result .= 	esc_html( $title ). '</h4>';
							}
							$result .= '<div class="contact-info-sidebar fix">';
							$result .= '<table class="contact-info-table"><tbody>';		
									
							
							if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_phone_1') ) { 
									if ( '' != $phone ) {
								$result .= '<tr><td class="contact-info-icon"><p><i class="fas fa-phone-alt"></i></p></td><td><p class="phone">'. $phone . '</p></td></tr>';
									} 
									if ( '' != $phone2 ) {
										$result .= '<tr><td class="contact-info-icon"><p><i class="fas fa-mobile-alt"></i></p></td><td><p class="phone">' . $phone2 . '</p></td></tr>';
									}
							}
							if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_address') ) {
										if ( '' != $address ) {
											$result .= '<tr><td class="contact-info-icon"><a target="_blank" href="'. self::wyz_biz_map_directions($id) .'"><p><i class="fas fa-map-marker-alt"></i></p></a></td><td><a target="_blank" href="' . self::wyz_biz_map_directions($id) .'"><p>' . $address;
										}
											$result .= '</p></a></td></tr>'; 
										if ( '' != $extra_address ) {	
											$result .= '<tr><td class="contact-info-icon"><a target="_blank" href="https://www.google.com/maps/place/' . esc_html( $extra_address ). '"><p><i class="fas fa-map-marker-alt"></i></p></a></td><td><a target="_blank" href="https://www.google.com/maps/place/' . esc_html( $extra_address ) .'"><p>' . esc_html( $extra_address );
										} 
										$result .= '</p></a></td></tr>';
							} 
							if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_email_1') ) {
										if ( '' != $email ) {
							
										$result .= '<tr><td class="contact-info-icon"><p><i class="fas fa-envelope"></i></p></td><td><p>' . $email . '</p></td></tr>';
									}} 
							
							if ( WyzHelpers::wyz_sub_can_bus_owner_do( $author_id,'wyzi_sub_business_show_website_url') ) {
										if ( '' !== $website ) {
										$result .= '<tr><td class="contact-info-icon"><p><i class="fas fa-globe"></i></p></td><td><p><a class="run_block" target="_blank" href="' . esc_url( $website ) . '">' . esc_html( 'Website' ) . '</a></p></td></tr>';
										
										}
							} 
										
									$result .=  '</tbody></table></div></div>';
								
							}
		
		
				echo $result;
		
		
			}

		public static function get_extra_address( $id ) {
			
			$prefix = 'wyz_';

			$additional_address = get_post_meta( $id, $prefix . 'business_addition_address_line', true );
					$extra_address = '';

			if ( '' !== $additional_address ) {
			$extra_address .= $additional_address;
			}
			return $extra_address;
		}

	//add function for 2nd phone
	public static function get_phone2( $id, $author_id ) {
		
		$phone2 = esc_html( get_post_meta( $id, 'wyz_business_phone2', true ) );
		
		if ( ! empty( $phone2 ) ) {
			$phone2 = '<a href="tel:' . $phone2 . '">' . $phone2 . '</a> ';
		}

		$final_phone2 = $phone2;

		return $final_phone2;
	}

	public static function get_phone( $id, $author_id ) {
		
		$phone1 = esc_html( get_post_meta( $id, 'wyz_business_phone1', true ) );
		
		if ( ! empty( $phone1 ) ) {
			$phone1 = '<a href="tel:' . $phone1 . '">' . $phone1 . '</a> ';
		}

		$final_phone = $phone1;

		return $final_phone;
	}

	public static function get_address( $id ) {
		
		$prefix = 'wyz_';

		$bldg = get_post_meta( $id, $prefix . 'business_bldg', true );
		$street = get_post_meta( $id, $prefix . 'business_street', true );
		$zipcode = get_post_meta( $id, $prefix . 'business_zipcode', true );
		$city = get_post_meta( $id, $prefix . 'business_city', true );
		$state = get_post_meta( $id, $prefix . 'biz_state', true );
		$country = get_post_meta( $id, $prefix . 'biz_country', true );
		if ( '' != $country )
			$country = get_the_title( $country );
		else $country = '';
		//$additional_address = get_post_meta( $id, $prefix . 'business_addition_address_line', true );
		$address = '';
		if ( '' !== $bldg ) {
			$address .= $bldg . '<br>';
		}
		if ( '' !== $street ) {
			$address .=  $street . '<br>';
		}
		
		if ( '' !== $city ) {
			$address .= $city . ', ';
		}
		if ( '' !== $state ) {
			$address .= $state . ', ';
		}
		if ( '' !== $zipcode ) {
			$address .=  $zipcode . ', ';
		}
		//country is actually city and city is actually state
		if ( '' !== $country ) {
			$address .= $country . ', ';
		}
		/*if ( '' !== $additional_address ) {
			$address .= $additional_address . ', ';
		}*/
		if ( '' != $address ) {
			$address = substr( $address, 0, strlen( $address ) - 2 );
		}
		return $address;
	}

	private static function new_get_about( $id ) {
		$logged_in_user = is_user_logged_in();
		$about = get_post_meta( $id, 'wyz_business_description', true );
		$about = preg_replace("/<img[^>]+\>/i", " ", $about);
		$about = preg_replace("/<div[^>]+>/", "", $about);
		$about = preg_replace("/<\/div[^>]+>/", "", $about);
		$about = WyzHelpers::wyz_strip_tags( $about, '<table><td><tr><th>' );
		if ( is_singular( 'wyz_offers' ) ) 
			$about_link = get_permalink( $id ) . '#about';
		else
			$about_link = '#about';
		$about = WyzHelpers::substring_excerpt($about, 150 ) . '<a href="' . $about_link . '" class="read-more wyz-secondary-color-text wyz-prim-color-txt">' . esc_html__( 'show more', 'koopo-online' ) . '</a>';
		$about = wyz_split_glue_link( array( $about, false ) );
		return $about[0][0];
	}

	public static function get_default_image( $image ) {
		
		$def = '';
		$img = '';
		if ( 'business' == $image ) {
			$def = WYZI_PLUGIN_URL . 'businesses-and-offers/businesses/images/default-business.png';
			if ( function_exists( 'wyz_get_option') ) {
				$img = wyz_get_option( 'default-business-logo' );
			}
		} elseif ( 'business_banner' == $image ) {
			$def = WYZI_PLUGIN_URL . 'locations/images/location-placeholder.jpg';
			if ( function_exists( 'wyz_get_option') ) {
				$img = wyz_get_option( 'default-business-banner' );
			}
		} elseif ( 'offer' == $image ) {
			$def = WYZI_PLUGIN_URL . 'businesses-and-offers/offers/images/offers-placeholder.jpg';
			if ( function_exists( 'wyz_get_option') ) {
				$img = wyz_get_option( 'default-offer-logo' );
			}
		} elseif ( 'location' == $image ) {
			$def = WYZI_PLUGIN_URL . 'locations/images/location-placeholder.jpg';
			if ( function_exists( 'wyz_get_option') ) {
				$img = wyz_get_option( 'default-location-logo' );
			}
		}
		return ! empty( $img ) ? $img : $def;
	}

	public static function open_close ($b_id) {
	    
	    $retured_value = '';
	    $days_ids = array( 'open_close_sunday','open_close_monday', 'open_close_tuesday', 'open_close_wednesday',
						'open_close_thursday', 'open_close_friday', 'open_close_saturday' );//moved sunday to the front to represent 0
	    for( $i=0; $i<7; $i++)
				$days_arr[] =  get_post_meta( $b_id, 'wyz_' . $days_ids[ $i ], true ) ;
		
		$are_all_days_empty = true;
		// lets check if all fields are empty first so we return nothing
		for( $i=0; $i<7; $i++) {
			if ( empty( $days_arr[$i] ) ) continue;
			foreach ( $days_arr[$i] as $key => $value ) { 
			    if ( !empty($value['open']) || !empty($value['close']) ) {
			        $are_all_days_empty = false; break;
			    }
			}
		}

		for( $a=0; $a<7; $a++)
			$all_days_arr = get_post_meta( $b_id, 'wyz_' . $days_ids[ $a ] . '_status', true );
			if ( !empty($all_days_arr) ){
				$are_all_days_empty = false; 
			}
			
		if ($are_all_days_empty) 
		    return array(
				'txt' 	=> '',
				'bool' 	=> '',
		    ); 
		
		$current_day_of_the_week = date( "w");
		$current_day_converstion = $current_day_of_the_week -1;
		$timezone = get_option('gmt_offset');
		$time = gmdate("H:i", time() + 3600*($timezone+date("I")));

		if ($current_day_of_the_week == 0 ) 
			$current_day_converstion = 6;
		$current_business_open = false;
		$current_business_status_txt = "Closed Now";

	//	print_r($days_arr);
	//	print_r($days_arr[$current_day_converstion]);
	//	print_r($current_day_converstion);
	//	print_r($current_day_of_the_week);
	
		if ( is_array( $days_arr[$current_day_converstion] ) ) {

			foreach ( $days_arr[$current_day_converstion] as $key => $value ) {
			    if ( !empty($value['open']) || !empty($value['close']) ) {
			        if ( !empty($value['open']) && !empty($value['close']) ) {  
			            if (strtotime($time) >  strtotime($value['open']) &&  strtotime($time) < strtotime($value['close'])) {
			                $current_business_status_txt = "Open Now";
			                $current_business_open = true;
			                break;
			            }else {
			                $current_business_status_txt = "Closed Now";
			            }
			            
			        }  
			       if (empty($value['open'] ) && !empty($value['close'])) {
			           if (strtotime($time) < strtotime($value['close']) ){
			               $current_business_status_txt = "Open Now";
			               $current_business_open = true;
			               break;
			           }else {
			              $current_business_status_txt = "Closed Now";
			           }
			           
			       }
			       if (empty($value['close'] ) && !empty($value['open'])) {
		    	           if (strtotime($time) > strtotime($value['open']) ){
		    	               $current_business_status_txt = "Open Now";
		    	               $current_business_open = true;
		    	               break;
		    	           }else {
		    	               $current_business_status_txt = "Closed Now";
		    	           }
		    	         
		    	       }
			        if (empty($value['close'] ) && empty($value['open'])) {
			            $current_business_status_txt = "Closed Now";
			        }
			        
			    }
			    
			}

		} else {

			//added this code to show open now when open all day is chosen
			$the_day = date( "l" );//Monday thru Sunday text
			$monday = get_post_meta($b_id, 'wyz_open_close_monday_status', true);
			$tuesday = get_post_meta($b_id, 'wyz_open_close_tuesday_status', true);
			$wednesday = get_post_meta($b_id, 'wyz_open_close_wednesday_status', true);
			$thursday = get_post_meta($b_id, 'wyz_open_close_thursday_status', true);
			$friday = get_post_meta($b_id, 'wyz_open_close_friday_status', true);
			$saturday = get_post_meta($b_id, 'wyz_open_close_saturday_status', true);
			$sunday = get_post_meta($b_id, 'wyz_open_close_sunday_status', true);

			if ($monday =='open_all_day' && $the_day == 'Monday'){
				$current_business_status_txt = "Open Now";
				$current_business_open = true;
			} elseif ($monday =='closed_all_day' && $the_day == 'Monday'){
				$current_business_status_txt = "Closed Now";
			} elseif ($tuesday =='open_all_day' && $the_day == 'Tuesday'){
				$current_business_status_txt = "Open Now";
				$current_business_open = true;
			} elseif ($tuesday =='closed_all_day' && $the_day == 'Tuesday'){
				$current_business_status_txt = "Closed Now";
			} elseif ($wednesday =='open_all_day' && $the_day == 'Wednesday'){
				$current_business_status_txt = "Open Now";
				$current_business_open = true;
			} elseif ($wednesday =='closed_all_day' && $the_day == 'Wednesday'){
				$current_business_status_txt = "Closed Now";
			} elseif ($thursday =='open_all_day' && $the_day == 'Thursday'){
				$current_business_status_txt = "Open Now";
				$current_business_open = true;
			} elseif ($thursday =='closed_all_day' && $the_day == 'Thursday'){
				$current_business_status_txt = "Closed Now";
			} elseif ($friday =='open_all_day' && $the_day == 'Friday'){
				$current_business_status_txt = "Open Now";
				$current_business_open = true;
			} elseif ($friday =='closed_all_day' && $the_day == 'Friday'){
				$current_business_status_txt = "Closed Now";
			} elseif ($saturday =='open_all_day' && $the_day == 'Saturday'){
				$current_business_status_txt = "Open Now";
				$current_business_open = true;
			} elseif ($saturday =='closed_all_day' && $the_day == 'Saturday'){
				$current_business_status_txt = "Closed Now";
			} elseif ($sunday =='open_all_day' && $the_day == 'Sunday'){
				$current_business_status_txt = "Open Now";
				$current_business_open = true;
			} elseif ($sunday =='closed_all_day' && $the_day == 'Sunday'){
				$current_business_status_txt = "Closed Now";
			} else {
				$current_business_status_txt = "";

			}

		}
	
	    return array(
			'txt' 	=> $current_business_status_txt,
			'bool' 	=> $current_business_open
		);
	}
		
}

