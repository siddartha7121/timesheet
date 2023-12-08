<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('connection.php');
// Accessing session variable
$id = $_SESSION['id'];
$username = $_SESSION['name'];
$pri=['development'=>1 , 'testing'=>2 , 'projectmanagement'=>3 , 'research'=>4 , 'meetings'=>5 , 'training'=>6 , 'support'=>7];
if (isset($_POST['submit'])) {
    // Accessing field inputs
    $date1 = $_POST['datee'];
    if ($date1 != null && $date1 <= date('Y-m-d')) {
        // Getting user id with a prepared statement
        $selectid = "SELECT id FROM user_details WHERE date = :date AND empid = :empid";
        $stmt = $conn->prepare($selectid);
        $stmt->bindParam(':date', $date1);
        $stmt->bindParam(':empid', $id);
        $stmt->execute();
        $idrec = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$idrec) {
            // Redirect using JavaScript
            $query = "INSERT INTO user_details(empid, date) VALUES ('$id', '$date1')";
            $conn->exec($query);
            for ($i = 1; $i <= (count($_POST)-2)/5; $i++){
                $work = $_POST['work-' . $i];
                // echo($work);
                if($work){
                $workid=$pri["$work"];
                $starttime = $_POST['starttime-' . $i];       
                $start_minute = $_POST['startminute-' . $i];  
                $start_time = (int) (((int) $starttime * 60) + (int) $start_minute);
                $endtime = $_POST['endtime-' . $i];
                $end_minute = $_POST['endminute-' . $i];
                $end_time = (int) (((int) $endtime * 60) + (int) $end_minute);
                $query = "INSERT INTO timesheet_data (start_time, end_time, userid, worktypeid) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$start_time, $end_time, $id, $workid]);
                echo "<script>window.location.href = 'dashboard.php';</script>";
                }
            }
            
        } else {
            echo "<script>window.location.href = 'dashboard.php';</script>";
        }
    }else{
        echo "you cannot fill time sheet for future <?>";
        }

}

     ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Time Tracker</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"
                integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
            <link rel="stylesheet" href="project.css">
            <!-- font-awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
                integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />
            <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
            <!-- <link rel="stylesheet" href="timetracker.js"> -->
        </head>

        <body>
            <div class="container-fluid border border-dark" id="mcon">
                <div class="  text-center ">
                    <form action="" id='form' method='post'>
                        <div class="row mt-5">
                            <div class="col">
                                <label>Date</label>
                                <input type="date" class="dateFormat" name="datee"></input>
                            </div>
                        </div>
                        <div class="row ms-2">
                            <div class="col-6 ">
                                <div class="row mt-5 ">
                                    <div class="col  " id="drop">
                                        <h4>Time-Filling sheet</h4>
                                    </div>
                                </div>
                                <div class="row pe-2 border px-0">

                                    <div class=" w-100 border border-dark px-0" id='addingCon'>
                                        <div class='row fw-bold'>
                                            <div class='col-4 '>WORK-TYPE</div>
                                            <div class='col-2'>START-HOUR</div>
                                            <div class='col-2'>START-MINUTE</div>
                                            <div class='col-2'>END-HOUR</div>
                                            <div class='col-2'>END-MINUTE</div>
                                        </div>
                                        <?php

                                for ($i = 1; $i <= 5; $i++) {

                                    echo "  <div class='row px-1 mx-1 mb-2 w-100 d-flex'>
                                                <div class='col-4 px-0 '> <input type='text' class='w-100   p-2 mt-2  dropping' name='work-$i' id='work-$i'></input></div>
                                                <div class='col-4 px-0 d-flex '>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='starttime-$i' id='starttime-$i'></input>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='startminute-$i' id='startminute-$i'></input>
                                                </div>
                                                <div class='col-4 px-0 d-flex '>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='endtime-$i' id='endtime-$i'></input>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='endminute-$i' id='endminute-$i'></input></div>
                                                </div>";

                                }

                                ?>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <input type="button" class="btn btn-primary add" value="Add another time-sheet">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row mt-4">

                                    <div class="col-10  border border-dark px-5 d-block m-auto " id="role">
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
                                </div>
                                <div class="row mt-4 me-2 ">
                                    <div class="col-1"></div>
                                    <div class="col-5 border border-dark" id="time">
                                        <h3>Hours</h3>
                                        <ul class="d-flex  flex-wrap drag   justify-content-center  px-0">
                                            <?php
                                    for ($i = 1; $i <= 24; $i++) {
                                        echo "<li class='hours me-1 mt-1  border  border-dark '>$i</li>";
                                    }
                                    ?>
                                        </ul>
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-4 border border-dark " id="minutes">
                                        <h3>Minutes</h3>
                                        <ul class="d-flex  flex-wrap drag justify-content-center px-0">
                                            <?php
                                    for ($i = 0; $i < 60; $i += 5) {
                                        echo "<li class='hours me-1 mt-1 border border-dark'>" . ((strlen((string) $i) > 1) ? $i : ($i)) . "</li>";
                                    }
                                    // ((strlen((string) $i) > 1) ? $i : ('0' . $i))
                                    // for ($i = 0; $i < 60; $i++) {
                                    //     echo "<li class='hours me-1 mt-1 border  border-dark '>". (strlen((string)$i)>1)?$i:(0.$i)."</li>";
                                    //     $i+=5;
                                    // }
                                    ?>
                                        </ul>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                            </div>

                        </div>

                        <!-- ///////////////////////// -->
                        <div class="row mt-3">
                            <div class="col-1">
                                <a href="dashboard.php">
                                    <button class='btn border btn-dark rounded'>
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col-11 px-0">
                                <input type='submit' class="btn text-center btn-light d-flex m-auto   bg-info save"
                                    name="submit" value="Save">
                                <!-- <input value='5' id="noOfRows" class="d-none" name='rows'> -->

                            </div>
                        </div>




                    </form>

                </div>
            </div>


            <script>
            let time_har = 0;
            let time_mnts = 0;
            let count_h = 1;
            let count_m = 1;
            function dragAndDrop($element) {
                $element.draggable({
                    helper: 'clone',
                });
                $element.droppable({
                    accept: 'li',
                    drop: function(event, ui) {
                        var data = $(this).attr('id');
                        if (data.startsWith("work-")) {
                            $('#addingCon input#' + data).val(ui.draggable.clone().text());
                            return; // Exit from the entire block of code
                        } else if (ui.draggable.clone().text() >= time_har && data.startsWith(
                            "starttime-") || data
                            .startsWith("endtime-")) {
                            $('#addingCon input#' + data).val(ui.draggable.clone().text());
                            if (ui.draggable.clone().text() == time_har) {
                                count_h++;
                            }
                            time_har = ui.draggable.clone().text();
                            return; // Exit from the entire block of code
                        } else if (count_h == 2 && ui.draggable.clone().text() > time_mnts && (data
                                .startsWith(
                                    "startminute-") || data.startsWith("endminute-"))) {
                            time_mnts = ui.draggable.clone().text();
                            $('#addingCon input#' + data).val(ui.draggable.clone().text());
                            count_h = 1;
                            count_m++;
                            // alert("hars equal");s
                            return; // Exit from the entire block of code
                        } else if (count_m == 1 && (data.startsWith("startminute-") || data.startsWith(
                                "endminute-"))) {
                            time_mnts = ui.draggable.clone().text();
                            $('#addingCon input#' + data).val(ui.draggable.clone().text());
                            // alert("normal");
                            return; // Exit from the entire block of code
                        } else {
                            alert("Invalid input");
                        }
                        count_m = 1;
                    }
                });
            }

            $(document).ready(function() {
                var i = 6;
                var noOfRows = 5;
                $('#noOfRows').val(noOfRows);

                //For draggable list items
                $('.drag>li').draggable({
                    helper: 'clone',
                });
                //For dropping dragged elements
                $('#addingCon input').each(function() {
                    dragAndDrop($(this));
                });
                var con = document.querySelector('.addingCon');
                $('.add').click(function(event) {
                    event.preventDefault();
                    var data = `
                <div class='row px-0 mx-1 mb-2'>
                        <div class='col-4 px-0'> <input  class='w-100  p-2 mt-2 dropping' name='work-${i}' id='work-${i}'></input></div>
                        <div class='col-4 px-0 d-flex'>
                        <input  class='w-100 p-2 mt-2 dropping' name='starttime-${i}' id='starttime-${i}'></input>
                        <input  class='w-100 p-2 mt-2 dropping' name='startminute-${i}' id='startminute-${i}'></input>
                        </div>
                        <div class='col-4 px-0 d-flex'>
                        <input  class='w-100 p-2 mt-2 dropping' name='endtime-${i}' id='endtime-${i}'></input>
                        <input  class='w-100 p-2 mt-2 dropping' name='endminute-${i}' id='endminute-${i}'></input>
                        </div>
                        </div>
            `;
                    noOfRows++;
                    $('#noOfRows').val(noOfRows);
                    $('#addingCon').append(data);
                    //making newly added elements droppable
                    dragAndDrop($('#addingCon input#work-' + i));
                    dragAndDrop($('#addingCon input#starttime-' + i));
                    dragAndDrop($('#addingCon input#endtime-' + i));
                    i++;
                });


            });
            </script>
        </body>


        </html>