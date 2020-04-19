<?php
include "../connection.php";

  $output = '';
  if(isset($_POST["action"])){

    if($_POST["action"] == "add_info"){

      $sqlUser = "SELECT count(id) FROM tbl_users";
      $sqlInactiveUser = "SELECT count(id) FROM tbl_users WHERE status = 'Inactive'";
      $sqlActiveUser = "SELECT count(id) FROM tbl_users WHERE status = 'Active'";
      $sqlAdmin = "SELECT count(id) FROM tbl_admin";

      $userResult = mysqli_query($conn, $sqlUser);
      $inactiveUserResult = mysqli_query($conn, $sqlInactiveUser);    
      $activeUserResult = mysqli_query($conn, $sqlActiveUser);    
      $adminResult = mysqli_query($conn, $sqlAdmin);

      $row1 = mysqli_fetch_row($userResult);
      $row2 = mysqli_fetch_row($inactiveUserResult);
      $row3 = mysqli_fetch_row($activeUserResult);
      $row4 = mysqli_fetch_row($adminResult);

      $output=array(
        'total_users'               => $row1[0],
        'total_inactive_users'      => $row2[0],
        'total_active_users'        => $row3[0],
        'total_admins'              => $row4[0],
      );
      echo json_encode($output);
    }

    if($_POST["action"] == "user_info"){

      $sql = "SELECT DATE_FORMAT(created_date, '%Y-%m'), COUNT(DATE_FORMAT(created_date, '%Y-%m'))
      FROM tbl_users GROUP BY DATE_FORMAT(created_date, '%m-%Y')";
      $result = mysqli_query($conn, $sql);
    
      while ($row = mysqli_fetch_row($result)){
        $valuesX[] = $row[0];
        $valuesY[] = $row[1];
      }

      $output=array(
        'date'        => $valuesX,
        'users'       => $valuesY,
      );
      echo json_encode($output);
    }

    if($_POST["action"] == "gender_info"){

      $sqlTotalMale = "SELECT count(gender) FROM tbl_users WHERE gender = 'Male'";
      $sqlTotalFemale = "SELECT count(gender) FROM tbl_users WHERE gender = 'Female'";

      $totalMaleResult = mysqli_query($conn, $sqlTotalMale);    
      $totalFemaleResult = mysqli_query($conn, $sqlTotalFemale);    

      $row1 = mysqli_fetch_row($totalMaleResult);
      $row2 = mysqli_fetch_row($totalFemaleResult);

      $output=array(
        'total_male'        => $row1[0],
        'total_female'      => $row2[0],
      );
      echo json_encode($output);
    }

  }
?>