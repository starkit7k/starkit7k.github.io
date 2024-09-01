<?php
$showalert = false;
$showerror = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];


    $existSql = "SELECT * FROM `clients` WHERE username = '$username'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        // $showerror = "username already exists";
        session_start();
        $_SESSION["UserAlreadyExist"] = "username already exists";
        header("location: ../index.php");

    } else {
        if (($password == $cpassword)) {
            $sql = "INSERT INTO `clients` (`username`, `password`, `date`) VALUES ('$username', '$password', current_timestamp());";
            // run karne ke liye
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showalert = true;
                session_start();
                $_SESSION["signup_complete"] = "Your account is now created and you can login.";
                header("location: ../index.php");
            }
        } else {
            // $showerror = "Passwords do not match";
            session_start();
            $_SESSION["signup_pwd"] = "Passwords do not match";
            header("location: ../index.php");
        }
    }
    // require_once '../index.php';

}




//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//      include 'db_connection.php';
//      $username = $_POST["username"];
//      $password = $_POST["password"];
//      $cpassword = $_POST["cpassword"];

//      $exist = false;
//      if (($password == $cpassword) && $exist == false) {
//          $sql = "INSERT INTO `clients` (`username`, `password`, `date`) VALUES ('$username', '$password', current_timestamp());";
//          // run karne ke liye
//          $result = mysqli_query($conn, $sql);
//          if ($result) {
//              $showalert = true;
//          }
//      }
//      else{
//          $showerror = "Passwords do not match";
//      }
//  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>
    <?php include '../index.php' ?>
    <?php
    if ($showalert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your account is now created and you can login.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>';
    }
    ?>

    <!-- showerror -->
    <?php
    if ($showerror) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>' . $showerror . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>';
    }
    ?>

</body>

</html>