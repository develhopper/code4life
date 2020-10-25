$(function(){
    $("[data-toggle]").on("click",function(){
        $($(this).attr("data-toggle")).toggle('hide');
    });
    $("#comment-form").on("submit",function(e){
        e.preventDefault();
        var form=$(this);
        var submit=form.find(":input[type=submit]");
        submit.prop('disabled', true);
        submit.val("در حال ارسال ...");
        $.ajax({
            type:form.attr("method"),
            url:form.attr("action"),
            data:form.serialize(),
            success:function(data){
                console.log(data);
                submit.val("ارسال شد");
            },
            error:function(data){
                console.log(data);
            }
        });
    });
});