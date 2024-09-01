<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="join1.css"> <!-- Link to your custom CSS -->

    <link rel="shortcut icon" href="../image/logo1.jpeg" type="image/x-icon">

    <!-- Bootstrap CSS (only one link needed) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- SweetAlert Script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <title>Join</title>
    <style>
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        /* Modal Content */
        .modal-content {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 28%;
            text-align: center;
        }

        .modal-content span img {
            width: 50px;
        }

        .modal-content p {
            color: black;
        }

        /* Close Button */
        /* .close {
            color: black;
            float: right;
            font-size: 28px;
            font-weight: bold;
        } */

        /* .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        } */

        /* Button Styles */
        #redirectButton {
            color: black;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>


<body>
    <!-- if user not login send to login Page -->
    <?php
    if (!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] != true) {
        ?>
        <div id="modal" class="modal">
            <div class="modal-content">
                <span id="closeModalBtn" class="close"><img src="image/cross.png" alt="nothing"></span>
                <p>You can't join <br>go and login first!
                </p>
                <button id="redirectButton">Go to Error Page</button>
            </div>
        </div>

        <?php
        //  header("location: index.php");
        //  exit;
    }
    ?>

    <!-- joining successfully-->
    <?php
    if (isset($_SESSION["success_join"])) {
        ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong><?php echo $_SESSION["success_join"]
            ; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        unset($_SESSION["success_join"]);
    }
    ?>

    <!-- joining failed-->
    <?php
    if (isset($_SESSION["join_failed"])) {
        ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong><?php echo $_SESSION["join_failed"]; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        unset($_SESSION["join_failed"]);
    }
    ?>



    <div class="before">
        <form action="backend/joiningdata.php" method="post">
            <div class="join_col">
                <div class="join_row">
                    <h2>Joining Form</h2>
                    <div class="form">
                        <div class="name">
                            <label for="Name">Name</label><br>
                            <input type="text" required name="name">
                        </div>
                        <div class="email">
                            <label for="email">Email</label><br>
                            <input required type="email" name="email">
                        </div>
                        <div class="number">
                            <label for="mobile">Mobile No.</label><br>
                            <input type="number" inputmode="numeric" required name="mobile">
                        </div>
                        <div class="gender">
                            <div>
                                <label for="gender">Gender</label><br>
                            </div>
                            <div class="radio_buttons">
                                <div>
                                    <input type="radio" name="gender" required clas="male" id="male" value="Male">
                                    <label for="male">Male</label>
                                </div>
                                <div>
                                    <input type="radio" name="gender" id="female" required class="female"
                                        value="Female">
                                    <label for="female">Female</label>
                                </div>

                            </div>
                        </div>

                        <div class="address">
                            <label for="address">Address</label><br>
                            <input input="textarea" required name="address">
                        </div>

                        <div class="join">
                            <input type="submit" value="Join" class="join_btn">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script> -->

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>


    <script>
        // script.js

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modal');
            // const closeModalBtn = document.getElementById('closeModalBtn');
            const redirectButton = document.getElementById('redirectButton');

            // Function to open the modal
            function openModal() {
                modal.style.display = 'block';
            }

            // Function to close the modal

            //  function closeModal() {
            //      modal.style.display = 'none';
            //  }

            // Open Modal on page load
            openModal();

            // Event listener for close modal button
            // closeModalBtn.onclick = closeModal;

            // Event listener for redirect button
            redirectButton.onclick = () => {
                window.location.href = 'index.php';
            };

            // Do not close modal when clicking outside of the modal content
            // Comment or remove this section
            /*
            window.onclick = (event) => {
                if (event.target == modal) {
                    closeModal();
                }
            };
            */
        });


    </script>


</body>

</html>