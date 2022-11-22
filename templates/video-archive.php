<style>
.mainWrapper {
    height:100%;
    overflow:auto;
}

#page-loader-init {
position:absolute;
height:100%;
width:100%;
display:flex;
flex-wrap:wrap;
align-content:center; 
z-index:9;
}
.spinner {
    height:1px;
}
.logo {
    width:100%;
    text-align:center;
}
.weboxAvatar {
    margin: 0 0 8px 5px;
}
.weboxAvatar img {
    background:#fff !important;
    border:solid 3px #fff !important;
    border-radius: 25px !important;
}
.watchBtn {
    padding:5px;
}
.topBar {
    position:fixed !important;
    position:-webkit-sticky !important;
    top:0;
    z-index:5
}
.the-vidz .elementor-post__avatar img{
    background: #fff;
    box-shadow: 0px 0px 0px 1px #ddd
}
.the-vidz {
    margin-top:50px !important;

}
.side-menu {
    height:100%;
    overflow:scroll;
    position: fixed !important;
    left: 0;
    top:20px;
    max-width: 15%;
    width: 100%;
}
.theVidSec {
    margin-top: 130px;
    min-height:90vh;
}
.side-menu::-webkit-scrollbar{
    width:0px;
}
.vidCats *, .mediaCats * {
    color:#fff;
}
.vidCats a:hover, .mediaCats a:hover {
    color:#ffcc01;
}
.userAvatar img {
    border-radius: 25px !important;
}


</style>
<?php $logo = wyz_get_option( 'header-logo-upload' );?>
<!DOCTYPE html>
<html class="no-js main-html"<?php echo ( is_rtl() ? 'dir="rtl"' : '' );?> <?php language_attributes();?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<?php wp_head();?>
</head> 
<?php
echo '<div id="page-loader-init" style="background-color:#fff;"><div class="logo"><img src="'.$logo.'"/></div><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';
echo '<div class="mainWrapper">';

echo do_shortcode('[INSERT_ELEMENTOR id="212299"]');

echo '</div>';

wp_footer();
?>
<script>
jQuery(window).on('load', function(){

    jQuery('#page-loader-init').fadeOut('fast');
});
</script>