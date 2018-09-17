// alert( window.location.pathname);
var jqxhr = $.get( "/app/webfile_manager/execute/getUser_dirJson", function() {
  })
    .done(function(data) {
      console.log(data);

      data=data.replace(/"/g,'').replace('[','').replace(']','');
      var folder_files=data.split(",");
      console.log(folder_files);
      var mainFraime=document.getElementById("main-content");
      folder_files.forEach(element => {
       // console.log(element);
       var child= document.createElement("a");
        child.className="col-md-1 col-lg-1 col-sm-2 col-xs-2";
        child.innerHTML=element;
        mainFraime.appendChild(child);
      });
    })
    .fail(function(eror,e1,e2) {
      console.log(e2);
    });