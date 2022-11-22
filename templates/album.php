<style>
.mainWrapper {
    height:100%;
    overflow:auto;
}
.addtocartbutton {
    padding: 5px 15px;
    color: #fff;
    border-radius: 8px;
    background: #33ca4d;
    width: 100%;
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 5px;
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
}.topBar {
    position:fixed !important;
    top:0;
    z-index:5
}
.tracksCol{
    min-height:90vh;
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
.userAvatar img {
    border-radius: 25px
}
.side-menu::-webkit-scrollbar{
    width:0px;
}
 .mediaCats * {
    color:#fff;
}
.mediaCats a:hover{
    color:#ffcc01;
}
span.elementor-post-info__terms-list {
    display:inline-flex;
    gap:3px;
}
.authorInfo li a:hover, .authorInfo li a span:hover {
    color:#ffba12;
}
.Share { 
    margin:auto;
}
.authorAvatar img {
    background:#fff;
}
.audio-loop .playbtn.player-but { 
background-color:#13B853 !important;
}
.imgCol {
    height: 130px;
    max-width: 125px;
}
.uploadBtn {
    background:#fff;
    padding:10px;
    border-radius:15px;
}
.imgCol img {
    border-radius:8px !important;
}
.track {
    width:80%;
    display:inline-block;
}
/*audio player style*/
.extra-html.active {
    width: 185px;
    flex-wrap: wrap;
    gap: 1em;
}
.we-audio-player .star-rating-con{
    width:110px !important;
    margin-right:5px !important;
}
.we-audio-player .star-rating-con .star-rating-bg, .star-rating-con .star-rating-prog {
    font-size: 25px !important;
    width:100% !important;
}
.we-audio-player .audioplayer.skin-wave.button-aspect-noir .player-but .the-icon-bg{
    background-color:#ffcc01;
}
.we-audio-player .audioplayer .comments-holder .the-avatar {
   
    background-color: #2ec132;
    
}

.we-audio-player .skin-wave.skin-wave-is-spectrum .scrubbar:after {
    background: #fff !important;
}

.we-audio-player button.submit-ap-comment.dzs-button-dzsap.float-right {
    width: 100%;
}
 
@media(max-width:475px){
    .we-audio-player .dzstooltip--inner {
        max-width:100% !important;
        width:95vw !important;   
    }
}
.social-artist-links {
    display: inline-flex;
    gap: 10px;
    font-size: 20px;
    justify-content: flex-end;
    flex-wrap:wrap;
}
.social-artist-links a{
    padding:10px;
    color:#fff;
    background:#000;
}

.social-artist-links a:hover,.star-rating-prog{
    color:#ffcc01 !important;
}

body .btn-zoomsounds.btn-like:hover .the-icon>svg path, body .btn-zoomsounds.btn-like.active .the-icon>svg path {
    fill: #24bf18;
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
echo do_shortcode('[INSERT_ELEMENTOR id="213509"]');
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