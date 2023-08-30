(function($){
    var a = function() {
        var b = $(window).scrollTop();
        var anchor = $("#notification-anchor");
        if (anchor.length) {
        var d = anchor.offset().top;
        }
        var c = $("#header-bottom");
        var f = $("#head-place");

        if (b > d) {
           c.css({
               position:"fixed",
               top:"0px", 
               width:"100%", 
               webkitTransition: "all 3s", 
               mozTransition: "all 3s",
               msTtransition: "all 3s",
               oTransition: "all 3s",
               transition: "all 1s ease 1s",
               opacity: 1,
               });     
        
            f.css('display',"block");
        
        } else {
            c.css({position:"relative",top:""});
            f.css('display',"none");

        }
    };
    
    $(window).scroll(a);a()
})(jQuery);