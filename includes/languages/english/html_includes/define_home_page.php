
<div class="flexslider">
  <ul class="slides">
    <li>
      <img src="/images/frontimages/slide1.jpg" />
    </li>
    <li>
      <img src="/images/frontimages/slide2.jpg" />
    </li>
    <li>
      <img src="/images/frontimages/slide3.jpg" />
    </li>
    <li>
      <img src="/images/frontimages/slide4.jpg" />
    </li>
  </ul>
</div>

  <script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all();
    });
    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
  </script>