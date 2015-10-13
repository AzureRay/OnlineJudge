$(function(){
    $('.hidden_nav').hover(function(){
        if($('.hidden_nav > ul').is(':hidden')){
            $('.hidden_nav > ul').css('display','block');
        }
        return false;
    },function(){
        if($('.hidden_nav > ul').is(':visible')){
            setTimeout(function(){
                $('.hidden_nav > ul').css('display','none');
            },200);
        }
        return false;
    });
});