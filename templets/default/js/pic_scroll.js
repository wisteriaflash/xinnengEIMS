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
        if(width<=1280 && width>960){
            bannerWidth = width;
        }else if(width<=960){
            bannerWidth = 960;
        }
        $('#slideBanner').width(bannerWidth);
        $('.slides_container a').width(bannerWidth);
    }
    setBannerSize();

    //jq-slide
    $('#slideBanner').slides({
        preload: true,
        preloadImage: 'templets/default/images/loading.gif',
        effect: 'slide',
        // effect: 'fade',
        // crossfade: true,
        slideSpeed: 350,
        fadeSpeed: 500,
        play: 5000          //自动播放时间
    });

    //jq-bjqs
    // $('#slideBanner').bjqs({
    //     animtype      : 'slide',
    //     height        : 400,
    //     width         : bannerWidth,
    //     responsive    : true,
    //     animspeed     : 5000
    // });
});