$(function(){
    $("#comment-bar").on("click",function(){
        $($(this).attr("data-toggle")).toggle('hide');
    });
});