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

.musicMeta .elementor-shortcode {
    display: flex;
    justify-content: space-between;
    padding-top: 5px;
    position: absolute;
    bottom: 5px;
}

.musicMeta .elementor-shortcode div {
    margin-right:10px;
}


.getStartedLogin {
    display:none;
}
.getStartedLogin input, .reg-form input {
    width:100%;
}
.audio-loop h3 a {
    word-break: break-all;
    /* overflow-wrap: anywhere; */
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
    z-index:5
}
@media(max-width:475px){
    
    .audio-image-wrap {
        width:25% !important;
        height:70px;
    }
}
.audio-image-wrap {
    max-height: 175px;
}
.theAudio {
    min-height:100vh;
}
.byArtist * {
    display: flex;
    flex-wrap: nowrap !important;
    min-width: 100%;
}
.albumArtist .elementor-author-box__avatar {
    height: 100%;
}
.albumArtist .elementor-author-box {
    display: flex;
    gap: 15px;
    align-items:center;
}
.audio-loop .grid-item {
        flex-basis:325px;
        flex-shrink:1;
        min-width:270px;
    }
    .audio-loop * {
        color:#fff;
        line-height:1.2;
    }
    
    .audio-loop .playbtn.player-but {
    background-color:#13B853 !important;
    }

    .audio-loop .audioplayer .ap-controls .scrubbar .scrubBox-hover {
    background: #13B853 !important;
    }
.audioCats a{
    color: #fff;
    line-height: 2
}
.audioCats a:hover{
    color:#ffcc01;
}
.userAvatar .elementor-author-box {
    display: inline-flex;
    flex-wrap: nowrap;
    gap: 1em;
}
.albumArtist img {
    background:#fff;
    border: solid 2px #fff !important;
}
.audioSearch .promagnifier {
    height: auto !important;
    border-right: solid 10px #000 !important;
    box-shadow: none !important;
}
.sticky-sidebar {
    height:100vh;
    overflow:scroll;
    position: fixed !important;
    left: 0;
    top:20px;
    max-width: 15%;
    width: 100%
}

.sticky-sidebar::-webkit-scrollbar{
    width:0px;
}
.audioplayer-inner {
    display:block !important;
    text-align:center;
}
.audioplayer.skin-minimal .ap-controls .con-controls, .audioplayer.skin-minimal .ap-controls {
    width: 60px !important;
    height: 60px !important;
}
.playbtn.player-but {
    border-radius:50%;
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
echo do_shortcode('[INSERT_ELEMENTOR id="212169"]');
echo '</div>';
wp_footer();
?>
<script>

jQuery(window).on('load', function(){

    jQuery('#page-loader-init').fadeOut('fast');
});

jQuery(document).on('click', '.getReg', function(){
    jQuery('.reg-form').show();
    jQuery('.getStartedLogin').hide();
});
jQuery(document).on('click', '.getLogin', function(){
    jQuery('.reg-form').hide();
    jQuery('.getStartedLogin').show();
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