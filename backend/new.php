<?php
// Database connection
include 'db_connection.php';

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

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

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true); // Create the directory if it doesn't exist
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $sql = "INSERT INTO images (image) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $newFileName);

            if ($stmt->execute()) {
                echo '<script>alert("Upload successfully!"); window.location.href="welcome.php";</script>';
            } else {
                echo '<script>alert("Database error: Could not insert file information.");</script>';
            }
            $stmt->close();
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

            if ($stmt->execute()) {
                echo '<script>alert("Image deleted successfully!"); window.location.href="welcome.php";</script>';
            } else {
                echo '<script>alert("Database error: Could not delete image record.");</script>';
            }
            $stmt->close();
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
if (!$result) {
    die('Query failed: ' . $conn->error);
}
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
            /* overflow-x: hidden; Prevent horizontal scrollbar */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* Ensure container takes full viewport height */
            padding-bottom: 50px;
            /* Add padding to prevent content from being hidden behind footer */
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
            width: 100%;
            max-width: 100vw;
            /* Ensure the container does not exceed viewport width */
            height: 70vh;
            /* Default height for larger screens */
            position: relative;
        }

        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }

        .swiper-slide img {
            width: 100vw;
            height: 100vh;
            object-fit: contain;
            /* Ensure image covers container */
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

        /* Swiper pagination and navigation styling */
        .swiper-pagination {
            bottom: 10px;
            /* Position at bottom */
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #fff;
            /* Navigation button color */
        }

        @media (min-width: 1024px) {
            .swiper-slide img {
                width: 100vw;
                height: 100vh;
                object-fit: contain;
                /* Ensure image covers container */
            }
        }

        @media (max-width: 1024px) {
            .swiper-container {
                height: 60vh;
                /* Adjust height for tablets and smaller screens */
            }

            .swiper-slide img {
                width: 100vw;
                height: 100vh;
                object-fit: contain;
                /* Ensure image covers container */
            }
        }

        @media (max-width: 768px) {
            .swiper-container {
                height: 50vh;
                /* Further adjust height for smaller tablets */
            }
        }

        @media (max-width: 480px) {
            .swiper-container {
                height: 40vh;
                /* Adjust height for mobile devices */
            }

            button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
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