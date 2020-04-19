<?php include('include/header.php'); ?>

  <div class="container">
    <!-- Users DataTable -->
    <div class="card mb-3" style="margin-top:30px">
      <div class="card-header">
        <div class="row">
          <div class="col-md-9 font-weight-bold"><i class="fas fa-users"></i> User List</div>
          <div class="col-md-3" align="right">
            <button type="button" id="add_button" class="btn btn-primary btn-sm">Create New User</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <span id="sucess_message"></span>
          <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Update</th>
              </tr>
            </thead>
            <tfoot>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <!-- End Users DataTable -->

    <!-- User Modal -->
    <div class="modal fade" id="formModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="modal_title"></h4>
            <button class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="user_form">
              <div id="alert_error_message" class="alert alert-danger collapse" role="alert">
                  Please check in on some of the fields below.
              </div>
              <div class="mb-3">
                  <label for="fullname">Full Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="fullname" name="fullname" maxlength="50"
                      placeholder="Enter full name">
                  <div id="fullname_error_message" class="text-danger"></div>
              </div>
              <div class="mb-3">
                  <label for="username">Username <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="username" name="username" maxlength="50"
                      placeholder="Enter username">
                  <div id="username_error_message" class="text-danger"></div>
              </div>
              <div class="mb-3">
                  <label for="email">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" maxlength="100"
                      placeholder="Enter email">
                  <div id="email_error_message" class="text-danger"></div>
              </div>
              <div class="mb-3">
                  <label>Gender <span class="text-danger">*</span></label>
                  <select name="gender" id="gender" class="custom-select">
                      <option value="" hidden>Gender</option>
                      <option>Male</option>
                      <option>Female</option>
                  </select>
                  <div id="gender_error_message" class="text-danger"></div>
              </div>
              <div class="mb-3">
                  <label>Status <span class="text-danger">*</span></label>
                  <select name="status" id="status" class="custom-select">
                      <option value="" hidden>Status</option>
                      <option>Active</option>
                      <option>Inactive</option>
                  </select>
                  <div id="status_error_message" class="text-danger"></div>
              </div>
              <hr class="mb-4">
              <div class="mb-3">
                  <label for="password">Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" id="password" name="password" maxlength="50"
                      placeholder="Enter password">
                  <div id="password_error_message" class="text-danger"></div>
              </div>
              <div class="mb-3">
                  <label for="confirm-password">Confirm Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                      maxlength="50" placeholder="Enter confirm password">
                  <div id="confirm_password_error_message" class="text-danger"></div>
              </div>
              <hr class="mb-4">
              <div class="modal-footer">
                <input type="hidden" name="user_id" id="user_id"/>
                <input type="hidden" name="action" id="action" value="Add"/>
                <input type="submit" name="button_action" id="button_action" class="btn btn-primary" value="Add"/>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End User Modal -->
  </div>

<?php include('include/footer.php'); ?>

<script>

  $(document).ready(function(){
    var datatable = $('#userTable').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          url:'user_action.php',
          type: 'POST',
          data: {action:'user_fetch'}
      },
      'columns': [
          { data: 'id' },
          { data: 'fullname'},
          { data: 'username'},
          { data: 'email'},
          { data: 'status'},
          { data: 'update',"orderable":false}
      ]
    });

    function clear_field()
    {
      $("#alert_error_message").hide();
      $('#user_form')[0].reset();
      $("#fullname_error_message").hide();
      $("#fullname").removeClass("is-invalid");
      $("#username_error_message").hide();
      $("#username").removeClass("is-invalid");
      $("#email_error_message").hide();
      $("#email").removeClass("is-invalid");
      $("#gender_error_message").hide();
      $("#gender").removeClass("is-invalid");
      $("#status_error_message").hide();
      $("#status").removeClass("is-invalid");
      $("#password_error_message").hide();
      $("#password").removeClass("is-invalid");
      $("#confirm_password_error_message").hide();
      $("#confirm_password").removeClass("is-invalid");
      document.getElementById("username").readOnly = false;
    }

    $('#add_button').click(function(){
      $('#modal_title').text('Create New User');
      $('#button_action').val('Save');
      $('#action').val('create_user');
      $('#formModal').modal('show');
      clear_field();
      $('#sucess_message').html('');
    });

    var error_fullname = false;
    var error_username = false;
    var error_email = false;
    var error_gender = false;
    var error_status = false;
    var error_password = false;
    var error_confirm_password = false;

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

    $("#status").focusout(function () {
        check_status();
    });

    $("#password").focusout(function () {
        check_password();
    });

    $("#confirm_password").focusout(function () {
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

    function check_status() {
        if ($.trim($('#status').val()) == '') {
            $("#status_error_message").html("Status is a required field.");
            $("#status_error_message").show();
            $("#status").addClass("is-invalid");
            error_status = true;
        } else {
            $("#status_error_message").hide();
            $("#status").removeClass("is-invalid");
        }
    }

    function check_password() {
        var password_length = $("#password").val().length;

        if ($.trim($('#password').val()) == '') {
            $("#password_error_message").hide();
            $("#password").removeClass("is-invalid");
        } else if (password_length < 8) {
            $("#password_error_message").html("Please enter at least 8 characters!");
            $("#password_error_message").show();
            error_password = true;
            $("#password").addClass("is-invalid");
        } else {
            $("#password_error_message").hide();
            $("#password").removeClass("is-invalid");
        }
    }

    function check_confirm_password() {
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();

        if ($.trim($('#confirm_password').val()) == '') {
            $("#confirm_password_error_message").hide();
            $("#confirm_password").removeClass("is-invalid");
        } else if (password != confirm_password) {
            $("#confirm_password_error_message").html("Passwords do not match!");
            $("#confirm_password_error_message").show();
            error_confirm_password = true;
            $("#confirm_password").addClass("is-invalid");
        } else {
            $("#confirm_password_error_message").hide();
            $("#confirm_password").removeClass("is-invalid");
        }
    }

    $('#user_form').on('submit', function(event){
      event.preventDefault();

      error_fullname = false;
      error_username = false;
      error_email = false;
      error_gender = false;
      error_status = false;
      error_password = false;
      error_confirm_password = false;

      check_fullname();
      check_username();
      check_email();
      check_gender();
      check_status();
      check_password();
      check_confirm_password();

      if (error_fullname == false && error_username == false && error_email == false && error_gender == false && error_status == false && error_password == false && error_confirm_password == false) {

        data = $('#user_form').serialize();

        $.ajax({
          type: "POST",
          data: data,
          url: "user_action.php",
          dataType: "json",
          success: function (data) {
              if (data.status == 'success') {
                $('#sucess_message').html('<div class="alert alert-success">'+data.success+'</div>');
                $("#alert_error_message").hide();
                clear_field();
                $('#formModal').modal('hide');
                datatable.ajax.reload();
              } else if (data.status=='error') {
                $("#username_error_message").html("Username already exists");
                $("#username_error_message").show();
              }
          },
          error: function () {
              alert("Oops! Something went wrong.");
          }
        });
      } else {
        $("#alert_error_message").show();
      }
    });

    $(document).on('click', '.update_user', function(){
      user_id = $(this).attr('id');
      clear_field();

      $.ajax({
        url:"user_action.php",
        method:"POST",
        data:{action:'single_fetch', user_id:user_id},
        success:function(data){
          var data = JSON.parse(data);
          $('#formModal').modal('show');
          $('#modal_title').text('Update User Information');
          $('#button_action').val('Update');
          $('#action').val('update_user');
          $('#user_id').val(data['id']);
          $('#fullname').val(data.fullname);
          document.getElementById("username").readOnly = true;
          $('#username').val(data.username);
          $('#email').val(data.email);
          $('#gender').val(data.gender);
          $('#status').val(data.status);
        }
      });
    });
  });
</script>