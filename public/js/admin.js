$(function () {
    try{
        $('#editor').summernote({ height: 300 ,
            callbacks:{
                onImageUpload:function(files,editor,welEditable){
                    upload(files[0],this);
                }
            } 
        });
    }catch(e){}

    after_input("#parent_search",function(input,el){
        $table=$($(el).attr("data-table")).find("tbody");
        $table.find("tr").filter(function(){
            $(this).toggle($(this).text().indexOf(input)>-1);
        });
    });
    $("#tagger").keydown(function(e){
        if(e.keyCode===13){
            e.preventDefault();
            var list=$(this).attr("data-action");
            var tag=$("<div></div>").addClass("tag").append($("<span></span>").addClass("title").text($(this).val()))
            .append($("<span></span>").addClass("close mr-auto").append($("<i></i>").addClass("icon-cancel")))
            $(list).append($(tag).append($("<input type='hidden'>").attr("name","tags[]").attr("value",$(this).val())));
            $(this).val("");
        }
    });

    $(".tags").on("click",".close",function(){
        $($(this).parent()).remove();
    });

    $("#thumb").change(function(){
        console.log("changed");
        if(this.files && this.files[0]){
            var reader = new FileReader();
            reader.onload=function(e){
                $("#preview").attr("src",e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    $("[maxLength]").keyup(function(){
        $(this).siblings(".counter").text($(this).val().length+"/"+$(this).attr("maxLength"));
    });

    $(".thumbnail-reset").click(function(){
        $("#thumb").val(null);
        $("#preview").attr("src","../images/noimage.jpg");
    });

    $("form.confirm").on("submit",function(e){
        e.preventDefault();
        if(confirm("Are You Sure?")){
            this.submit();
        }
    });

});


function makeRow(data){
    $row=$("<tr></tr>");
    $row.append($("<td></td>").append($("<input type='radio' name='parent[]'/>").attr("value",data.id)));
    $row.append($("<td></td>").text(data.cat_name));
    var parent=(data.parent_name)?data.parent_name:"-";
    $row.append($("<td></td>").text(parent));
    return $row;
}


function upload(file,editor){
    
    data=new FormData();
    data.append("file",file);

    $.ajax({
        data:data,
        url:"/admin/new/post/upload",
        type:"POST",
        cache:false,
        processData:false,
        contentType:false,
        success:function(url){
            console.log(url);
            $(editor).summernote("editor.insertImage",url);
        }
    });
}