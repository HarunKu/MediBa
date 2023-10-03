var ReviewService = {

  add: function(review){
    $.ajax({
      url: 'rest/review',
      type: 'POST',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      data: JSON.stringify(review),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
        // append to the list
        $("#worker-reviews").append(`<div class="list-group-item worker-review-`+result.id+`">
          <button class="btn btn-danger btn-sm float-end " onclick="ReviewService.delete(`+result.id+`)">Delete</button>
          <p class="list-group-item-text">`+'Your review | Right now | Rating: '+result.review_grade+' | Comment: '+result.review_comment+`</p>
        </div>`);
        toastr.success("Added !");
      }
    });
  },

  list_by_worker_id: function(id){
    $("#worker-reviews").html('loading ...');

    $.ajax({
       url: "rest/worker/"+id+"/review",
       type: "GET",
       beforeSend: function(xhr){
         xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
       },
       success: function(data) {
         var html = "";
         for(let i = 0; i < data.length; i++){
           html += `<div class="list-group-item worker-review-`+data[i].id+`">
             <button class="btn btn-danger btn-sm float-end admin-panel worker-review-`+data[i].id+` hidden" onclick="ReviewService.delete(`+data[i].id+`)">Delete</button>

             <p class="list-group-item-text">`+data[i].user+' | '+data[i].posted+' | Rating: '+data[i].review_grade+' | Comment: '+data[i].review_comment+`</p>
           </div>`;
         }
         $("#worker-reviews").html(html);

         let userInfo = UserService.parseJWT(localStorage.getItem("token"));

         if(userInfo.r == "ADMIN"){
           $('.admin-panel').removeClass("hidden");
         }else{
           for(let i = 0; i < data.length; i++){
             if(data[i].user_id==userInfo.id){

               $('.worker-review-'+data[i].id).removeClass("hidden");
             }
           }
         }

       },
       error: function(XMLHttpRequest, textStatus, errorThrown) {
         toastr.error(XMLHttpRequest.responseJSON.message);
         UserService.logout();
       }
    });


    // note id populate and form validation
    $('#add-review-form input[name="worker_id"]').val(id);


    $('#add-review-form').validate({
      submitHandler: function(form) {
        var entity = Object.fromEntries((new FormData(form)).entries());
        ReviewService.add(entity);
        $('#add-review-form input[name="review_grade"]').val("");
        $('#add-review-form input[name="review_comment"]').val("");

        toastr.info("Adding ...");
      }
    });
    $("#reviewModal").modal('show');
  },

  delete: function(id){
    var old_html = $("#worker_reviews").html();
    $('.worker-review-'+id).remove();
    toastr.info("Deleting in background ...");
    $.ajax({
      url: 'rest/review/'+id,
      type: 'DELETE',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      success: function(result) {
        toastr.success("Deleted !");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
        $("#worker-reviews").html(old_html);
        //alert("Status: " + textStatus); alert("Error: " + errorThrown);
      }
    });
  },



}
