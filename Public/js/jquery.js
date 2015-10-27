$(function(){
    //header显示隐藏多余的导航
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

    //detail页面评论聚焦失焦默认文字显示隐藏
    var str = '扯淡、想法、吐槽、灌水......想什么说什么！！！';
    $('.comment textarea').focus(function(){
        if(this.value == str) this.value = '';
        $(this).css('color','#555')
    });
    $('.comment textarea').blur(function(){
        if(this.value == '') {
            this.value = str;
            $(this).css('color','#A9A9A9');
        }
    });

    //test

    $(".register_btn").click(function(){
        $.post("doRegister",{
                    userId : $('#register_userid').val(),
                    nick : $('#nick').val(),
                    password : $('#register_password').val(),
                    rptpassword : $('#rptpassword').val(),
                    school : $('#school').val(),
                    email : $('#email').val(),
                    vcode : $('#vcode').val()
                },function(data,textStatus){
                    if(data.code != 1001) {
                        alert(data.result.msg);
                    } else {
                        location.href = data.result.msg;
                    }
                },'json'
        );
    });

    //表格展示
    $(function(){
        $('tr:odd').css('background','#F9F9F9');
    });
});