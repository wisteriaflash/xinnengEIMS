$(document).ready(function () {
    //set home selected
    var hasHover = $("#navMenu").hasClass('hover');
    if(!hasHover){
      $("#navMenu li:first").addClass('hover');
    }

    //slideBanner--初始化自适应宽度
    var bannerWidth = 1280;
    function setBannerSize(){
        var width = $(window).width()
        if(width<=1280 && width>1000){
            bannerWidth = width;
        }else if(width<=1000){
            bannerWidth = 1000;
        }
        $('#slideBanner').width(bannerWidth);
        $('.slides_container a').width(bannerWidth);
    }
    setBannerSize();
    $('#slideBanner').bjqs({
        animtype      : 'slide',
        height        : 400,
        width         : bannerWidth,
        responsive    : true,
        animspeed       : 5000
    });
});