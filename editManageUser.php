<?php
include("connection.php");
$employee_id = $_SERVER['QUERY_STRING'];

//accessing input field values
$query1 = "select * from employee where empid='$employee_id'";
$result = $conn->query($query1);
if ($result->rowCount() > 0) {
    foreach ($result as $row) {

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Manage User</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <!-- <link rel="stylesheet" href="project.css"> -->
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"
                integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
            <!-- bootstrap js -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <!-- font-awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
                integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />
        </head>

        <body>
            <div class="container w-75 border rounded d-block m-auto mt-4  createStyle">
                <h1 class="text-center">Editing user</h1>
                <form action="" method="post" id='form' class=" w-75 d-block m-auto" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col" id="profile_picture">
                            <?php
                            $query = "select * from employee where empid='$employee_id'";
                            $result = $conn->query($query);
                            foreach ($result as $res) {
                                $image = $res['image'];
                                echo "<img src='./Images/$image' class='d-block object-fit-cover  mx-auto  ' 
                        style='width:300px;height:200px;'>";
                            }
                            ?>
                        </div>
                        <button type="button" class="btn d-inline border" name='img_button' data-bs-toggle="collapse"
                            data-bs-target="#image_file">Change
                            profile picture</button>

                    </div>
                    <div class="row">
                        <div id="image_file" class="col collapse">
                            <input type="file" id="image_file" name="image_upload">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col "> <label for="" class="form-label mt-2">First name :</label> </div>
                    </div>
                    <div class="row">
                        <div class="col"> <input class="form-control" type="text" name='fname'
                                value="<?php echo $row['first_name'] ?>"></div>
                    </div>
                    <div class="row">
                        <div class="col"> <label for="" class=" form-label mt-2">Last name :</label> </div>
                    </div>
                    <div class="row">
                        <div class="col"> <input class="form-control" type="text" name="lname"
                                value="<?php echo $row['last_name'] ?>"></div>
                    </div>
                    <div class="row">
                        <div class="col"><label for="" class="form-labimageel mt-2">Email :</label></div>
                    </div>
                    <div class="row">
                        <div class="col"><input type="email" name='email' class=" form-control"
                                value="<?php echo $row['email'] ?>"></div>
                    </div>

                    <div class=" row mt-2">
                        <div class="col-sm-2 dropdown">
                            <label for="" class="form-label ">Role :</label>
                        </div>
                        <div class=" col-sm-10 dropdown   ps-5 rounded">
                            <select name="dropdown" id="dropdown" class='border-0  form-control bg-light '>
                                <option value="Admin" <?php echo $row['role'] == "Admin" ? 'selected' : "" ?>>Admin</option>
                                <option value="HR" <?php echo $row['role'] == "HR" ? 'selected' : "" ?>>HR</option>
                                <option value="Developer" <?php echo $row['role'] == "Developer" ? 'selected' : "" ?>>
                                    Developer
                                </option>
                                <option value="Tester" <?php echo $row['role'] == "Tester" ? 'selected' : "" ?>>Tester
                                </option>
                                <option value="Research" <?php echo $row['role'] == "Research" ? 'selected' : "" ?>>Research
                                </option>
                                <option value="Accountant" <?php echo $row['role'] == "Accountant" ? 'selected' : "" ?>>
                                    Accountant
                                </option>
                            </select>
                            <span class="color">
                                <!-- <?php echo $roleErr; ?> -->
                            </span>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col"><label for="" class="label-form mt-2">Username :</label></div>
                    </div>
                    <div class="row">
                        <div class="col"><input type="text" name='usn' class="form-control"
                                value="<?php echo $row['user_name'] ?>"></div>
                    </div>
                    <div class="row">
                        <div class="col"><label for="" class="form-label mt-2">Password :</label></div>
                    </div>
                    <div class="row">
                        <div class="col"><input type="text" name='pswd' class="form-control"
                                value=""></div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" name='submit' class=" btn btn-light d-block m-auto mt-3" value="Save">
                        </div>
                    </div>


                </form>
                <div class="row">
                    <div class="col">
                        <a href="manageUser.php">
                            <button type="button" class='btn border btn-dark rounded'>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>


        </body>
        <?php
        if (isset($_POST['submit'])) {

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $selectedValue = $_POST['dropdown'];
            $pswd = $_POST['pswd'];
            echo strlen($pswd)."<br>";
            $hashedPswd = password_hash($pswd, PASSWORD_BCRYPT);
            
            $usn = $_POST['usn'];


            // $empId = $res['empid'];
            $query2 = "update employee
                    set
                    first_name = '$fname',
                    last_name = '$lname',
                    email = '$email',
                    role = '$selectedValue',
                    user_name = '$usn',
                    password = '$hashedPswd'
                    where empid = '$employee_id'";
            $conn->exec($query2);
            if (isset($_FILES["image_upload"]) && $_FILES["image_upload"]["error"] == UPLOAD_ERR_OK) {
                $image = $_FILES["image_upload"]["name"];

                $temp = $_FILES["image_upload"]["tmp_name"];

                move_uploaded_file($temp, "Images/" . $image);
                $image_update = "update employee 
                set 
                image='$image' where empid='$employee_id'";
                $conn->exec($image_update);
            }
            echo "<script>alert('updated successfully');</script>";
        }
    }
}
?>
<?php


?>

</html>