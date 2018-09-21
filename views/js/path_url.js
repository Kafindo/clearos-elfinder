function myfunc(data){
    //alert(data);
    Nagivation(data);
}

function Nagivation(path){
    var urls=path.split("/");    
    var main_frame=document.getElementById("main-content");
    main_frame.innerHTML="";
    //main_frame.innerHTML="";
    var url="racine";
    for (let i = 1; i < urls.length; i++) {
      url+=("/"+urls[i]);      
    }
    var data= $.ajax({type: "GET", url:"/app/webfile_manager/executeDirCmd/open_dir2Json/"+url, async: false}).responseText;
    data=data.replace(/[[]/g,'').replace(/]/g,'').replace(/"/g,'');
      var folder_files=data.split(",");
      var mainFrame=document.getElementById("main-content");
      for(var i =0;i<folder_files.length;i=i+2){
        var child= document.createElement("a");
        child.setAttribute("onclick","navigate('"+folder_files[i]+"')")
        child.className="col-md-1 col-lg-1 col-sm-2 col-xs-2";
        var img=getExtImg(folder_files[i],folder_files[i+1]);
        child.innerHTML=img+folder_files[i];      
        mainFrame.appendChild(child);
      }  
  
}