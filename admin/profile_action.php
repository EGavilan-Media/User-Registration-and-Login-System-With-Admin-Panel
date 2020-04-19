<?php

//profile_action.php

include "../connection.php";

session_start();

$output = '';
if(isset($_POST["action"])){

  // Admin login
  if($_POST["action"] == "login_admin"){

    $username = $_POST['username'];
    $password = sha1($_POST['password']);	
  
    $sql = "SELECT * FROM tbl_admin WHERE username = '$username' AND password = '$password'";
  
    $result = mysqli_query($conn, $sql);
  
    $row = mysqli_fetch_row($result);
  
    if(mysqli_num_rows($result) > 0){
  
      $_SESSION['user_id']       = $row[0];
      $_SESSION['fullname']      = $row[1];
      $_SESSION['username']      = $row[2];
      $_SESSION['email']         = $row[3];
      $_SESSION['gender']        = $row[4];
      $_SESSION['created_date']  = $row[7];
      
      echo 1;
      
    }else{
      echo 0;		
    }
    
  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $id = $_SESSION['user_id'];
      
    $sql = "SELECT id, fullname, username, email, gender FROM tbl_admin WHERE id = '$id'";

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
  if($_POST["action"] == "update_admin"){

    $id = $_SESSION['user_id'];

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $sql = "UPDATE tbl_admin SET fullname = '$fullname',
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

  if($_POST["action"] == "update_password"){

    $id = $_SESSION['user_id'];

    $password = sha1($_POST['current_password']);
    $new_password = sha1($_POST['new_password']);

    $sql = "SELECT * FROM tbl_admin WHERE password = '$password' AND id = '$id'";

    $result = mysqli_query($conn, $sql);

    $checkrows = mysqli_num_rows($result);

    if($checkrows > 0) {

        $sql = "UPDATE tbl_admin SET password = '$new_password' WHERE id = '$id'";

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