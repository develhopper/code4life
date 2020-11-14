$(function(){
    $("[data-toggle]").on("click",function(){
        $($(this).attr("data-toggle")).toggle('hide');
    });

    $("#comment-form").on("submit",function(e){
        e.preventDefault();
        var form=$(this);
        var submit=form.find(":input[type=submit]");
        submit.prop('disabled', true);
        submit.addClass("bg-blue-grey");
        $.ajax({
            type:form.attr("method"),
            url:form.attr("action"),
            data:form.serialize(),
            success:function(data){
                submit.removeClass("bg-blue-grey");
                submit.prop('disabled', false);
                make_toast("Your comment posted successfuly :)");
            },
            error:function(data){
                submit.removeClass("bg-blue-grey");
                submit.prop('disabled', false);
                make_toast("Somthing wrong happened, we lost your comment :(","danger");
            }
        });
    });

    $(".tab-item").on('click',function(e){
        e.preventDefault();
        $(".tab-item").removeClass("active");
        $(this).addClass("active");
        show_tab();
    });
    after_input("[data-check]",function(input,el){
        $element=$(el);
        var entity=$(el).attr("data-check");
        var data;
        data=new FormData();
        data.append('type',entity);
        data.append('data',input);
        $.ajax({
            url:"/api/check_username",
            type:"POST",
            data:data,
            processData:false,
            contentType:false,
            success:function(data){
                $reg_button=$("#register_button");
                if(data.code!=200){
                    $element.css("border-color","red");
                    $($element.attr("data-message")).css({"color":"red","display":"block"});
                    $reg_button.prop("disabled",true);
                }else{
                    $element.css("border-color","green");
                    $($element.attr("data-message")).css({"color":"green","display":"block"});
                    $reg_button.prop("disabled",false);
                }
                $($element.attr("data-message")).text(data.message);
            }
        });
    });
});

function make_toast(message,type="primary",rtl=false){
    type="bg-"+type;
    if(rtl==true)
        type+=" rtl";
    var toast=$("<div></div>").addClass("toast "+type).attr("id","toast-id");
    var msg=$("<span></span>").addClass("message").text(message);
    var close=$("<span></span>").addClass("close").append($("<i></i>").addClass("icon-cancel"));
    close.on("click",function(){
        toast.remove();
    });
    toast.append(msg).append(close).appendTo($("body"));
}

function show_tab(){
    $(".tab-contents .tab").hide();
    var tab=$(".tab-item.active a").attr("href");
    if(tab=="#skills"){
        progress();
    }
    $(tab).fadeIn(500);
}

function after_input(id,func){
    var timer;
    var timeout=500;
    var func=func;
    $(id).keyup(id,function(e){
        if(e.keyCode==13)
            e.preventDefault();
        clearInterval(timer);
        var value=$(this).val();
        var element=this;
        timer=setTimeout(function(){
            func(value,element);
        },timeout);
    });
}