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
});
// $.ajax({
//     type:"GET",
//     url:"http://code4life.com/api/category",
//     data:{search:input},
//     success:function(data){
//         if(data){
//             console.log(data);
//             $table=$($(el).attr("data-table")).find("tbody");
//             $table.empty();
//             data.forEach(element => {
//                 $table.append(makeRow(element));
//             });
//         }
//     }
// });

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