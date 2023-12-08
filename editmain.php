<?php
include("connection.php");
session_start();
$id = $_SESSION['id'];
$username = $_SESSION['name'];
//getting data from url
$date = $_GET['date'];
// $date = $_SERVER['QUERY_STRING'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="project.css">
</head>

<body>
    <div class="container border border-dark p-0 " id="mcon">
        <div class="w-75  text-center d-block m-auto ">
            <form action="" id='form' method='post'>
                <div class="row mx-0">
                    <div class="col">User :
                        <?php echo $username ?>
                    </div>
                </div>
                <div class="row mt-5 mx-0">
                    <div class="col">
                        <label>Date</label>
                        <input id='datee' type="date" class="dateFormat" name="datee" value=<?php echo $date; ?>>
                    </div>
                </div>

                <div class="row mt-4 mx-0">
                    <div class="col-4  border border-dark " id="role">
                        <h3>Work-Type</h3>
                        <?php
                        $array = ['development', 'testing', 'research', 'meetings', 'projectmanagement', 'training'];
                        ?>
                        <ul class="d-flex flex-wrap  drag p-3 ">
                            <?php
                            foreach ($array as $value) {
                                echo "<li class='g-2 p-2 mt-1 me-1 border border-dark '>$value</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-1 "></div>
                    <div class="col-3 border border-dark " id="time">
                        <h3>Time-Slot</h3>
                        <ul class="d-flex  flex-wrap drag justify-content-center mb-4  px-0">
                            <?php
                            for ($i = 0; $i < 24; $i++) {
                                echo "<li class='hours me-1 mt-1 border  border-dark '>$i</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-1 "></div>
                    <div class="col-3 border border-dark " id="minutes">
                        <h3>Minutes</h3>
                        <ul class="d-flex  flex-wrap drag justify-content-center px-0">
                            <?php
                            for ($i = 0; $i < 60; $i += 5) {
                                echo "<li class='hours me-1 mt-1 border border-dark '>" . ((strlen((string) $i) > 1) ? $i : ('0' . $i)) . "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>


                <div class=" border border-dark">
                    <div class="" id='inputFields'>
                        <table border=2px id="edittable">

                            <thead>
                                <tr>
                                    <th>WORK</th>
                                    <th>START HOUR</th>
                                    <th>START MINUTE</th>
                                    <th>END HOUR</th>
                                    <th>END MINUTE</th>
                                    <th>REMOVE</th>


                                </tr>
                            </thead>
                            <tbody >
                                <?php

                                $selectid = "select * from user_details where date='$date' and empid='$id'";
                                $idresult = $conn->query($selectid);

                                if ($idresult) {
                                    $rec = $idresult->fetch(PDO::FETCH_ASSOC);
                                    $userid = $rec['id'];
                                }
                                // $query = "select * from timesheet where dateOfFilling='$date' and  
                                // emp_id='$id' order by start_time asc";
                                $query = "select * from timesheet_data where userid='$userid' 
                              order by start_time asc";
                                $result = $conn->query($query);
                                $id = 1;
                                //for changing id value after creating new fields
                                $flag = false;
                                foreach ($result as $res) {
                                    $work = $res['worktypeid'];
                                    //getting workid
                                    $selectwork = "select work_type from work_type where id='$work' ";
                                    $workresult = $conn->query($selectwork);

                                    if ($workresult) {
                                        $workrec = $workresult->fetch(PDO::FETCH_ASSOC);
                                        $worktype = $workrec['work_type'];
                                    }

                                    echo "                      
                                 <tr id='row-$id' class='drop' >
                            <td><input name='work-$id' id='work-$id' value=" . $worktype . " ></td>
                            <td><input  name='start-$id' id='start-$id' value=" . (int) (((int) $res['start_time']) / 60) . "></td>
                            <td><input  name='startminute-$id' id='startminute-$id' value=" . ((int) $res['start_time']) % 60 . "></td>
                            <td><input name='end-$id' id='end-$id' value=" . (int) (((int) $res['end_time']) / 60) . "></td>  
                            <td><input name='endminute-$id' id='endminute-$id' value=" . ((int) $res['end_time']) % 60 . "></td>  
                            <td><a href=''>
                            <button type='button'  class='del border rounded btn btn-danger pt-0'>X</button>
                            </a></td>                          
                            </tr>";
                                    $id++;
                                }
                                if (isset($_POST['submit'])) {
                                    $endValue = 0;
                                    $first_record = 0;
                                    $result1 = $conn->query($query);
                                    $existedRows = $result1->rowCount();
                                    $flag = $_POST['flagVal'];
                                    $limit = $id;
                                    $first_startValue = 0;
                                    $end1 = 0;
                                    $datresCheckee = $_POST['datee'];

                                    $dateCheck = "select date from user_details";
                                    $date_result = $conn->query($dateCheck);
                                    foreach ($date_result as $row) {
                                        if ($datresCheckee == $row['date'] && $datresCheckee != $date) {
                                            echo "already exists for changing date";
                                            break;
                                        } else {
                                            $date = $row['date'];
                                        }
                                    }
                                    if ($flag == "true") {
                                        $limit = $_POST['changedId'];
                                    }
                                    // $select = "select empid from employee where empid='$id'";
                                    // $result = $conn->query($select);
                                    // foreach ($result as $res) {
                                    //     $eid = $res['empid'];
                                    // }
                                    for ($i = 1; $i < $limit; $i++) {
                                        // for ($i = 1; $i < $id; $i++) {
                                        $work = $_POST['work-' . $i];

                                        //getting work-id
                                        $workquery = "select id from work_type where work_type='$work'";
                                        $workresult = $conn->query($workquery);
                                        if ($workresult) {
                                            $workexec = $workresult->fetch(PDO::FETCH_ASSOC);
                                            $workid = $workexec['id'];
                                        }


                                        $starthour = $_POST['start-' . $i];
                                        $startminute = $_POST['startminute-' . $i];
                                        $start = (int) (((int) $starthour * 60) + (int) $startminute);
                                        $endhour = $_POST['end-' . $i];
                                        $endminute = $_POST['endminute-' . $i];
                                        $end = (int) (((int) $endhour * 60) + (int) $endminute);
                                        foreach ($result1 as $res1) {
                                            $id1 = $res1['id'];
                                            $work1 = $res1['worktypeid'];
                                            $start1 = $res1['start_time'];
                                            $start_hour = (int) ($start1 / 60);
                                            $start_minute = (int) ($start1 % 60);
                                            $end1 = $res1['end_time'];
                                            $end_hour = (int) ($end1 / 60);
                                            $end_minute = (int) ($end1 % 60);
                                            if ($first_record == 0) {
                                                $first_record++;
                                                $first_startValue = $start1;
                                            }
                                            if ($work1 == $work && $start1 == $start && $end1 == $end) {
                                                //won't update any record                        
                                                break;
                                            } else {

                                                $work_update = ($work != $workid) ? "update timesheet_data set worktypeid='$workid' where id='$id1'" : "";
                                                $start_update = ($start != $start1) ? "update timesheet_data set start_time='$start' where id='$id1'" : "";
                                                $end_update = ($end != $end1) ? "update timesheet_data set end_time='$end' where id='$id1'" : "";
                                                if ($work_update != "")
                                                    $conn->exec($work_update);
                                                if ($start < $end && $endValue <= $start) {
                                                    if ($start_update != "")
                                                        $conn->exec($start_update);
                                                    if ($end_update != "")
                                                        $conn->exec($end_update);
                                                    $endValue = $end;
                                                }
                                                break;
                                            }
                                        }
                                        //inserting new row
                                        $checking = "select * from timesheet_data where (start_time='$start' and end_time='$end') 
                                                      and userid='$id'";
                                        $resCheck = $conn->query($checking);
                                        if ($resCheck->rowCount() == 0 && $start < $end) {
                                            //inserting new row at begining
                                            if ($end <= $first_startValue) {

                                                $insert_begining = "insert into timesheet_data(start_time,end_time,userid,worktypeid)
                                            values('$start','$end','$userid',$workid)";
                                                $conn->exec($insert_begining);
                                                break;
                                            }
                                            //inserting row at ending
                                            else if ($start >= $end1) {

                                                $insert_ending = "insert into timesheet_data(start_time,end_time,userid,worktypeid)
                                            values('$start','$end','$userid',$workid)";
                                                $conn->exec($insert_ending);
                                                break;
                                            }
                                            //inserting new row in between  
                                            else {
                                                $result3 = $conn->query($query);
                                                foreach ($result3 as $row3) {
                                                    $prev_start = $row3["start_time"];
                                                    $prev_end = $row3["end_time"];
                                                    $work_id = 0;

                                                    $second_query = "select * from timesheet_data where (start_time >= '$prev_end') 
                                             and  userid='$userid'";
                                                    $result2 = $conn->query($second_query);
                                                    foreach ($result2 as $res2) {
                                                        $latest_end = $res2['end_time'];
                                                        $latest_start = $res2['start_time'];
                                                        if ($resCheck->rowCount() == 0 && $start >= $prev_end) {
                                                            if ($end <= $latest_start) {

                                                                $insert = "insert into timesheet_data(start_time,end_time,userid,worktypeid)
                                                        values('$start','$end','$userid','$workid')";
                                                                $conn->exec($insert);
                                                                echo "inserted";
                                                                break 2;
                                                            }
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                echo "</tbody>";
                                ?>

                        </table>

                    </div>
                </div>

                <div class="row">
                    <div class="col-1 pe-0">
                        <input type="hidden" name="changedId" value="idvalue" id="changingId">
                        <input type="hidden" name="flagVal" id="flagVal" value="false">
                        <a href="dashboard.php">
                            <button type="button" class='btn border btn-dark rounded'>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col-9 pe-0">
                        <input type="submit" name="submit" value="save " class="bg-primary submit border rounded p-1">
                    </div>
                    <div class="col ps-0">
                        <input type="button" name="add" value="Add new row" class="bg-success add border rounded p-1 "
                            id="addNewRow">

                    </div>

                </div>
        </div>
        </form>
    </div>
    </div>
    <a href="">

    </a>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>

        $(document).ready(function () {


            $('.del').click(function () {

                  if (window.confirm('ARE YOU SURE?')) {
                    window.location.href = "deleteRecord.php?id=<?php   echo $res['id']  ; ?>";
                } else {
                    // Handle the 'No' case or do nothing
                }

            });
         
            $('.submit').submit(function (event) {
                event.preventDefault();
                window.location.href = 'dashboard.php';
            });
            $('.drag li').draggable({
                helper: 'clone',
            });


       
            // function dragAndDrop($element) {
            //     $element.draggable({
            //         helper: 'clone',
            //     });
            //     $element.droppable(
            //         {
            //             accept: 'li',
            //             drop: function (event, ui) {
            //                 var data = $(this).attr('id');
            //                 $('.drop input#' + data).val(ui.draggable.clone().text());
            //             }
            //         }
            //     );
            // }

            function dragAndDrop(element) {
                $('.drag li').draggable({
                    helper: 'clone',
                });
                $('.drop input').droppable(
                    {
                        accept: 'li',
                        drop: function (event, ui) {
                            var data = $(this).attr('id');
                            $('.drop input#' + data).val(ui.draggable.clone().text());
                        }
                    }
                );
            }
            //For dropping dragged elements
            $('#addingCon input').each(function () {
                dragAndDrop($(this));
            });

            //creating new row and making new fields drag and drop
            let newRowID = <?php echo $id; ?>;
            $('.add').click(function () {

                let newRow = `< tr id = 'row-${newRowID}' class='drop' >
                <td><input name='work-${newRowID}' id='work-${newRowID}' value='' ></td>
               <td> <input  name='start-${newRowID}' id='start-${newRowID}' value=''></td>              
               <td> <input  name='startminute-${newRowID}' id='startminute-${newRowID}' value=''></td>
               <td> <input name='end-${newRowID}' id='end-${newRowID}' value=''></td>
               <td> <input name='endminute-${newRowID}' id='endminute-${newRowID}' value=''></td>
                </tr >`;
                $('#edittable').append(newRow);
             
                let flg = document.getElementById("flagVal").value;
                flg = true;
                $('#flagVal').val(flg);
                newRowID++;
                // console.log(newRowID);
                $('#changingId').val(newRowID);
                existed_rowCount = <?php echo $id; ?>;
                //making newly added elements droppable
                dragAndDrop($('#row-' + existed_rowCount + ' input#work-' + existed_rowCount));
                dragAndDrop($('#row-' + existed_rowCount + ' input#start-' + existed_rowCount));
                dragAndDrop($('#row-' + existed_rowCount + ' input#end-' + existed_rowCount));
                existed_rowCount++;

            })
        });
    </script>
</body>

</html>