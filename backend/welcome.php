<?php
// Database connection
include 'db_connection.php';

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedExts = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($fileExtension, $allowedExts)) {
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = '../upload_image/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $sql = "INSERT INTO images (image) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $newFileName);
            $stmt->execute();
            $stmt->close();
            echo '<script>alert("Upload successfully!"); window.location.href="welcome.php";</script>';
        } else {
            echo '<script>alert("There was an error uploading the file. Please try again.");</script>';
        }
    } else {
        echo '<script>alert("Unsupported file type.");</script>';
    }
}

// Handle image deletion
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql = "SELECT image FROM images WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imageFileName);
    $stmt->fetch();
    $stmt->close();

    if ($imageFileName) {
        $file_path = '../upload_image/' . $imageFileName;
        if (file_exists($file_path) && unlink($file_path)) {
            $sql = "DELETE FROM images WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            echo '<script>alert("Image deleted successfully!"); window.location.href="welcome.php";</script>';
        } else {
            echo '<script>alert("There was an error deleting the file.");</script>';
        }
    } else {
        echo '<script>alert("Image not found.");</script>';
    }
}

// Fetch images from the database
$sql = "SELECT id, image FROM images ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload and Management</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        h1 {
            margin: 20px 0;
        }
        form {
            margin: 20px;
            text-align: center;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .swiper-container {
            width: 100vw; /* Full viewport width */
            height: 90vh; /* 90% of viewport height */
            position: relative;
        }
        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .swiper-slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .delete-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            padding: 5px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
            display: block;
        }
        @media (max-width: 768px) {
            .swiper-container {
                height: 70vh; /* Adjust height for tablets */
            }
        }
        @media (max-width: 480px) {
            .swiper-container {
                height: 60vh; /* Adjust height for mobile devices */
            }
            button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Upload and Manage Images</h1>
    <!-- File upload form -->
    <form action="welcome.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit">Upload Image</button>
    </form>
    <!-- Image Slider -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <img src="../upload_image/<?php echo htmlspecialchars($row['image']); ?>" alt="Image">
                        <div class="delete-icon" onclick="confirmDelete(<?php echo $row['id']; ?>)">✖</div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No images found.</p>
            <?php endif; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <!-- Include Swiper JavaScript -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        var swiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 3000, // Change slide every 3 seconds
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this image?")) {
                window.location.href = 'welcome.php?delete_id=' + id;
            }
        }
    </script>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
