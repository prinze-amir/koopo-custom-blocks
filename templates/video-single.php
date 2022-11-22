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
.paginate *{
    color:#fff;
}
.nav-links a:hover {
    color:#ffba12;
}
nav.navigation.post-navigation {
    display: block;
}
.spinner {
    height:1px;
}
.logo {
    width:100%;
    text-align:center;
}
.topBar {
    position:sticky !important;
    position:-webkit-sticky !important;
    top:0;
    z-index:5;
}
.the_champ_sharing_container.the_champ_horizontal_sharing {
    margin: auto;
} 
. {
        margin-top: 50px;
    }
@media(max-width:900px){
    .theVidSec {
        margin-top: 150px;
    }
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
.selfVid video {
    object-fit: contain !important;
    max-height:85vh;
}

.userAvatar img {
    border-radius: 25px
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
span.elementor-post-info__terms-list {
    display:inline-flex;
    gap:3px;
}
.authorInfo li a:hover, .authorInfo li a span:hover {
    color:#ffba12;
}
.vidShare { 
    margin:auto;
}
.authorAvatar img {
    background:#fff;
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
echo do_shortcode('[INSERT_ELEMENTOR id="212282"]');
echo '</div>';
wp_footer();
?>
<script async>

jQuery(window).on('load', function(){

jQuery('#page-loader-init').fadeOut('fast');
});
    var video = jQuery('.elementor-video');
    
jQuery(document).ready(function(){
   video.each(function(){
    
    var vheight =  jQuery(this).height();
    var ratio = jQuery(this).closest('.elementor-fit-aspect-ratio');
    var container = ratio.closest('div');
        container.css('height', vheight); 

   });
});

</script>