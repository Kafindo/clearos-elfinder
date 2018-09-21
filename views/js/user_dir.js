(function(){
  var jqxhr = $.get( "/app/webfile_manager/execute/getActualUserPath", function() {
  })
    .done(function(data) {
      var uri=document.getElementById("navigation_url");
      data=data.replace(/[[]/g,'').replace(/]/g,'').replace(/"/g,'').replace(/\\/g,'');
      var url=data.split('/');
      url[0]="/";
      var final_data=regularise(url);
      for (let i = 0; i < final_data.length; i=i+2) {
        var link=document.createElement("a");
        link.setAttribute("onclick","myfunc('"+final_data[i+1]+"')");
        var separator=document.createElement("span");
        separator.innerText=" > ";
        link.innerText=final_data[i];
        uri.appendChild(link);
        uri.appendChild(separator);
      }     
    })
    .fail(function(eror,e1,e2) {
      console.log(eror);
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
    var mainFrame=document.getElementById("main-content");
    for(var i =0;i<folder_files.length;i=i+2){
      var child= document.createElement("a");
      child.setAttribute("ondblclick","NavigationFolder('"+folder_files[i]+"')");
      child.className="col-md-1 col-lg-1 col-sm-2 col-xs-2";
      var img=getExtImg(folder_files[i],folder_files[i+1]);
      child.innerHTML=img+folder_files[i];      
      mainFrame.appendChild(child);
    }
   
  })
  .fail(function(eror,e1,e2) {
    console.log(e2);
  });
function regularise(data){
  var areturner=[];
  var concat="/";
  areturner.push("/");
  areturner.push(concat);
  for (let i = 1; i < data.length; i++) {
    areturner.push(data[i]);
    concat=concat+data[i]+"/";
    areturner.push(concat); 
  }
  return areturner;
}
function NavigationFolder(url){
  var uri=document.getElementById("navigation_url");
  var text=uri.innerText;
  alert(text);
}