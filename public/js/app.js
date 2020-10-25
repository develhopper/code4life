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
