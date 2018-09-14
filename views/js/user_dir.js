// alert( window.location.pathname);
var jqxhr = $.get( "/app/webfile_manager/execute/user_dir", function() {
  })
    .done(function(data) {
      alert(data);
    })
    .fail(function(eror,e1,e2) {
     console.log(e2);
    });