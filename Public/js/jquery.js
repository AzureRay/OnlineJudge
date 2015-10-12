    $(function(){
        $('.hidden_nav').hover(function(){
            if($('.hidden_nav > ul').is(':hidden')){
                $('.hidden_nav > ul').css('display','block');
            }
            return false;
        },function(){
            if($('.hidden_nav > ul').is(':visible')){
                $('.hidden_nav > ul').css('display','none');
            }
            return false;
        });
    });