var UserService = {
  init: function(){
    var token = localStorage.getItem("token");
    if (token){
      window.location.replace("index.html");
    }
    $('#login-form').validate({
      submitHandler: function(form) {
        var entity = Object.fromEntries((new FormData(form)).entries());
        UserService.login(entity);
      }
    });
    $('#register-form').validate({
      submitHandler: function(form) {
        var user = Object.fromEntries((new FormData(form)).entries());
        UserService.register(user);
        toastr.info("Adding ...");
      }
    });
  },
  profile: function() {
    $("#change-password-form").validate({
      submitHandler: function (form) {
        var data = Object.fromEntries(new FormData(form).entries());
        UserService.update_password(data);
      },
    });
    $("#update-user-form").validate({
      submitHandler: function (form) {
        var data = Object.fromEntries(new FormData(form).entries());
        toastr.info("Updating ...");
        UserService.update(data);
      },
    });

    UserService.get();
  },
  login: function(entity){
    $.ajax({
      url: 'rest/login',
      type: 'POST',
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
        console.log(result);
        localStorage.setItem("token", result.token);
        window.location.replace("index.html");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    });
  },

  logout: function(){
    localStorage.clear();
    window.location.replace("login.html");
  },
  showRegisterForm: function(){
    $("#login-form-container").addClass("hidden");
    $("#register-form-container").removeClass("hidden");

  },
  showLoginForm: function(){
    $("#register-form-container").addClass("hidden");
    $("#login-form-container").removeClass("hidden");

  },
  showPasswordForm: function(){
    $("#change-password-form-container").removeClass("hidden");
    $("#password-edit-button").addClass("hidden");
    $("#password-edit-cancel").removeClass("hidden");

  },
  cancelPasswordChange: function(){
    $("#change-password-form-container").addClass("hidden");
    $("#password-edit-button").removeClass("hidden");
    $("#password-edit-cancel").addClass("hidden");
    $('#change-password-form').trigger('reset');
    $('#change-password-form').validate().resetForm();

  },
  parseJWT: function(token){
    var base64Url = token.split('.')[1];
      var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
          return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
      return JSON.parse(jsonPayload);
  },
  register: function(user){
    $.ajax({
      url: 'rest/register',
      type: 'POST',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      data: JSON.stringify(user),
      contentType: "application/json",
      dataType: "json",
      success: function() {
        toastr.success("Added. Check your email for activation link!");
        $("#register-form-container").addClass("hidden");
        $("#login-form-container").removeClass("hidden");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    });
  },
  update_password: function (data) {
    $.ajax({
      url: "rest/password",
      type: "PUT",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Authorization", localStorage.getItem("token"));
      },
      data: JSON.stringify(data),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        toastr.success("Password changed!");
        $("#change-password-form-container").addClass("hidden");
        $("#password-edit-button").removeClass("hidden");
        $("#password-edit-cancel").addClass("hidden");
        $('#change-password-form').trigger('reset');
        $('#change-password-form').validate().resetForm();
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      },
    });
  },
  update: function (data) {
    $.ajax({
      url: "rest/user",
      type: "PUT",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Authorization", localStorage.getItem("token"));
      },
      data: JSON.stringify(data),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        toastr.success("Profile updated!");
        $('#profile-name').text(data.user_name + " " + data.user_surname);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      },
    });
  },
  get: function(){

    $.ajax({
      url: 'rest/user',
      type: 'GET',
      beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
      },
      success: function(data){
      $('#update-user-form input[name="user_name"]').val(data.user_name);
      $('#update-user-form input[name="user_surname"]').val(data.user_surname);
      $('#update-user-form input[name="phone"]').val(data.phone);
      $('#update-user-form input[name="city"]').val(data.city);
      $('#update-user-form input[name="email"]').val(data.email);
      $('#profile-name').text(data.user_name + " " + data.user_surname);
    }
    })
  },
}
