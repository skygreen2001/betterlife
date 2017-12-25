$(function(){
  // 顶部导航滚动显示底部挡板效果
  $(document).scrollTop() <= 0 ? $(".navbar").removeClass("nav-scroll") : $(".navbar").addClass("nav-scroll");
  $(document).on("scroll", function() {
      if ($(window).width() >= 768){
        $(document).scrollTop() <= 0 ? $(".navbar").removeClass("nav-scroll") : $(".navbar").addClass("nav-scroll");
      }
  });
  if ($(window).width() < 768) $(".navbar").addClass("nav-scroll");
  $("nav").hover(function() {
      $(".navbar").addClass("nav-scroll");
  },function(){
      if ($(window).width() >= 768) $(".navbar").removeClass("nav-scroll");
  });

});
