 /*
   - general script calls
   
  -->> --------------------------
    Table of Contents:
    1 - SelectNav 
    2 - Nicescroll Call 
    3 - UI Scroll To Top Call
    4 - Sidebox Menu - Toggle
    5 - Main Menu Toggle
    6 - Sidebox Menu - Open Active Menu 
    7 - Dropdown(for Main Menu) Call
    8 - SideboxSlider
    9 - Shopping Cart
    10 - Grid Product Listing Effect
    11 - Tooltip
    12 - Login Form styling
    13 - Shopping Cart styling
    14 - SelectBox Setups
    15 - Footer Info Toggle
    16 - Custom Box Calls
    17 - Avoid `console` errors in browsers that lack a console.
    18 - Loader Fade Out
    19 - Functions    
  -->> --------------------------- */


jQuery(function($) {     
    
    /* ------- 1 - SelectNav ------- */    
    selectnav('headerNav',{
      label: 'TOP MENU',
    });    
    $(".selectnav").selectbox({ 
      effect: "fade",      
      onChange: function (val, inst) {                      
        window.location = val;
      }
     });

    /* ------- 2 - Nicescroll Call ------- */
    $("html.gt-ie8").niceScroll({cursorcolor:"#333333", cursorwidth:8, cursorborder:"none", background:'#f8f8f8', cursoropacitymin:0.3, zindex: 9999});    

    /* ------- 3 - UI Scroll To Top Call ------- */
    $().UItoTop({ easingType: 'easeOutQuart' });    

    /* ------- 4 - Sidebox Menu - Toggle ------- */        
    $("#categoriesContent .toggle").on("click", function(event){
      $(this).next().slideToggle('fast');
    });

    /* ------- 5 - Main Menu Toggle ------- */
    $(document).on("click", ".mobileNav .toggle", function(){ $(this).next().slideToggle('fast'); }); 

    $('.mobileNavBtn').click(function(e) {
      e.preventDefault();
      $(this).toggleClass('on');
      $('nav .level1').slideToggle('fast');
    });

    /* ------- 6 - Sidebox Menu - Open Active Menu ------- */
    last_active = $("#categoriesContent .on, .mobileNav .on").last();
    if (typeof(last_active.html()) !== 'undefined') {      
      dropDownMenu(last_active);
    }
  
    /* ------- 7 - Dropdown(for Main Menu) Call ------- */
    // Uncomment it in case you want animated dropdown menu
    // $('.nav .level1').miniDropdown({
    //   animation: 'fade', 
    //   show: 200,
    //   hide: 200,
    //   delayIn: 200,
    //   delayOut: 200
    // });
    
    /* ------- 8 - SideboxSlider ------- */
    $('.sideBoxSlider').flexslider({
      animation: "slide",
      pauseOnHover: true,
      smoothHeight: true,
      directionNav: false,    
      slideshowSpeed: 5000
    });        

    /* ------- 9 - Shopping Cart ------- */    
    if($('html').hasClass('lt-ie9')){
      if($('.sideBoxContent').hasClass('sideBoxSlider')){
        $(".sideBoxSlider .slides li").hover(        
          function (e) { e.preventDefault(); makeTall($(this));  }, 
          function (e) { e.preventDefault(); makeShort($(this)); }
        ); 
      }
    }else{
      if($('.sideBoxContent').hasClass('sideBoxSlider')){
        $(".sideBoxSlider .slides li").hoverIntent(
          function (e) { e.preventDefault(); makeTall($(this));  }, 
          function (e) { e.preventDefault(); makeShort($(this)); }
        ); 
      }    
    }

    /* ------- 10 - Grid Product Listing Effect ------- */    
    if($(".centerBoxContentsProducts").hasClass("centerBoxContentsEffect")){
    
      /* ------- Set equal box height to all elements ------- */
      /* 
        !!! Uncomment it in case you want to have the same HEIGHT for all elements 
            if not make shure all your product images have the same height !!!
      */ 
      // var effectArray = new Array();
      // $(".centerBoxContentsEffect").each(function( index ) {
      //   var thisHeight = $(this).height();        
      //   effectArray.push(thisHeight);            
      // });
      
      // var largest = Math.max.apply(Math, effectArray);
            
      // $(".centerBoxContentsEffect").each(function( index ) {
      //   $(this).height(largest);
      // });
      
      /* ------- Show more info on hover ------- */
      $(".centerBoxContentsEffect").hover(
        function (e) {
          e.preventDefault(); 
          var $this = $(this);               
          var thisHeight = $this.height();        
          var infoHeight = $this.find(".listingProductContent").height() + 10;
          $this.height(thisHeight+infoHeight);                
          $this.css({"margin-bottom": "-"+infoHeight+"px"});        
        }, 
        function (e) {
          e.preventDefault();
          var $this = $(this);        
          var thisHeight = $this.height();
          var infoHeight = $this.find(".listingProductContent").height() + 10 ;
          $this.height(thisHeight-infoHeight);   
          $this.css({"margin-bottom": "1%"});
        }
      );

  	  var generalProducts = $('.generalProducts ').height();
  	  $('.generalProducts ').height(generalProducts);
    }

    /* ------- 11 - Tooltip ------- */
    $('.tooltip').tooltipster({ animation: 'grow' });
          
    /* ------- 12 - Login Form styling ------- */
    $('input').iCheck({
      checkboxClass: 'icheckbox_minimal',
      radioClass: 'iradio_minimal',
      increaseArea: '20%' // optional
    });

    /* ------- 13 - Shopping Cart styling ------- */
    $('.products').jScrollPane();    
    $(".productWrapper").hide();
    $(".cartContainer").hover(
      function (e) {
        e.preventDefault();
        $(".productWrapper").fadeIn();
      }, 
      function (e) {
        e.preventDefault();
        $(".productWrapper").fadeOut();
      }
    ); 
    
    /* ------- 14 - SelectBox Setups ------- */
    $(".currencyHeader select").selectbox({
      effect: "fade"
    });

    $("#currenciesContent select, #manufacturersContent select, #recordcompaniesContent select, #musicgenresContent select, #advSearchResultsDefault select").selectbox({
      effect: "fade"
    });
    
    $(".selectBoxContent select").selectbox({
      effect: "fade"
    });

    $("#productAttributes select").selectbox({
      effect: "fade"
    });

    /* ------- 15 - Footer Info Toggle ------- */
    $('.toggler').on('click',function(){
      var footerContent = $(this).parents('.col').find('.footerContent');
      if($(this).hasClass('on')){
        footerContent.slideUp();
        $(this).removeClass('on');
      }else{
        footerContent.slideDown();
        $(this).addClass('on');
      }
      
    });
    
    /* ------- 16 - Custom Box Calls ------- */   

    // Footer custom content example - 1    
    $("#testimonials").carouFredSel({
      items     : 2,      
      responsive: true,
      direction : "up",      
      scroll : {
        items     : 1,          
        duration    : 1000,             
        pauseOnHover  : true
      }
    });

    // Footer custom content example - 2    
    // $("#musthave_wrap").carouFredSel({
    //   // items     : 1,
    //   direction : "up",      
    //   width: "auto",                      
    //   auto : {        
    //     duration  : 1000,
    //     timeoutDuration: 2000,
    //     pauseOnHover: true
    //   },
    //   items : {
    //       height   : "variable",          
    //       visible   : {
    //           min     : 1,
    //           max     : 1
    //       }
    //   }   
    // }).find(".slide").hover(
    //   function() { $(this).find("div").slideDown(); },
    //   function() { $(this).find("div").slideUp(); }
    // ); 

    // Sidebox Custom Slider
    $("#sidebox_slider").carouFredSel({      
      infinite  : true,
      circular  : true,
      auto : {        
        duration  : 1000,
        timeoutDuration: 3000,
        pauseOnHover: true
      },
      responsive : true,
      items : {          
          visible   : {
              min     : 1,
              max     : 1
          }
      },  
      pagination : {
        container : "#sidebox_pag",
        keys    : true,
        duration  : 1000
      }
    });

    /* --- Footer - Custom Info --- */
    $('.ftInnerWrapper').hover(
      function() {
        $('#pager').addClass( 'visible' );
        $('#carousel').trigger( 'next' );
      }, function() {
        $('#pager').removeClass( 'visible' );
        $('#carousel').trigger( 'prev' );
      }
    );

    $('#carousel').carouFredSel({
      circular: false,
      infinite: false,
      responsive : true,
      direction: 'up',
      auto: false,
      scroll: {
        queue: 'last'
      }
    });

    $('#images').carouFredSel({
      circular: false,
      infinite: false,
      auto: false,
      pagination: '#pager'
    });

    /* ------- 17 - Avoid `console` errors in browsers that lack a console. ------- */    
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }

});

/* ------- 18 - Loader Fade Out ------- */
$(window).load(function() { $("#loader").remove(); });


/* ------- 19 - Functions ------- */
function dropDownMenu(activeLink){  

  level = activeLink.closest("ul");
  levelClass = level.attr("class");

  if(levelClass != "level1"){
    level.slideDown("fast");

    nextLink = level.closest("li").find('a:first');    
    dropDownMenu(nextLink);
  }

}

function makeTall(element){  
  $(element).find(".price_out").fadeOut('fast',function(){
    $(element).find(".product_info").animate({ height: '100%' }, 200, function() {
      $(element).find(".product_info h4").fadeIn('fast',function(){
        $(element).find(".product_info div").fadeIn('fast');
      });
    });  
  });
}

function makeShort(element){
  $(element).find(".product_info div").fadeOut('fast',function(){
    $(element).find(".product_info h4").fadeOut('fast',function(){
      $(element).find(".product_info").animate({ height: 0 }, 200, function() {
        $(element).find(".price_out").fadeIn('fast');
      });              
    });  
  });        
}

function isIE () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}