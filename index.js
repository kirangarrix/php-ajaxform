$(document).ready(function () {
  $("#myForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      type: "POST",
      url: "signup.php",
      data: new FormData(this),
      dataType: "json",
      contentType: false,
      cache: false,
      processData: false,
      success:function(response) {
        $(".form-message").css("display", "block");
        
        if (response.status == 1) {
          $("#myForm")[0].reset();
          $(".form-message").html("<p>" + response.message + "</p>");
        } else {
          $(".form-message").css("display", "block");
          $(".form-message").html("<p>" + response.message + "</p>");
        }
      },
    });
  });

  $("#file").change(function(){
      var file=this.files[0];
      var fileType=file.type;
      var match=['image/jpeg','image/jpg','image/png'];

      if(!((fileType == match[0]) || (fileType == match[1]) || (fileType==match[2]) )){
          alert("sorry only jpg png jpeg are allowed");
          $("#file").val('');
          return false;
      }
  })

});
