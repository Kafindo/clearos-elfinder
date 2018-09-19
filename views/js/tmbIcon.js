function getExtImg(filename,type) {
    if(type==="d")
    {
        return "<img src='" + document.location.href + "/assets/img/ext/folder.png' alt ='folder' class='img-responsive' width='40px'>";
    }else{
        var ext = filename.split('.').pop();
        return "<img src='" + document.location.href + "/assets/img/ext/" + ext + ".png' alt ='"+ ext +"' class='img-responsive' width='40px'>";
    }

}