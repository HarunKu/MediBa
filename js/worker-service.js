var WorkerService = {

  init: function(){
    $('#addWorkerForm').validate({
      submitHandler: function(form) {
        var worker = Object.fromEntries((new FormData(form)).entries());
        WorkerService.add(worker);
      }
    });
    $('#update-worker-form').validate({
      submitHandler: function(form) {
        var worker = Object.fromEntries((new FormData(form)).entries());
        WorkerService.update(worker);
      }
    });

    WorkerService.list();
  },

  list: function(){
    var queryParam = this.getQueryParam('q');
    var urlajax = "rest/worker";
    if(queryParam){
      urlajax="rest/job/" + queryParam + "/worker";
    };
    $.ajax({
       url: urlajax,
       type: "GET",
       beforeSend: function(xhr){
         xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
       },
       success: function(data) {
         $("#worker-list").html("");
         var html = "";
         for(let i = 0; i < data.length; i++){
           html += `
           <div class="col-lg-4">
             <div class="card" style="margin-top:30px;">
               <img class="rounded-circle" src="assets/test.png" alt="Card image cap">
               <div class="card-body text-center">
                 <h5 class="card-title">`+ data[i].worker_name +`</h5>
                 <h6 class="card-title"><i class="bi bi-geo-alt-fill text-danger"></i> `+ data[i].worker_city +`</h6>
                 <p class="card-text"><i>`+ data[i].job_name +`</i> - `+ data[i].description +`</p>
                 <ul class="list-group list-group-flush">
                  <li class="list-group-item">`+ data[i].worker_email +`</li>
                  <li class="list-group-item">`+ data[i].worker_phone_number +`</li>
                  <li class="list-group-item">`+ data[i].worker_address +`</li>
                  <li class="list-group-item">`+ data[i].avg +` <i class="bi bi-star-fill text-warning"></i></li>
                 </ul>
                 <div class="btn-group my-2" role="group">
                   <button type="button" class="btn btn-outline-dark worker-button" onclick="ReviewService.list_by_worker_id(`+data[i].id+`)">Reviews</button>
                   <button  type="button" class="btn btn-outline-dark admin-panel worker-button-`+data[i].id+` hidden" onclick="WorkerService.get(`+data[i].id+`)">Edit</button>
                   <button  type="button" class="btn btn-danger admin-panel worker-button-`+data[i].id+` hidden" onclick="WorkerService.delete(`+data[i].id+`)">Delete</button>
                 </div>
                 <div class="created-by hidden">
                   <p class="mt-1"> Created by: `+ data[i].user +` </p>
                 </div>
               </div>
             </div>
           </div>
           `;
         }
         $("#worker-list").html(html);

         let userInfo = UserService.parseJWT(localStorage.getItem("token"));
         if(userInfo.r == "ADMIN"){
          $('.admin-panel').removeClass("hidden");
          $('.created-by').removeClass("hidden");
         }else{
          for(let i = 0; i < data.length; i++){
            if(data[i].user_created==userInfo.id){

              $('.worker-button-'+data[i].id).removeClass("hidden");
            }
          }
        }
       },
       error: function(XMLHttpRequest, textStatus, errorThrown) {
         toastr.error(XMLHttpRequest.responseJSON.message);
         UserService.logout();
       }
    });
  },

  get: function(worker_id){

    $('.worker-button').attr('disabled', true);
    $.ajax({
      url: 'rest/worker/'+worker_id,
      type: 'GET',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      success: function(data){
      $('#update-worker-form input[name="worker_name"]').val(data.worker_name);
      $('#update-worker-form input[name="id"]').val(data.id);
      $('#update-worker-form input[name="worker_city"]').val(data.worker_city);
      $('#update-worker-form input[name="worker_phone_number"]').val(data.worker_phone_number);
      $('#update-worker-form input[name="worker_address"]').val(data.worker_address);
      $('#update-worker-form input[name="worker_email"]').val(data.worker_email);
      $('#update-worker-form textarea[name="description"]').val(data.description);
      $("#updateWorkerModal").modal("show");
      $('.worker-button').attr('disabled', false);
    }
    })
  },

  add: function(worker){
    $.ajax({
      url: 'rest/worker',
      type: 'POST',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      data: JSON.stringify(worker),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
          $("#worker-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
          WorkerService.list(); 
          $("#workerModal").modal("hide");
          toastr.success("Added new doctor.");
          $('#addWorkerForm').trigger('reset');
          $('#addWorkerForm').validate().resetForm();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    });
  },

  update: function(worker){
    $.ajax({
      url: 'rest/worker/'+worker.id,
      type: 'PUT',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      data: JSON.stringify(worker),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
          $("#worker-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
          WorkerService.list(); 
          $("#updateWorkerModal").modal("hide");
          toastr.success("Successfully updated doctor");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    });
  },

  

  delete: function(id){
    $('.worker-button').attr('disabled', true);
    $.ajax({
      url: 'rest/worker/'+id,
      type: 'DELETE',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      success: function(result) {
          $("#worker-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
          WorkerService.list();
          toastr.success("Deleted successfully.");
      }
    });
  },

  getQueryParam: function(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
  }



}
