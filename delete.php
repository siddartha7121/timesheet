<?php
include("connection.php");
session_start();
$empid = $_SESSION['id'];
$username = $_SESSION['name'];

if (isset($_GET['date'])) {
    $date_value = $_GET['date'];
    $selectidquery = "select * from user_details where empid='$empid' and date=$date_value";
    $selectid = $conn->query($selectidquery);
    $getId = $selectid->fetch(PDO::FETCH_ASSOC);
    $id = $getId['id'];

    //deleting whole day records permanently
    $delete_record = "delete from timesheet_data where userid='$id'";
    $conn->exec($delete_record);
    $delete_date = "delete from user_details where id='$id'";
    $conn->exec($delete_date);
    header("Location:dashboard.php");

} else if (isset($_GET['employee_id'])) {

    $emp_id = $_GET['employee_id'];
    //storing deleting data before delete
    $select_employee = "select * from employee where empid='$emp_id'";
    $result = $conn->query($select_employee);
    $res = $result->fetch(PDO::FETCH_ASSOC);

    // $fname = $res["first_name"];
    // $lname = $res["last_name"];
    // $email = $res["email"];
    // $image = $res["image"];
    // $selectedValue = $res["role"];
    // $usn = $res["user_name"];
    // $hashedPswd = $res["password"];
    // $insert = "insert into backup_employee(first_name,last_name,email,image,role,user_name,password)
    //     values('$fname','$lname','$email','$image','$selectedValue','$usn','$hashedPswd')";
    // $conn->exec($insert);

    //deleting employee permanently
    $delete_user = "delete from employee where empid='$emp_id'";
    $conn->exec($delete_user);
    header("Location:manageUser.php");
}

?>






























<!-- <?php
// include("connection.php");
// session_start();
// $empid = $_SESSION['id'];
// $username = $_SESSION['name'];

// if (isset($_GET['date'])) {
//     $date_value = $_GET['date'];
//     $selectidquery = "select * from user_details where empid='$empid' and date=$date_value";
//     $selectid = $conn->query($selectidquery);
//     $getId = $selectid->fetch(PDO::FETCH_ASSOC);
//     $id = $getId['id'];



//storing deleting data before delete
// $select = "select * from timesheet_data where userid='$id'";
// $result = $conn->query($select);
// $insert_backup = "insert into user_detailsbackup(empid,date) values('$id',$date_value)";
// $conn->query($insert_backup);
// foreach ($result as $res) {
//     $work = $res['work_type'];
//     $start = $res['start_time'];
//     $end = $res['end_time'];
//     $uid = $res['userid'];
//     $insert = "insert into timesheet_backup(work_type,start_time,end_time,userid)
// values('$work','$start','$end','$id')";
//     $conn->query($insert);

// }



//deleting whole day records permanently
// $delete_record = "delete from timesheet_data where userid='$id'";
// $conn->exec($delete_record);
// $delete_date = "delete from user_details where empid='$empid' and date=$date_value";
// $conn->exec($delete_date);


// header("Location:dashboard.php");
// } else if (preg_match("/[=]/", $date)) {
// }


// else if (isset($_GET['id'])) {
//     $idValue = $_GET['id'];
//     // $select = "select dateOfFilling from timesheet where id='$idValue'";
//     // $select = "select dateOfFilling from user_details where empid='$idValue'";
//     // $result = $conn->query($select);
//     // if ($result->rowCount() > 0) {
//     //     foreach ($result as $res) {
//     //         $date1 = $res["dateOfFilling"];
//     //     }
//     //storing deleting data before delete
//     // $select = "select * from timesheet where dateOfFilling='$date1' and id='$idValue' and emp_id='$id'";

//     $select = "select * from timesheet_data where  id=$idValue";
//     $result = $conn->query($select);
//     foreach ($result as $res) {
//         $work = $res['work_type'];
//         $start = $res['start_time'];
//         $end = $res['end_time'];
//         $uid = $res['userid'];
//         // $userquery = "select * from user_details where id='$uid'";
//         // $execquery = $conn->query($userquery);
//         // $userdetails = $execquery->fetch(PDO::FETCH_ASSOC);
//         // $empid = $userdetails['empid'];
//         // $date = $userdetails['date'];
//         // $insert_backup = "insert into user_detailsbackup(empid,date) values('$empid',$date)";
//         // $conn->query($insert_backup);
//         $insert = "insert into timesheet_backup(work_type,start_time,end_time,userid)
//             values('$work','$start','$end','$idValue')";
//         $conn->query($insert);
//     }
//     //deleting each record permanently

//     // $delete_record = "delete from timesheet where id='$idValue'";
//     $delete_record = "delete from timesheet_data where id='$idValue'";

//     $conn->exec($delete_record);
//     header('Location:editmain.php?' . $idValue);


//     // } else {
// } else 


// ?>


//  <!DOCTYPE html>
// <html lang="en">
// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Document</title>
//     <script src="https://code.jquery.com/jquery-3.7.1.min.js"
//         integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
// </head>
// <body>
//     <input type="hidden" id="input_tag" name="booleanVal">
// </body>
// <script>
    $(document).ready(function(){

       var verify=confirm("Are you sure to delete");
    $('#input_tag').val(verify);

    });
    
</script>
</html> -->