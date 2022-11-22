<style>
.mainWrapper {
    height:100%;
    overflow:auto;
}
.we-box img {
    border:solid;
}

.buy-btn a{
    color:#fff;
}
.audio-sidebar-sec {
    height:100%;
    overflow:scroll;
    position: fixed !important;
    left: 0;
    top:20px;
    max-width: 15%;
    width: 100%;
}
.songImgWrap {
    max-height:168px;
}
.topBar {
    position:sticky !important;
    position:-webkit-sticky !important;
    top:0;
    z-index:5
}
.audioCats a{
    color: #fff;
    line-height: 2
}
.audioCats a:hover{
    color:#ffcc01;
}
.audio-sidebar-sec::-webkit-scrollbar{
    width:0px;
}
.userAvatar img {
    border-radius: 25px
}
    
.audio-banner{
    max-height: 400px;
    overflow:hidden;
}
.extra-html.active {
    width: 185px;
    flex-wrap: wrap;
    gap: 1em;
}
.audio-search .promagnifier {
    height: auto !important;
    border-right: solid 10px #000 !important;
    box-shadow: none !important;
}
body .extra-html {
    color: #fff0;
}
.we-audio-player * {
    color:#fff;
}
.we-audio-player .star-rating-con{
    width:110px !important;
    margin:10px 10px 0 0 !important;
}
.we-audio-player .dzsap-counter {
    margin-left:10px;
}
.songImg {
    max-height:200px;
}
.we-audio-player .star-rating-con .star-rating-bg, .star-rating-con .star-rating-prog {
    font-size: 25px !important;
    width:100% !important;
    color:#ffcc01 !important;
}
.we-audio-player .the-name.the-songname.second-line {
    color: #fff !important;
}
.we-audio-player .audioplayer.skin-wave.button-aspect-noir .player-but .the-icon-bg{
    background-color:#ffcc01;
}
.we-audio-player .audioplayer .comments-holder .the-avatar {
   
    background-color: #2ec132;
    
}
.we-audio-player .the-label{
    color:#000 !important;
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

.audio-comments #comment-form *, .audio-comments #comments * {
    color:#fff;
}

.audio-comments textarea#comment {
    background: #000;
    border: none;
    color: #fff;
}

.audio-comments a:hover {
    color:#23A455 !important;
}

.related-songs .player-but .the-icon-bg  {
   background:#1aa518;
}
.related-songs .audioplayer.skin-minimal .ap-controls .con-controls {
    width:65px;
    height:65px;
}
.related-songs * {
    color:#fff;
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
</style>
<?php 
global $template_type;
$logo = wyz_get_option( 'header-logo-upload' );?>
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
echo do_shortcode('[INSERT_ELEMENTOR id="212182"]');
echo '</div>';
wp_footer();
?>
<script>
jQuery(window).load(function(){

    jQuery('#page-loader-init').fadeOut('fast');
});

</script>
<!--div class="wrapper">
    <div class="sidebar-column">

        <div class="sticky">
            <div class="logo">
            </div>
            <div class="search">
            </div>
            <div class="user">
            </div>
            <div class="music-nav"></div>
            <div class="cats">
            </div>
            <div class="image-ad"></div>
        </div>

    </div>
    <div class="main-block">

        <div class="topMobileNav"></div>
        <div class="audioBanner"></div>

        <div class="audioBlock">
            <div class="artistInfo"></div>
            <div class="player"></div>
            <div class="topCharts"></div>
        </div>

        <div class="audioBanner2"></div>
        
        <div class="commentsRelated">
            <div class="audioComments"></div>
            <div class="relatedAudio"></div>
        </div>

        <div class="mobileFooter">
        
        </div>
    </div>
</!--div-->