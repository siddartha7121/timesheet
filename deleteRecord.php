<?php
include("connection.php");
session_start();
$date=$_SESSION['deldate'];
$empid = $_SESSION['id'];
$username = $_SESSION['name'];
if (isset($_GET['id'])) {
    $idValue = $_GET['id'];
    
    // $select = "select * from timesheet_data where  id=$idValue";
    // $result = $conn->query($select);
    // $uid=0;
    // $res=$result->fetch(PDO::FETCH_ASSOC);
    //     // $work = $res['work_type'];
    //     $start = $res['start_time'];
    //     $end = $res['end_time'];
    //     $uid = $res['userid'];
    //     // $insert = "insert into timesheet_backup(work_type,start_time,end_time,userid)
    //     //     values('$work','$start','$end','$idValue')";
    //     // $conn->query($insert);
    
    // $selectdate="select date from user_details where id='$uid'";
    // $dateexec=$conn->query($selectdate);
    // $getdate=$dateexec->fetch(PDO::FETCH_ASSOC);
    // $date=$getdate['date'];

    //deleting each record permanently
    $delete_record = "delete from timesheet_data where id='$idValue'";
    $conn->exec($delete_record); 
    header('Location:editmain.php?date=' . $date);
}
?>