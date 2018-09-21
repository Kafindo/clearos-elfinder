(function(){
  var jqxhr = $.get( "/app/webfile_manager/execute/getUser_dirJson", function() {
  })
    .done(function(data) {
      console.log(data);
      data=data.replace(/[[]/g,'').replace(/]/g,'').replace(/"/g,'');
      var folder_files=data.split(",");
      var final_folder_file;
      alert(folder_files[1]);
      var mainFrame=document.getElementById("main-content");
      for(var i =0;i<folder_files.length;i=i+2){
        var child= document.createElement("a");
        child.className="col-md-1 col-lg-1 col-sm-2 col-xs-2";
        var img=getExtImg(folder_files[i],folder_files[i+1]);
        child.innerHTML=img+folder_files[i];      
        mainFrame.appendChild(child);
      }
     
    })
    .fail(function(eror,e1,e2) {
      console.log(e2);
    });  
})();

// alert( window.location.pathname);
var jqxhr = $.get( "/app/webfile_manager/execute/getUser_dirJson", function() {
})
  .done(function(data) {
    console.log(data);
    data=data.replace(/[[]/g,'').replace(/]/g,'').replace(/"/g,'');
    var folder_files=data.split(",");
    var final_folder_file;
    alert(folder_files[1]);
    var mainFrame=document.getElementById("main-content");
    for(var i =0;i<folder_files.length;i=i+2){
      var child= document.createElement("a");
      child.className="col-md-1 col-lg-1 col-sm-2 col-xs-2";
      var img=getExtImg(folder_files[i],folder_files[i+1]);
      child.innerHTML=img+folder_files[i];      
      mainFrame.appendChild(child);
    }
   
  })
  .fail(function(eror,e1,e2) {
    console.log(e2);
  });
