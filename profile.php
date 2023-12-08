<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('connection.php');
//accessing session variable
$username = $_SESSION['name'];
$query = "select * from employee where user_name='$username'";

$result = $conn->query($query)

    ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="project.css">
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class='background'>
    <div class="container pt-5 mt-5 ">
        <div class="row w-75  border border-dark mx-auto mt-5 rounded-pill">

            <?php
            foreach ($result as $res) {

                echo " <div class='col-6 d-block m-auto ps-5'>
                <h3> User details :</h3>
            <div>Name: " . $res['first_name'] . $res['last_name'] . "</div>
            <div>Email: " . $res['email'] . "</div>
            <div>Role:" . " ". $res['role'] . "</div>
            </div>
            <div class='col-6 bg-light border  border-dark rounded'>";

                $image = $res['image'];
                echo "<img src='./Images/$image' class='d-block  mx-auto border  rounded-circle' style='width:300px;height:300px;'>";
            }
            ?>

        </div>

    </div>
    <div class="row">
        <div class="col">
            <a href="dashboard.php">
                <button type="button" class='btn border btn-dark rounded'>
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a>
        </div>
    </div>
    </div>
</body>

</html>