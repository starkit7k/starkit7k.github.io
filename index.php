<?php
//from login.php(agar user login nahi hai tro usse login page par bhejna)
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
?>

<?php

if (isset($_SESSION["admin"]) && $_SESSION["admin"] == true) {
    $admin = true;
} else {
    $admin = false;
}
?>


<?php
include 'backend/db_connection.php';

// Handle file upload
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
        $uploadFileDir = 'upload_image/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true); // Create the directory if it doesn't exist
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $sql = "INSERT INTO images (image) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $newFileName);

            if ($stmt->execute()) {
                echo '<script>alert("Upload successfully!"); window.location.href="index.php";</script>';
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
        $file_path = 'upload_image/' . $imageFileName;
        if (file_exists($file_path) && unlink($file_path)) {
            $sql = "DELETE FROM images WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo '<script>alert("Image deleted successfully!"); window.location.href="index.php";</script>';
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
    <!-- bootstrap-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    <!-- fonts-->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/infinite-justice" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="image/logo1.jpeg" type="image/x-icon">
    <!--link-->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="script.js">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="backend/log.php">
    <!-- bootstrap responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link rel="stylesheet" href="contact/contact.html"> -->
    <!-- <link rel="stylesheet" href="join/join.html"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
    <!-- Unicon-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <title>Emarsun Unisex Gym <?php echo $_SESSION['username'] ?></title>
    <!-- php -->
    <!-- <link rel="stylesheet" href="PHP files/main.php"> -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            /* overflow-x: hidden; Prevent horizontal scrollbar */
        }

        .container1 {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100vw;
            padding-bottom: 50px;

            margin-top: 10px;
        }

        .container h1 {
            margin: 20px 0;
        }

        .container form {
            margin: 20px;
            text-align: center;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        .upl {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .upl:hover {
            background-color: #0056b3;
        }

        .swiper-container {
            width: 100%;
            max-width: 100vw;
            /* Ensure the container does not exceed viewport width */
            height: 100vh;
            /* Default height for larger screens */
            position: relative;
            margin-top: 60px;
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
            width: 100%;
            height: 100%;
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
            color: #0ba3eb;

            /* Navigation button color */
        }

        @media (max-width: 1024px) {
            .swiper-container {
                height: 60vh;
                /* Adjust height for tablets and smaller screens */
            }
        }

        @media (max-width: 768px) {
            .swiper-container {
                height: 50vh;
                /* Further adjust height for smaller tablets */
            }
        }

        @media (max-width: 500px) {
            .swiper-container {
                height: 40vh;
                /* Adjust height for mobile devices */
            }

            .container form button {
                padding: 8px 16px;
                font-size: 14px;
            }

            .container1 form {
                display: flex;
                flex-direction: column;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 525px) {
            .image_slider {
                height: auto;
            }
        }
    </style>
</head>

<body>
    <!-- signup already user exist-->

    <?php
    if (isset($_SESSION["UserAlreadyExist"])) {
        ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong><?php echo $_SESSION["UserAlreadyExist"]; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        // unset($_SESSION['success_log']);
    }
    ?>

    <!-- signup completed-->
    <?php
    if (isset($_SESSION["signup_complete"])) {
        ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong><?php echo $_SESSION["signup_complete"]; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        // unset($_SESSION['success_log']);
    }
    ?>

    <!-- signup pwd not match-->
    <?php
    if (isset($_SESSION["signup_pwd"])) {
        ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong><?php echo $_SESSION["signup_pwd"]; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        //unset($_SESSION["signup_pwd"]);
    }
    ?>



    <!-- logging successfully -->
    <?php
    if (isset($_SESSION['success_log'])) {
        ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong><?php echo $_SESSION['success_log']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        // unset($_SESSION['success_log']);
    }
    ?>

    <!-- logging failed -->
    <?php
    if (isset($_SESSION["login_fail"])) {
        ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong><?php echo $_SESSION["login_fail"]; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        unset($_SESSION['login_fail']);
    }
    ?>


    <!-- main -->
    <?php
    if ($loggedin) {
        // echo ' <a href="backend/logout.php">click here for logout</a>';
    }
    echo '<div class="grid">
        <header class="top_bar">
            <div class="plane">
                <img src="image/logo1.jpeg" alt="Emarsun Unisex Gym">
                <i class="ri-menu-2-line top_menu" onclick=showsidebar()></i>
                <nav class="navbar">
                    <ul>
                        <li><a href="#" onclick=closesidebar() class="hideoncomputer"><svg
                                    xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="30">
                                    <path
                                        d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                                </svg></a></li>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#membershipa">MemberShip</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>

                </nav>
                <div class="right">
                    <a href="join.php"><button class="btn1">Join</button></a>';
    if (!$loggedin) {
        echo '<button class="btn2" id="form-open">Login</button>';
    }
    if ($loggedin) {
        echo '<a href="backend/logout.php"><button class="btn2">Logout</button></a>';
    }
    echo '</div>
            </div>
        </header>

        <section class="home">

            <div class="form_container0">
                <i class=" uil uil-times form_close"></i>
                <!-- login form-->
                <div class="form login_form">
                    <form action="backend/log.php" method="post">
                        <h2>Login</h2>
                        <div class="input_box email">
                            <input required type="email" placeholder="Enter your email" name="username">
                            <i class="uil uil-envelope email1 " required></i>
                        </div>
                        <div class="input_box">
                            <input required type="password" placeholder="Enter your password" name="password">
                            <i class="uil uil-lock password"></i>
                            <i class="uil uil-eye-slash pw_hide"></i>
                        </div>
                        <div class="option_feild">
                            <span class="check">
                                <input type="checkbox" id="check">
                                <label for="check">Remember me</label>
                            </span>
                            <a href="#">Forgot password?</a>
                        </div>
                        <button class="login_btn"> Login</button>
                        <div class="dont_have_account">
                            Do not have an account? <a href="#" id="signup">signup</a>
                        </div>
                    </form>
                </div>
                <!--signup form-->
                <div class="form signup_form">
                    <form action="backend/signup.php" method="post">
                        <h2>Sign Up</h2>
                        <div class="input_box email">
                            <input required type="email" placeholder="Enter your email" name="username">
                            <i class="uil uil-envelope email2 email1 same"></i>
                        </div>
                        <div class="input_box">
                            <input required type="password" placeholder="Create password" id="conf_password"
                                name="password">
                            <i class="uil uil-lock password same"></i>
                            <i class="uil uil-eye-slash pw_hide"></i>
                        </div>
                        <div class="input_box">
                            <input required type="password" placeholder="Confirm password" id="conf_password"
                                name="cpassword">
                            <i class="uil uil-lock password confirm_password cofm_pass"></i>
                            <i class="uil uil-eye-slash pw_hide pw_hide2"></i>
                        </div>

                        <button class="login_btn">Sign up</button>
                        <div class="dont_have_account">
                            Already have an account? <a href="#" id="login">Login</a>
                        </div>
                    </form>
                </div>

            </div>
            <div class="back_img">

            </div>
            <div class="opacity_back_image">
            </div>

            <div class="main_center">
                <h1 class="name_of_gym"> <span>Emarsun</span>Unisex Gym</h1>
                <p>Experience the perfect blend of fitness and community at our state-of-the- art gym.
                    Join us today and start your journey towards a healthier and happier you

                </p>
                <a href="join.php"><button class="btn3">Join Now</button></a>
            </div>
        </section>
    </div>

    <section class="nd" id="second_pg">
        <div class="wrap">
            <div class="left">
                <h1>Unleash Your Potential with
                    Our State-of-the-Art
                    Facilities</h1>
                <p class="pad_nd_p">At our gym, we offer a wide range of state-of-the-art equipment and facilities to
                    help you
                    achieve
                    your
                    fitness goals. Whether you re looking to build muscle, improve cardiovascular endurance, or
                    simply
                    stay active, our gym has everything you need.
                </p>
                <div class="leftin">
                    <div class="icon1">
                        <img src="image/icon1.png" alt="">
                        <p>Our gym is equipped with the latest machines and tools to support your workouts</p>
                    </div>
                    <div class="icon1">
                        <img src="image/icon2.png" alt="">
                        <p>Enjoy our spacious workout areas, clean showers, and comfortable locker rooms.</p>
                    </div>
                    <!--  <a href="join.html"><button class="btn4">Join</button></a>   -->
                </div>
            </div>

            <div class="rightimg">
                <img src="image/ndr1.jfif" alt="">
            </div>

            <div class="new_one">
                <img src="image/new_one.jpg" alt="">
            </div>

        </div>
    </section>

    <section class="rd">
        <div class="one">
            <img src="image/icon3.png" alt="#">
            <h1>Joining the Gym and
                Starting Your Fitness

                Journey</h1>
            <p>Joining our gym is the first step towards a healthier and fitter lifestyle. Our fitness programs
                are designed to help you achieve your goals and make the most out of
                your gym experience.</p>
            <a href="join.html"><button class="btn5">Join</button></a>

        </div>

        <div class="two">
            <img src="image/icon4.png" alt="#">
            <h1>

                Choose Your Fitness <br>
                Program and Get Started</h1>
            <p>We offer a variety of fitness programs to suit your needs and preferences. Whether you re looking to
                lose weight, bulld muscle, or improve overall fitness, our experienced trainers will guide you
                every step of the
                way</p>
            <button class="btn6">Explore</button>
        </div>

        <div class="three">
            <img src="image/icon5.png" alt="#">
            <h1>Enjoy the Benefit of Being

                a Gym Member
            </h1>
            <p>As a member of our gym, you ll have access to state-of- the-art facilities, a supportive community,
                and exclusive perks. Take advantage of our group classes, personal training sessions, and
                special events to stay motivated
                and achieve your fitness go</p>
            <a href="join.html"> <button class="btn7">Join</button></a>

        </div>

    </section>

    <section class="th">
        <div class="th_heading">
            <h1>Achieve Your Fitness Goals <br>
                Our Service</h1>
            <p>
                At our gym, we offer a range of services to help you reach your fitness goals. From
                personalized
                training sessions to group classes and nutritional guidance, we have everything you need to get
                fit and stay healthy
            </p>
        </div>
        <div class="th_function">
            <div class="one1">
                <img src="image/icon6.png" alt="">
                <h1>Personal Training</h1>
                <p>Work one-on-one with our experienced trainers to
                    achieve your fitness goals faster.</p>
            </div>
            <div class="two2">
                <img src="image/icon7.png" alt="">
                <h1>Yoga</h1>
                <p>Find peace within, stretch your body, and breath
                    Yoga bring harmony to mind and soul.</p>
            </div>
            <div class="three3">
                <img src="image/icon8.png" alt="">
                <h1>Nutritional Guidance</h1>
                <p>Get expert advice on nutrition to complement your
                    fitness routine and optimize your results.</p>
            </div>
        </div>
        <div class="th_button">
            <a href="join.html"><button class="btn8">join</button></a>

            <a href="#">learn &gt;</a>
        </div>
    </section>

    <section class="price_card" id="membershipa">
        <div class="price-row">
            <h2>MemberShip Plan</h2>
            <p>Choose the perfect member ship plan for your fitness journey</p>
        </div>
        <div class="price_column">
            <div>
                <p>1 Month</p>
                <h2>&#8377 700 <span>/ month</span></h2>
                <ul>
                    <li>trainer</li>
                    <li>basic diet plan</li>
                    <li>machine uses</li>
                    <li>weekly cardio</li>
                    <li>straching</li>
                </ul>
                <button class="btn9">Choose Now</button>
            </div>
            <div>
                <p>3 Month</p>
                <h2>&#8377 1700 <span>/ Quatarly</span></h2>
                <ul>
                    <li>trainer</li>
                    <li>basic diet plan</li>
                    <li>machine uses</li>
                    <li>weekly cardio</li>
                    <li>straching</li>
                </ul>
                <button class="btn9">Choose Now</button>
            </div>
            <div>
                <p>6 Month</p>
                <h2>&#8377 2999 <span>/ Half Yearly</span></h2>
                <ul>
                    <li>trainer</li>
                    <li>basic diet plan</li>
                    <li>machine uses</li>
                    <li>weekly cardio</li>
                    <li>straching</li>
                </ul>
                <button class="btn9">Choose Now</button>
            </div>
            <div>
                <p>1 Year</p>
                <h2>&#8377 4999 <span>/ Year</span></h2>
                <ul>
                    <li>trainer</li>
                    <li>basic diet plan</li>
                    <li>machine uses</li>
                    <li>weekly cardio</li>
                    <li>straching</li>
                </ul>
                <button class="btn9">Choose Now</button>
            </div>
        </div>
        <div class="Note">
            <p>Note: 200 Rupees Will Be Regitration Fees</p>
        </div>

    </section>

    <section class="image_slider" id="gallery">
        <div class="heading_slider">
            <h2>Gallery Of Our Gym</h2>
            <p>"Explore our gym s vibrant gallery showcasing fitness journeys,
                workout routines, and a

                community committed to wellness. Discover the power of transformation

                through sweat and dedication.</p>
        </div> 
        
        

        ';
    if (!$admin) {
        echo '<div class="container1" style="display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100vw;
            padding-bottom: 50px;
            margin-top: 10px">

        <h1>Upload Images</h1>
        <!-- File upload form -->
        <form action="index.php" method="post" enctype="multipart/form-data" style="">
            <input type="file" name="image" required>
            <button type="submit" class="upl">Upload Image</button>
        </form>';
    }
    echo '
        <!-- Image Slider -->
        <div class="swiper-container">
            <div class="swiper-wrapper">';


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="swiper-slide">
                    <img src="upload_image/' . htmlspecialchars($row['image']) . '" alt="Image" >
                    <div class="delete-icon" onclick="confirmDelete(' . $row['id'] . ')">✖</div>
                </div>';
        }
    } else {
        echo '<p>No images found.</p>';
    }
    echo '    </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>';

    echo '</div>';





    // echo ' </div>
    // <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
    //     data-bs-slide="prev">
    //     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    //     <span class="visually-hidden">Previous</span>
    // </button>
    // <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
    //     data-bs-slide="next">
    //     <span class="carousel-control-next-icon" aria-hidden="true"></span>
    //     <span class="visually-hidden">Next</span>
    // </button>
    // </div>
    echo '
    </section>
    <section class="rating_caed">

    </section>
    <section class="map">
        <div>
            <h2>Locate Us On Google Map</h2>
        </div>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3767.7492760779214!2d72.95018507346357!3d19.206150247877236!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b9a27dd03929%3A0xe261c1048d045bea!2sEMARSUN%20FAMILY%20GYM!5e0!3m2!1sen!2sin!4v1704535884723!5m2!1sen!2sin"
            width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    <section class="footer">
        <div class="info">
            <div class="row_footer">
                <div class="col_footer">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="">About Us</a></li>
                        <li><a href="">Our Services</a></li>
                        <li><a href="">Privacy Policy</a></li>
                        <li><a href="">Affiliate Program</a></li>
                    </ul>
                </div>
                <div class="col_footer">
                    <h4>Get Help</h4>
                    <ul>
                        <li><a href="">FAQ</a></li>
                        <li><a href="">MemberShip</a></li>
                        <li><a href="">Inqiary</a></li>
                        <li><a href="">Help</a></li>
                    </ul>
                </div>
                <div class="col_footer">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="">Fees Inquiry</a></li>
                        <li><a href="">Offers</a></li>
                        <li><a href="">Class</a></li>
                        <li><a href="">Done</a></li>
                    </ul>
                </div>
                <div class="col_footer">
                    <h4>Follow Us</h4>
                    <div class="social_link">

                        <a href=""><i class="fab fa-facebook-f"></i></a>
                        <a href=""><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/star_kit_7k?igsh=MTgzOXBwdnpnZ2VoOQ=="><i
                                class="fab fa-instagram"></i></a>
                        <a href=""><i class="fab fa-linkedin"></i></a>
                        <a href="https://wa.me/917738964584"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="loc">
                <div class="location">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>
                        Near Manpasand Sweets & Ganesh Hospital, Veer Savarkar Nagar, Savarkar Nagar,
                        Thane West, Thane, Maharashtra 400606
                    </span>
                </div>
                <div class="phone_no">
                    <i class="fa-solid fa-phone"></i>
                    <span>
                        +91 8452045908
                    </span>
                </div>


            </div>

        </div>
    </section>';

    ?>
    <!-- image slider bootstrap script-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <!-- bootstrap -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>




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
                window.location.href = 'index.php?delete_id=' + id;
            }
        }
    </script>
</body>

</html>