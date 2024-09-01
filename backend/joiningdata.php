<?php
$register = false;
$invalidno = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';
    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];

    if (strlen($mobile) == 10) {
        $sql = "INSERT INTO `joining` (`name`, `email`, `mobile`, `gender`, `address`, `date`) VALUES ('$name', '$email', '$mobile', '$gender', '$address', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $register = true;

        session_start();
        $_SESSION["success_join"] = "You can now choose your plan!";
        header("location: ../join.php");
        // header("location: ../join.html");
    } else {
        $invalidno = "";

        session_start();
        $_SESSION["join_failed"] = "please! enter a valid number";
        header("location: ../join.php");
        // header("location: ../join.html");
    }


}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <title>Document</title>
</head>

<body>
    <?php include '../join.html'; ?>
    <?php
    if ($register) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong>now you can choose your plan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>';
    }
    ?>
    <?php
    if ($invalidno) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>' . $invalidno . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>';
    }
    ?>
</body>

</html>