<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymmembers";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
   //  echo"success";
//  }
//  else{
    die("Connection failed!" .mysqli_connect_errno());
}

?>