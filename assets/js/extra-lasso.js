(function($){
    $(document).ready(function(){
    
        $('#lasso--edit').on('click', function(){
         //   $('.sidebar-container, #ele-footer1').css('display','none');
           // $('.col-lg-9').css('width','100%');
            $('.betterdocs-widget-container').hide();
        });
    
        $(window).on('load',function() { 
        var hash = window.location.hash.slice(1); // get the hash, and strip out the "#"
    
        if( hash && hash === 'edit' )  // if there was a hash
        $('#lasso--edit' ).trigger('click');
      });
    
    });
    $(document).on( 'click', '#lasso--exit', function(){
        //    $('.sidebar-container, #ele-footer1').css('display','block');
            $('.betterdocs-widget-container').show();
         /*   if ( $(window).width() > 972 ){
            $('.col-lg-9').css('width','75%');
            } else {
                $('.col-lg-9').css('width','100%');
            }*/
        });
    
        $(document).on('click','#lasso--post-format', function(){
        //	$('.hide-lasso').removeClass('hide-lasso');
            $('.lasso-mobile').toggle();
        });
        $(document).on('change', function(){
            var srcv = $('#aesop-generator-attr-src').val();
            if (srcv =="self") {
                    $(".aesop-video-hosted").slideDown();
                    $(".aesop-video-id").slideUp();
            }
        });
         
    })(jQuery);