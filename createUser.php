<?php
include('connection.php');
//accessing input field values
if (isset($_POST['submit'])) {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $selectedValue = $_POST['dropdown'];
    $pswd = $_POST['pswd'];
    $hashedPswd = password_hash($pswd, PASSWORD_BCRYPT);
    // echo $hashedPswd;
    $usn = $_POST['usn'];

    $image = $_FILES["img"]["name"];
    $temp = $_FILES["img"]["tmp_name"];

    if (move_uploaded_file($temp, "Images/" . $image)) {

        echo "image uploaded";
    }
    // echo $_SERVER['REQUEST_URI'];
    if (empty($fname) || empty($lname) || empty($email) || empty($usn) || empty($hashedPswd) || empty($selectedValue)) {
        echo "<script>alert('Please fill all the fields');</script>";
    } else {
        $query = "insert into employee(first_name,last_name,email,image,role,user_name,password)
     values('$fname','$lname','$email','$image','$selectedValue','$usn','$hashedPswd')";
        $conn->exec($query);
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="project.css">
</head>

<body>
    <div class="container w-75 border rounded d-block m-auto mt-4  createStyle">
        <h1 class="text-center">Create user</h1>
        <form action="" method="post" id='form' class=" w-75 d-block m-auto" enctype="multipart/form-data">
            <div class="row">
                <div class="col "> <label for="" class="form-label mt-2">First name :</label> </div>
            </div>
            <div class="row">
                <div class="col"> <input class="form-control" type="text" name='fname'></div>
            </div>
            <div class="row">
                <div class="col"> <label for="" class=" form-label mt-2">Last name :</label> </div>
            </div>
            <div class="row">
                <div class="col"> <input class="form-control" type="text" name='lname'></div>
            </div>

            <div class="row">
                <div class="col"><label for="" class="form-labimageel mt-2">Email :</label></div>
            </div>
            <div class="row">
                <div class="col"><input type="email" name='email' class=" form-control"></div>
            </div>
            <div class="row">
                <div class="col"><label class="form-label mt-2">Image :</label></div>
            </div>
            <div class="row">
                <div class="col"><input type="file" name="img" class=" form-control">

                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm dropdown">
                    <label for="" class="form-label ">Role :</label>
                </div>
            </div>
            <div class="row ">

                <div class=" col dropdown  rounded">
                    <select name="dropdown" id="dropdown" class='border-0  form-control bg-light '>
                        <option value="Admin"> Admin</option>
                        <option value="HR">HR</option>
                        <option value="Developer">Developer</option>
                        <option value="Tester">Tester</option>
                        <option value="Research">Research</option>
                        <option value="Accountant">Accountant</option>
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
                <div class="col"><input type="text" name='usn' class="form-control"></div>
            </div>
            <div class="row">
                <div class="col"><label for="" class="form-label mt-2">Password :</label></div>
            </div>
            <div class="row">
                <div class="col"><input type="text" name='pswd' class="form-control"></div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" name='submit' class=" btn btn-light d-block m-auto mt-3" value="Save">
                </div>
            </div>


        </form>

    </div>

</body>

</html>