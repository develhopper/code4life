var current_dir;
var clipboard={};
var baseurl="http://code4life.com";
$(function(){
    /** close context menu */
    $("body").click(function(){
        removeContextMenu();
    });
    
    /** get directory listing */
    getListing(refresh);

    /** register context menu plugin */
    $.fn.contextMenu=function(items){
        this.on("contextmenu","tr",function(e){
            removeContextMenu();
            makeMenu(this,items,{top:e.pageY+5,left:e.pageX});
            return false;
        });
    }

    /** context menu items */
    var items={
        View:{click:view,icon:"icon-eye"},
        "View as Text":{click:viewas,icon:"icon-eye",condition:function(context){return $(context).attr("data-type")=="doc";}},
        "New Folder":{click:mkdir,icon:"icon-folder"},
        "New File":{click:mkfile,icon:"icon-doc-text"},
        "Paste Here":{click:paste,icon:"icon-paste",condition:function(){return Object.keys(window.clipboard).length !== 0}},
        "Paste Into":{click:pasteInto,icon:"icon-paste",condition:function(context){return Object.keys(window.clipboard).length !== 0 && $(context).attr("data-type")=="dir"}},
        Cut:{click:cut,icon:"icon-scissors"},
        Copy:{click:copy,icon:"icon-docs"},
        Delete:{click:del,icon:"icon-trash"}
    };

    $(".filemanager tbody").contextMenu(items);
    $(".filemanager tbody").on("dblclick","tr",function(){
        if($(this).find(".dir").text())
            getListing(refresh,$(this).attr("data-path"));
    });
    $(".viewer-container").on("click",".viewer-close",function(){
        $(this).closest(".dialog").remove();
        bringDown();
    });
    $(".uploader").on("change",function(){
        if($(this).prop("files").length>0 && confirm("Are you Sure to upload selected file?"))
            uploader($(this).prop("files")[0]);            
    });
});

types={
    "code":{icon:"icon-code",callback:editor},
    "doc":{icon:"icon-doc",callback:null},
    "picture":{icon:"icon-picture",callback:imageViewer},
    "video":{icon:"icon-video",callback:null},
    "archive":{icon:"icon-file-archive",callback:null},
    "text":{icon:"icon-doc-text",callback:editor}
};

function uploader(input){
    if(input){
        var dialog=Dialog("Uploading... ","Please Wait");
        
        call("/api/file/upload","POST",{"file":input,"path":current_dir},function(data){
            dialog.dissmis();
                getListing(refresh,current_dir);
                alert(data.message);
        });
    }
}

function Dialog(title,body){
    var dialog=$("<div class='dialog'></div>");
    $(dialog).append($("<div class='viewer-title'></div>").append($("<span></span>").text(title)));
    $(dialog).append($("<div class='body'></div>").text(body).append("<i class='icon-arrows-cw animate-spin'><i/>"));
    $(".viewer-container").append(dialog);
    bringUp();
    return {
        dissmis:function(){
            $(dialog).remove();
            bringDown();
        }
    };
}

function bringUp(){
    $(".viewer-container").css("z-index",1);
    $(".filemanager").css("filter","blur(4px)");
}

function bringDown(){
    $(".viewer-container").css("z-index",-1);
    $(".filemanager").css("filter","none");
}

/** make context menu popup */
function makeMenu(context,items,position){
    var menu=$("<div></div>").addClass("context-menu");
    var ul=$("<ul></ul>");

    Object.keys(items).forEach(key => {
        make=true;
        if('condition' in items[key])
            make=items[key].condition(context);
        if(make){
            var li=$("<li></li>").append($("<span></span").text(key)).click(function(){items[key].click(context)});
            if('icon' in items[key])
                $(li).prepend($("<i></i>").addClass(items[key].icon));
            $(ul).append(li);
        }
    });

    $(menu).append(ul).css({
        top:position.top+"px",
        left:position.left+"px"
    });
    $(document.body).append(menu);
}

/** directory listing ajax */
function getListing(callback,path=null){
    call("/api/file/listing","POST",{path:path},callback);
}

/** refresh table */
function refresh(data){
    var dirs=data.dir;
    var files=data.file;
    window.current_dir=data.current_directory;
    $(".filemanager caption").text(current_dir);
    $(".filemanager tbody").empty();
    dirs.forEach(element => {
        makeRow(element,"dir");
    });
    files.forEach(element=>{
        makeRow(element,"file");
    });
}

/** make table rows for directory list */
function makeRow(data,type){
    var file_type=(data.file_type)?data.file_type:"dir";
    var row=$("<tr></tr>").attr("data-path",data.path).attr("data-type",file_type);
    $(row).append($("<td></td>").append($("<input type='checkbox' />")));
    $(row).append($("<td></td>").append($("<i></i>").addClass(getIcon(data.file_type))));
    if(type=="dir")
        $(row).append($("<td></td>").text(data.name).addClass("dir"));
    else
        $(row).append($("<td></td>").text(data.name).addClass("name"));
    $(row).append($("<td></td>").text(data.size));
    $(".filemanager tbody").append(row);
}

/** get file type icon */
function getIcon(file_type){
    var icon="icon-doc";
    if(file_type=="")
        icon="icon-folder";
    else{
        Object.keys(types).every(key => {
            if(key==file_type){
                icon=types[key].icon;
                return false;
            }
            return true;
        });
    }
    return icon;
}

/** remove context menu */
function removeContextMenu(){
    $(".context-menu").remove();
}

/** view context menu callback function */
function view(context){
    var type=$(context).attr("data-type");
    if(type!="dir"){
        if(types[type])
            if(types[type].callback)
                types[type]["callback"](context);
    }
    else
        $(context).dblclick();
}

function viewas(context){
    editor(context);
}

/** image viewer */
function imageViewer(context){
    var path=$(context).attr("data-path");
    call("/api/file/get_url","POST",{path:path},function(data){
        var viewer=$("<div class='dialog imageviewer'></div>");
            $(viewer).append($("<div class='viewer-title'></div>")
            .append($("<span></span>").text($(context).find(".name").text()))
            .append("<span class='icon-cancel viewer-close'></span>"));
            var img=new Image();
            img.src=data.url;
            $(viewer).append($("<div></div>").addClass("body").append(img));
            $(".viewer-container").append(viewer);
            bringUp();
    });
}

function editor(context){
    var path=$(context).attr('data-path');
    var callback=function(){
        call("/api/file/put_content","POST",{path:path,content:$(".editor .edit").val()},function(data){
                $(".viewer-close").click();
                alert(data.message);
            });
    }

    call("/api/file/get_content","POST",{path:path},function(data){
            var viewer=$("<div class='dialog editor'></div>");
            var title=$("<div class='viewer-title'></div>");
            var body=$("<div class='body'></div>").append($("<textarea class='edit'></textarea>").text(data));
            var buttons=$("<div class='row justify-end'></div>");
            $(buttons).append($("<button class='btn'>Cancel</button>").click(function(){$(".viewer-close").click()}));
            $(buttons).append($("<button class='btn btn-primary'>Save</button>").click(callback));
            $(title).append($("<span></span>").text(path)).append("<span class='viewer-close'><i class='icon-cancel'></i><span>");

            $(viewer).append(title).append(body).append(buttons);

            $(".viewer-container").append(viewer);
            bringUp();
        });
}

function mkdir(){
    var dirname=prompt("please enter directory name: ");
    if(dirname){
        call("/api/file/make_dir","POST",{path:current_dir,name:dirname});
        getListing(refresh,current_dir);
    }
}

function mkfile(){
    var filename=prompt("please enter file name: ");
    if(filename){
        call("/api/file/make_file","POST",{path:current_dir,name:filename});
        getListing(refresh,current_dir);
    }
}

/** delete contextmenu callback */
function del(context){
    if(confirm("Are you sure to delete this item?")){
        call("/api/file/remove","POST",{path:$(context).attr("data-path")});
        getListing(refresh,window.current_dir);
    }
}

/** copy contextmenu callback */
function copy(context){
    window.clipboard={
        src:$(context).attr("data-path"),
        type:"copy"
    };
}

/** cut contextmenu callback */
function cut(context){
    window.clipboard={
        src:$(context).attr("data-path"),
        type:"cut"
    };
}

/** paste contextmenu callback */
function paste(){
    window.clipboard.dst=window.current_dir;
    route="/api/file/"+clipboard.type;
    call(route,"POST",clipboard);
    if(window.clipboard.type=="cut")
        window.clipboard={};
    getListing(refresh,window.current_dir);
}

function pasteInto(context){
    window.clipboard.dst=$(context).attr("data-path");
    route="/api/file/"+clipboard.type;
    call(route,"POST",clipboard);
    if(window.clipboard.type=="cut")
        window.clipboard={};
    getListing(refresh,window.current_dir);
}

function call(route,type,data,callback=null,error=null){
    var url=baseurl+route;
    if(callback==null)
        callback=defaultCallback;
    if(error==null)
        error=defaultCallback;
    if(type=="GET"){
        $.ajax({
            type:type,
            url:url,
            data:data,
            success:callback,
            error:error
        });
    }else{
        $.ajax({
            type:type,
            url:url,
            data:getFormData(data),
            processData:false,
            contentType:false,
            success:callback,
            error:error
        });
    }
}

function defaultCallback(data){
    if('message' in data)
        alert(data.message);
}

function getFormData(data){
    formData=new FormData();
    for(var key in data){
        formData.append(key,data[key]);
    }
    
    return formData;
}