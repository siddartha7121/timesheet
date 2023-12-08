<?php
include("connection.php");
$query = "select * from employee";
$result = $conn->query($query);
$rows = $result->rowCount();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>Manage User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="project.css">
     <!-- font-awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
                integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <div class="container w-75 px-0 ">
        <div class="row">
            <div class="col d-block m-auto">
                <form method="post">
                    <table border=2px cell-spacing=0px class=" table border text-center" id='bgcolor'>
                        <tr>
                            <th>Employee Id</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>image</th>
                            <th>Role</th>
                            <th>Edit</th>
                            <th>Remove</th>
                        </tr>

                        <?php
                        $row = 1;
                        foreach ($result as $res) {
                            $fname=$res['first_name'];
                            $lname=$res['last_name'];
                            $email=$res['email'];
                            $empid=$res['empid'];
                            $image = $res['image'];
                            $role=$res['role'];
                            
                            echo "   <tr id='row-'" . $row . ">
                                <td><input type='button' name='empid' id='empId-$row' value=" . $res['empid'] . "></td>
                                <td>" . $fname . "</td>
                                <td>" . $lname . "</td>
                                <td>" . $email . "</td>";
                           
                            echo "<td><img src='./Images/$image' class='d-block object-fit-contain  mx-auto border rounded-circle' style='width:150px;height:150px;'></td>
                          
                                <td>" . $role . "</td>
                                <td><a href='editManageUser.php?" . $empid . "'>
                                <button type='button' class='border rounded '>Edit</button>
                                </a></td>
                                <td><a href='delete.php?employee_id=" . $empid . "'>
                                <button type='button' class='border rounded '>X</button>
                              
                                </a></td>
                                </tr>";
                            $row++;
                        }
                        ?>
                    </table>
                </form>
                
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
    </div>
   
</body>

</html>