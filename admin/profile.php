<?php include('include/header.php'); ?>

    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="profile-tab" data-toggle="tab" name="profile" href="#profile" role="tab" aria-controls="home" aria-selected="true">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="profile-tab" data-toggle="tab" href="#edit-profile" role="tab" aria-controls="profile" aria-selected="false">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="messages-tab" data-toggle="tab" href="#edit-password" role="tab" aria-controls="messages" aria-selected="false">Edit Password</a>
            </li>
        </ul class="mb-5">
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="profile" role="tabpanel" aria-labelledby="profile-tab" style="margin-bottom: 682px;">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Full Name: </strong>
                            </div>
                            <div class="col-md-10"> <?php echo $_SESSION['fullname']; ?>
                            </div>
                            <div class="col-md-2">
                                <strong>Username: </strong>
                            </div>
                            <div class="col-md-10">
                                <?php echo $_SESSION['username']; ?>
                            </div>
                            <div class="col-md-2">
                                <strong>E-mail: </strong>
                            </div>
                            <div class="col-md-10">
                                <?php echo $_SESSION['email']; ?>
                            </div>
                            <div class="col-md-2">
                                <strong>Gender: </strong>
                            </div>
                            <div class="col-md-10">
                                <?php echo $_SESSION['gender']; ?>
                            </div>
                            <div class="col-md-2">
                                <strong>Created: </strong>
                            </div>
                            <div class="col-md-10">
                                <?php echo $_SESSION['created_date']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="edit-profile" role="tabpanel" aria-labelledby="profile-tab" style="margin-bottom: 371px;">
                <div class="card">
                    <div class="card-body">    
                        <div class="col-md-6">
                            <br>
                            <form id="user_form">
                                <div id="alert_error_message" class="alert alert-danger collapse" role="alert">
                                    Please check in on some of the fields below.
                                </div>
                                <div id="alert_sucess_message" class="alert alert-success collapse" role="alert">
                                    Your profile has been updated successfully.
                                </div>
                                <div class="mb-3">
                                    <label for="fullname">Full Name *</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" maxlength="50"
                                        placeholder="Enter full name">
                                    <div id="fullname_error_message" class="text-danger"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="username">Username *</label>
                                    <input type="text" class="form-control" id="username" name="username" maxlength="50"
                                        placeholder="Enter username" readonly>
                                    <div id="username_error_message" class="text-danger"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="100"
                                        placeholder="Enter email">
                                    <div id="email_error_message" class="text-danger"></div>
                                </div>
                                <div class="mb-3">
                                    <label>Gender *</label>
                                    <select name="gender" id="gender" class="custom-select">
                                        <option value="" hidden>Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                    <div id="gender_error_message" class="text-danger"></div>
                                </div>
                                <hr class="mb-4">
                                <button class="btn btn-primary btn-block" type="submit">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="edit-password" role="tabpanel" aria-labelledby="messages-tab" style="margin-bottom: 457px;">
                <div class="card">
                    <div class="card-body">    
                        <div class="col-md-6">
                            <br>
                            <form id="update_password_form">
                                <div id="update_password_alert_error_message" class="alert alert-danger collapse" role="alert">
                                    Please check in on some of the fields below.
                                </div>
                                <div id="update_password_alert_sucess_message" class="alert alert-success collapse" role="alert">
                                    Password updated successfully!
                                </div>
                                <div class="mb-3">
                                    <label for="password">Current Password *</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current Password">
                                    <div id="current_password_error_message" class="text-danger"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="password">New password *</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" maxlength="50"
                                        placeholder="Enter password">
                                    <div id="new_password_error_message" class="text-danger"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm-password">Confirm Password *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                        maxlength="50" placeholder="Enter confirm password">
                                    <div id="confirm_password_error_message" class="text-danger"></div>
                                </div>
                                <hr class="mb-4">
                                <button class="btn btn-primary btn-block" type="submit">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include('include/footer.php'); ?>

<script>

  $(document).ready(function(){

    getUser();

    $('#user_form').on('submit', function (event) {
        event.preventDefault();
        updateUser();
    });

    $('#update_password_form').on('submit', function (event) {
        event.preventDefault();
        updatePassword();
    });

    var error_fullname = false;
    var error_username = false;
    var error_email = false;
    var error_gender = false;
    var error_current_password = false;
    var error_new_password = false;
    var error_confirm_password = false;

    $("#profile-tab").click(function(){
        location.reload();
    });

    $("#fullname").focusout(function () {
        check_fullname();
    });

    $("#username").focusout(function () {
        check_username();
    });

    $("#email").focusout(function () {
        check_email();
    });

    $("#gender").focusout(function () {
        check_gender();
    });

    $("#current_password").focusout(function() {
        check_current_password();
    });

    $("#new_password").focusout(function() {
        check_new_password();
    });

    $("#confirm_password").focusout(function() {
        check_confirm_password();
    });

    function check_fullname() {
        if ($.trim($('#fullname').val()) == '') {
            $("#fullname_error_message").html("Fullname is a required field.");
            $("#fullname_error_message").show();
            $("#fullname").addClass("is-invalid");
            error_fullname = true;
        } else {
            $("#fullname_error_message").hide();
            $("#fullname").removeClass("is-invalid");
        }
    }

    function check_username() {
        if ($.trim($('#username').val()) == '') {
            $("#username_error_message").html("Username is a required field.");
            $("#username_error_message").show();
            $("#username").addClass("is-invalid");
            error_username = true;
        } else {
            $("#username_error_message").hide();
            $("#username").removeClass("is-invalid");
        }
    }

    function check_email() {
        var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        var email_length = $("#email").val().length;

        if ($.trim($('#email').val()) == '') {
            $("#email_error_message").html("Email is a required field.");
            $("#email_error_message").show();
            $("#email").addClass("is-invalid");
        } else if (!(pattern.test($("#email").val()))) {
            $("#email_error_message").html("Invalid email address");
            $("#email_error_message").show();
            error_email = true;
            $("#email").addClass("is-invalid");
        } else {
            $("#email_error_message").hide();
            $("#email").removeClass("is-invalid");
        }
    }

    function check_gender() {
        if ($.trim($('#gender').val()) == '') {
            $("#gender_error_message").html("Gender is a required field.");
            $("#gender_error_message").show();
            $("#gender").addClass("is-invalid");
            error_gender = true;
        } else {
            $("#gender_error_message").hide();
            $("#gender").removeClass("is-invalid");
        }
    }

    function check_current_password() {
        var current_password_length = $("#current_password").val().length;

        if( $.trim( $('#current_password').val() ) == '' ){
            $("#current_password_error_message").html("Current password is a required field.");
            $("#current_password_error_message").show();
            $("#current_password").addClass("is-invalid");
            error_current_password = true;
        }else if(current_password_length < 8) {
            $("#current_password_error_message").html("At least 8 characters.");
            $("#current_password_error_message").show();
            $("#current_password").addClass("is-invalid");
            error_current_password = true;
        } else {
            $("#current_password_error_message").hide();
            $("#current_password").removeClass("is-invalid");
        }
    }

    function check_new_password() {

        var current_password = $("#current_password").val();
        var new_password = $("#new_password").val();
        var new_password_length = $("#new_password").val().length;

        if( $.trim( $('#new_password').val() ) == '' ){
            $("#new_password_error_message").html("New password is a required field.");
            $("#new_password_error_message").show();
            $("#new_password").addClass("is-invalid");
            error_new_password = true;
        }else if(new_password_length < 8) {
            $("#new_password_error_message").html("At least 8 characters.");
            $("#new_password_error_message").show();
            $("#new_password").addClass("is-invalid");
            error_new_password = true;
        }else if(new_password == current_password) {
            $("#new_password_error_message").html("New password cannot be same as your current password.");
            $("#new_password_error_message").show();
            $("#new_password").addClass("is-invalid");
            error_confirm_password = true;
        }else{
            $("#new_password_error_message").hide();
            $("#new_password").removeClass("is-invalid");
        }
    }

    function check_confirm_password() {

        var new_password = $("#new_password").val();
        var confirm_password = $("#confirm_password").val();

        if( $.trim( $('#confirm_password').val() ) == '' ){
            $("#confirm_password_error_message").html("Confirm password is a required field.");
            $("#confirm_password_error_message").show();
            $("#confirm_password").addClass("is-invalid");
            error_confirm_password = true;
        }else if(new_password !=  confirm_password) {
            $("#confirm_password_error_message").html("Passwords do not match.");
            $("#confirm_password_error_message").show();
            $("#confirm_password").addClass("is-invalid");
            error_confirm_password = true;
        } else {
            $("#confirm_password_error_message").hide();
            $("#confirm_password").removeClass("is-invalid");
        }
    }

    function updateUser() {

        error_fullname = false;
        error_username = false;
        error_email = false;
        error_gender = false;

        check_fullname();
        check_username();
        check_email();
        check_gender();

        if (error_fullname == false && error_username == false && error_email == false && error_gender == false) {

            var dataAction = {
                "action": "update_admin"
            };

            data = $('#user_form').serialize();
            data=data + "&" + $.param(dataAction);
            
            $.ajax({
                type: "POST",
                data: data,
                url: "profile_action.php",
                dataType: "json",
                success: function (data) {
                    if (data.status == 'success') {
                        $("#alert_sucess_message").show();
                        $("#alert_error_message").hide();
                    } else if (data.status=='error') {
                        alert("Oops! Something went wrong.");
                    }
                },
                error: function () {
                    alert("Oops! Something went wrong.");
                }
            });
            return false;
        } else {
            $("#alert_sucess_message").hide();
            $("#alert_error_message").show();
            return false;
        }
    }

    function updatePassword(){

        error_current_password = false;
        error_new_password = false;
        error_confirm_password = false;

        check_current_password();
        check_new_password();
        check_confirm_password();

        if(error_current_password == false && error_new_password == false && error_confirm_password == false) {

            var dataAction = {
                "action": "update_password"
            };

            data=$('#update_password_form').serialize();
            data=data + "&" + $.param(dataAction);

            $.ajax({
                type:"POST",
                data: data, 
                url:"profile_action.php",
                dataType:"json",
                success:function(data){
                    if(data.status) {
                        $("#update_password_alert_sucess_message").show();
                        $("#update_password_alert_error_message").hide();
                        $('#update_password_form')[0].reset();
                    }else if(data.error) {
                        $("#current_password_error_message").html("Passwords do not match.");
                        $("#current_password_error_message").show();
                    }else{
                        alert("Oops! Something went wrong.", "", "error");
                    }
                },error:function(){
                alert("Oops! Something went wrong.");
                }
            });
            return false;
        }else{
            $("#update_password_alert_sucess_message").hide();
            $("#update_password_alert_error_message").show();
            return false;
        }
    }

    function getUser() {
        $.ajax({
            url: "profile_action.php",
            type: "POST",
            data: { action: 'single_fetch' }, 
            success: function (data) {
                var data = JSON.parse(data);
                $('#id').val(data['id']);
                $('#fullname').val(data.fullname);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#gender').val(data.gender);
            }
        });
    }
  });
</script>