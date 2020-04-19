<?php

//profile_action.php

include "connection.php";

session_start();

$output = '';
if(isset($_POST["action"])){

  // User Register
    if($_POST["action"] == "register_user"){

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $status = "Active";
    $password = sha1($_POST['password']);

    // Check if username already exists.
    $sql = "SELECT * FROM tbl_users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $checkrows = mysqli_num_rows($result);

    if($checkrows > 0) {
      $output = array(
          'status'		    =>	'error',
      );
    } else {
      $sql = "INSERT INTO tbl_users (fullname, 
                                      username, 
                                      email,
                                      gender,
                                      status,
                                      password,
                                      created_date) 
                              VALUES('$fullname', 
                                    '$username',
                                    '$email',
                                    '$gender',
                                    '$status',
                                    '$password',
                                    NOW())";
      if(mysqli_query($conn, $sql)){
          $output = array(
              'status'        => 'success'
          );
      }
    }

    echo json_encode($output);

  }

  // User login
  if($_POST["action"] == "login_user"){

    $username = $_POST['username'];
    $password = sha1($_POST['password']);	
  
    $sql = "SELECT * FROM tbl_users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    // Check if username is Inactive
    if($row[5] == "Inactive")
    {
      $output = array(
        'status'        => 'inactive',            
        'error'	      	=>	'This username has been set as inactive. Please contact your administrator.'
      );
    } else {  
      if(mysqli_num_rows($result) > 0){    
        $_SESSION['user_id']       = $row[0];
        $_SESSION['fullname']      = $row[1];
        $_SESSION['username']      = $row[2];
        $_SESSION['email']         = $row[3];
        $_SESSION['gender']        = $row[4];
        $_SESSION['created_date']  = $row[7];

        $output = array(
          'status'        => 'success'
        );
      } else {
        $output = array(
          'status'        => 'error',            
          'error'	      	=> 'Incorrect username or password.'
        );
      }
    }

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $id = $_SESSION['user_id'];
      
    $sql = "SELECT id, fullname, username, email, gender FROM tbl_users WHERE id = '$id'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_row($result);

    $output = array(
      "id"		            =>	$row[0],
      "fullname"		      =>	$row[1],
      "username"		      => 	$row[2],
      "email"		          => 	$row[3],
      "gender"		        => 	$row[4]
    );

    echo json_encode($output);

  }

  // Single edit fetch
  if($_POST["action"] == "update_user"){

    $id = $_SESSION['user_id'];

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $sql = "UPDATE tbl_users SET fullname = '$fullname',
                              username = '$username',
                              email = '$email',
                              gender = '$gender'
                              WHERE id = '$id'";

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success'
      );

      $_SESSION['fullname']      = $fullname;
      $_SESSION['username']      = $username;
      $_SESSION['email']         = $email;
      $_SESSION['gender']        = $gender;

    }else{
      $output = array(
        'status'        => 'error'
      );
    }

    echo json_encode($output);

  }

  // Update User Password
  if($_POST["action"] == "update_password"){

    $id = $_SESSION['user_id'];

    $password = sha1($_POST['current_password']);
    $new_password = sha1($_POST['new_password']);

    $sql = "SELECT * FROM tbl_users WHERE password = '$password' AND id = '$id'";
    $result = mysqli_query($conn, $sql);
    $checkrows = mysqli_num_rows($result);

    if($checkrows > 0) {

        $sql = "UPDATE tbl_users SET password = '$new_password' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);

        if($result > 0)	{
          $output = array(
            'status'	=>	'success'
          );                
          echo json_encode($output);
        } 

    } else {
        $output = array(
            'error'		     =>	'true'
        );
        echo json_encode($output); 
    }
  }
}

?>