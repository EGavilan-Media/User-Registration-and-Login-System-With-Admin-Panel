<?php

//profile_action.php

include "../connection.php";

session_start();

$output = '';
if(isset($_POST["action"])){

  // Fetch customer
  if($_POST["action"] == "user_fetch"){

    // Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = $_POST['search']['value'];

    // Search
    $searchQuery = " ";
    if($searchValue != ''){
      $searchQuery = " and (id LIKE '%".$searchValue."%'
                            OR fullname LIKE '%".$searchValue."%'
                            OR username LIKE '%".$searchValue."%'
                            OR email LIKE '%".$searchValue."%'
                            OR gender LIKE '%".$searchValue."%'
                            OR status LIKE '%".$searchValue."%' ) ";
    }

    // Total number of records without filtering
    $sqlUser = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_users");
    $records = mysqli_fetch_assoc($sqlUser);
    $totalRecords = $records['allcount'];

    // Total number of records with filtering
    $sqlUser = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_users WHERE 1 ".$searchQuery);
    $records = mysqli_fetch_assoc($sqlUser);
    $totalRecordwithFilter = $records['allcount'];

    // Fetch records
    $userQuery = "SELECT * FROM tbl_users WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ".$row.",".$rowperpage;

    $usersRecords = mysqli_query($conn, $userQuery);
    $data = array();

    while ($row = mysqli_fetch_assoc($usersRecords)){

      $status = '';
      if($row["status"] == "Active")
      {
        $status = '<label class="badge badge-success">Active</label>';
      }

      if($row["status"] == "Inactive")
      {
        $status = '<label class="badge badge-danger">Inactive</label>';
      }

      $data[] = array(
        "id"              =>  $row['id'],
        "fullname"        =>  $row['fullname'],
        "username"        =>  $row['username'],
        "email"           =>  $row['email'],
        "status"          =>  $status,
        "update"          =>  '<button type="button" class="btn btn-danger update_user" id="'.$row['id'].'"><i class="fas fa-edit"></i></button>'
      );
    }

    $response = array(
      "draw"                  => intval($draw),
      "iTotalRecords"         => $totalRecords,
      "iTotalDisplayRecords"  => $totalRecordwithFilter,
      "aaData"                => $data

    );

    echo json_encode($response);

  }

  // Register User
  if($_POST["action"] == "create_user"){

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
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
          'status'        => 'success',            
          'success'		=>	'New user successfully created.'
        );
      }
    }

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $id = $_POST['user_id'];      
    $sql = "SELECT id, fullname, username, email, gender, status FROM tbl_users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    $output = array(
      "id"		            =>	$row[0],
      "fullname"		      =>	$row[1],
      "username"		      => 	$row[2],
      "email"		          => 	$row[3],
      "gender"		        => 	$row[4],
      "status"		        => 	$row[5]
    );

    echo json_encode($output);

  }

  // Single edit fetch
  if($_POST["action"] == "update_user"){

    $id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $password = sha1($_POST['password']);

    if($_POST['password'] == ""){

      $sql = "UPDATE tbl_users SET fullname = '$fullname',
                              username = '$username',
                              email = '$email',
                              gender = '$gender',
                              status = '$status'
                              WHERE id = '$id'";

    } else {

      $sql = "UPDATE tbl_users SET fullname = '$fullname',
                              username = '$username',
                              email = '$email',
                              gender = '$gender',
                              status = '$status',
                              password ='$password'
                              WHERE id = '$id'";
    }

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success',
        'success'		=>	'User has been updated successfully.',
      );

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