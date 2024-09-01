<?php
include 'db_connection.php';

if (isset($_GET['file'])) {
    $file = 'uploaded_images/' . $_GET['file'];
    if (file_exists($file)) {
        unlink($file);
    }
}

header('Location: index.php');
exit();
?>
