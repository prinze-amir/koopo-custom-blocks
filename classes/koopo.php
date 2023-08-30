<?php
namespace KoopoBlocks\Classes;

class Koopo{

	public static function show_top_picks_bagde($id=-1){
		$local_tag = '"Shop Smart. Produce Local"';
		if ( $id<1)$id=get_the_ID();
		if( 'true' !== get_post_meta($id,'koopo_top_pick',true))return '';
		$koopo_options  = get_option('koopo-options');
		$ktpbadge = $koopo_options['ktp_badge'];
		$theBadge = '<div class="theBadge-'.get_post_type($id).'"><!--div style="display:inline-block"><p class="local-tag" style="color:#23A455">'.$local_tag.'</p></!--div--><img class="kbadge" src="'.wp_get_attachment_url($ktpbadge).'" data-no-lazy="1"></div>';
		return $theBadge;
	}

	/*
	*add paid in my city badge
	*
	*/
	private static $pimc_badge;

	public static function show_paid_in_my_city($id=-1){
		$local_tag = 'Shop Smart. Produce Local';
	//	$sticky = is_sticky();
		if ( $id<1)$id=get_the_ID();
		if('yes'!==get_post_meta($id,'_paid_in_mycity',true))return '';
		if ( self::pimc_expired( $id ) )return '';
		if ( '' == self::$pimc_badge )
		echo '<div style="display:inline-block"><p class="local-tag" style="color:#23A455">'.$local_tag.'</p></div>';
		echo self::$pimc_badge = '<a href="https://paidinmycity.com" target="_blank"><img class="kbadge" src="https://koopoonline.com/wp-content/uploads/2018/09/paid-indetroit-small.png"></a>';
		return self::$pimc_badge;
	}

	/**
	 * Check if business verification has expired
	 *
	 * @param integer $id business id.
	 */
	public static function pimc_expired( $id ) {
		$verified_date = get_post_meta( $id, 'paid_in_mycity_expiry', true );
		if ( empty( $verified_date) ) return false;
		return ( time() > strtotime( $verified_date ) );
	}
}