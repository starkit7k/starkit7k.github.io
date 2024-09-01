<?php
// $login = false;
// $showerror = false;
// $admin = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "Select * from clients where username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    //ADMIN LOGIN
    if ($username == "admin@gmail.com" && $password == "admin") {
        session_start();
        $admin = true;
        $_SESSION["admin"] = true;
        // $_SESSION["admin_login"] = "You are admin";
        header("location: ../index.php");
        // $login="true";
    }
    //USER LOGIN
    if ($num == 1) {
        $login = true;
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $password;
        $_SESSION["success_log"] = "You are logged in";
        header("location: ../index.php");
       

    } else {

        session_start();
        $_SESSION["login_fail"] = "Invalid username and password";
        header("location: ../index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>
    <?php include '../index.php'; ?>
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
    <!-- shoe messages in login -->
    <?php
    if ($login) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> You are logged in
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>';
    }
    ?>

</body>

</html>