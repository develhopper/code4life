$(function () {
    $('#editor').summernote({ height: 300 ,
        callbacks:{
            onImageUpload:function(files,editor,welEditable){
                upload(files[0],this);
            }
        }
    
    });
});

function upload(file,editor){
    
    data=new FormData();
    data.append("file",file);

    $.ajax({
        data:data,
        url:"http://localhost:8000/upload.php",
        type:"POST",
        cache:false,
        processData:false,
        contentType:false,
        success:function(url){
            $(editor).summernote("editor.insertImage",url);
        }
    });
}