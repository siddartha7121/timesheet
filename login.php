<?php
ob_start(); // Start output buffering
session_start();
include('connection.php');

// Initializing variables
$usnErr = $pswdErr = $loginErr = $roleErr = "";

if (isset($_POST['submit'])) {
    // Accessing form inputs
    $usn = $_POST['usn'];
    $pswd = $_POST['pswd'];
    $selectedValue = $_POST['dropdown'];

    if (empty($usn)) {
        $usnErr = "Username is required*";
    } else if (empty($pswd)) {
        $pswdErr = "Password is required*";
    } else if ($selectedValue === 'Role') {
        $roleErr = "Please select a role";
    } else {
        // Query to fetch a particular employee record based on username
        $query = "SELECT * FROM employee WHERE user_name = :usn";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':usn', $usn);
        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug: Print the fetched record
        print_r($rec);

        if ($rec) {
            // Assuming password is stored as hashed in the database
            $hashedPassword = $rec['password'];

            // Check if the entered password is correct
            if (password_verify($pswd, $hashedPassword)) {
                // Check if the selected role matches the stored role
                if ($rec['role'] === $selectedValue) {
                    // Initialize session variables
                    $_SESSION['id'] = $rec['empid'];
                    $_SESSION['name'] = $rec['user_name'];
                    $_SESSION['role'] = $rec['role'];

                    ob_end_clean(); // Clean (discard) the buffer before header

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $roleErr = "Role is incorrect";
                }
            } else {
                $loginErr = "Username or password is incorrect";
            }
        } else {
            $loginErr = "Username not found";
        }
    }
}

ob_end_clean(); // Clean (discard) the buffer before any potential output
?>


 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Log in</title>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <link rel="stylesheet" href="project.css">
 </head>

 <body>
     <div class=" container d-block m-auto w-50 mt-5 border rounded" id='mcon'>
         <form action="" id='form' method='post'>
             <h1 class="text-center">Login</h1>
             <div class="m-auto d-block w-75 me-3">
                 <div class="row mb-3 mt-3">
                     <label for="" class="col-sm-2 col-form-label  px-0">Username:</label>
                     <div class="col-sm-10">
                         <input type="text" class="form-control w-75 " name='usn'>
                         <span class="color">
                             <?php echo $usnErr; ?>
                         </span>
                     </div>
                 </div>
                 <div class="row mb-3 mt-3">
                     <label for="" class="col-sm-2 col-form-label px-0">Password:</label>
                     <div class="col-sm-10">
                         <input type="password" class="form-control w-75" name='pswd'>
                         <span class="color">
                             <?php echo $pswdErr; ?>
                         </span>
                     </div>
                     <span class="color">
                         <?php echo $loginErr ?>
                     </span>
                 </div>
             </div>
             <div class="row mb-4 ">
                 <div class="col-sm-2 me-5"></div>
                 <div class=" col-sm-10 dropdown dropend  w-25 ms-5 ps-5 rounded">
                     <select name="dropdown" id="dropdown" class='border-0  form-control bg-light '>
                         <option value="Role">Role</option>
                         <option value="Admin">Admin</option>
                         <option value="HR">HR</option>
                         <option value="Developer">Developer</option>
                         <option value="Tester">Tester</option>
                         <option value="Research">Research</option>
                         <option value="Accountant">Accountant</option>
                     </select>
                     <span class="color">
                         <?php echo $roleErr; ?>
                     </span>
                 </div>
             </div>
             <button type="submit" name='submit' class="btn btn-primary d-block m-auto mb-3">Login</button>
         </form>
     </div>
 </body>

 </html>