function getExtImg(filename) {
    var ext = filename.split('.').pop();
    return "<img src='" + document.location.href + "assets/img/" + ext + ".png' alt ='"+ ext +"' class='img-responsive' width='40px'>";
}