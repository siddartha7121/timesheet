<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('connection.php');
$id = $_SESSION['id'];
$username = $_SESSION['name'];
//getting data from url
$date = $_GET['date'];
$count;
// Accessing session variable
$pri = ['development' => 1, 'testing' => 2, 'projectmanagement' => 3, 'research' => 4, 'meetings' => 5, 'training' => 6, 'support' => 7];
if (isset($_POST['submit'])) {
    $delete_record = "delete from timesheet_data where date='$date'";
    $conn->exec($delete_record);
    $date1 = $date;
  // Redirect using JavaScript
        $query = "INSERT INTO user_details(empid, date) VALUES ('$id', '$date1')";
        $conn->exec($query);
        for ($i = 1; $i <= (count($_POST) - 1) / 5; $i++) {
            $work = $_POST['work-' . $i];
            if ($work) {
                $workid = $pri["$work"];
                $starttime = $_POST['starttime-' . $i];
                $start_minute = $_POST['startminute-' . $i];
                $start_time = (int) (((int) $starttime * 60) + (int) $start_minute);
                $endtime = $_POST['endtime-' . $i];
                $end_minute = $_POST['endminute-' . $i];
                $end_time = (int) (((int) $endtime * 60) + (int) $end_minute);
                $query = "INSERT INTO timesheet_data (start_time, end_time, userid, worktypeid ,date) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$start_time, $end_time, $id, $workid, $date1 ]);
            }
        }
        echo "<script>window.location.href = 'dashboard.php';</script>";
} else {
    echo "you cannot fill time sheet check once <?>";
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
                        <input type="date" class="dateFormat" name="datee"
                            value="<?php echo htmlspecialchars($date); ?>" disabled>
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
                                $selectid = "select * from timesheet_data where date='$date' and userid='$id'";
                                $idresult = $conn->query($selectid);
                                $pri_rev = ['development',  'testing',  'projectmanagement',  'research',  'meetings',   'training',  'support'];
                                // $rec = $idresult->fetch(PDO::FETCH_ASSOC);
                                $i = 1; // Initialize the counter
                                while ($rec = $idresult->fetch(PDO::FETCH_ASSOC)) {
                                    $idd = $rec['worktypeid'];
                                    $work = $pri_rev[$idd - 1];
                                    // Convert start_time to hours and minutes
                                    $start_hours = floor($rec['start_time'] / 60);
                                    $start_minutes = $rec['start_time'] % 60;
                                    // Convert end_time to hours and minutes
                                    $end_hours = floor($rec['end_time'] / 60);
                                    $end_minutes = $rec['end_time'] % 60;
                                    echo "<div class='row px-1 mx-1 mb-2 w-100 d-flex'>
                                            <div class='col-4 px-0 '>
                                                <input type='text' class='w-100 p-2 mt-2 dropping' name='work-" . $i . "' id='work-" . $i . "' value='" . $work . "'></input>
                                            </div>
                                            <div class='col-4 px-0 d-flex '>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='starttime-" . $i . "' id='starttime-" . $i . "' value='" . $start_hours . "'></input>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='startminute-" . $i . "' id='startminute-" . $i . "' value='" . $start_minutes . "'></input>
                                            </div>
                                            <div class='col-4 px-0 d-flex '>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='endtime-" . $i . "' id='endtime-" . $i . "' value='" . $end_hours . "'></input>
                                                <input type='number' class='w-100 p-2 mt-2 dropping' name='endminute-" . $i . "' id='endminute-" . $i . "' value='" . $end_minutes . "'></input>
                                            </div>
                                        </div>";
                                    $i++;
                                }
                                $count=$i;
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
    var i = <?php echo $count; ?>;
    function dragAndDrop($element) {
        $element.draggable({
            helper: 'clone'
        }).droppable({
            accept: 'li',
            drop: function(event, ui) {
                var data = $(this).attr('id');
                var getValue = (id) => $("#" + id).val();

                if (data.startsWith("work-") || data == "starttime-1") {
                    $('#addingCon input#' + data).val(ui.draggable.clone().text());
                } else if (data.startsWith("endtime-")) {
                    var inputId = `starttime-${data.charAt(data.length - 1)}`;
                    if (ui.draggable.clone().text() >= getValue(inputId)) {
                        $('#addingCon input#' + data).val(ui.draggable.clone().text());
                    } else alert("end time should be higher than start time :)");
                } else if (data.startsWith("starttime-")) {
                    var inputId = `endtime-${data.charAt(data.length - 1)-1}`;
                    if (ui.draggable.clone().text() >= getValue(inputId)) {
                        $('#addingCon input#' + data).val(ui.draggable.clone().text());
                    } else alert("start time should be higher than previous end time :)");
                } else if (data.startsWith("startminute-") || data.startsWith("endminute-")) {
                    if (data == "startminute-1") {
                        $('#addingCon input#' + data).val(ui.draggable.clone().text());
                    } else if (data == 'endminute-1' && getValue(`startminute-1`) < ui.draggable.clone()
                        .text()) {
                        $('#addingCon input#' + data).val(ui.draggable.clone().text());
                    } else if ((getValue(`starttime-${data.charAt(data.length - 1)}`) == getValue(
                            `endtime-${data.charAt(data.length - 1)}`)) ||
                        (getValue(`starttime-${data.charAt(data.length - 1)}`) == getValue(
                            `endtime-${data.charAt(data.length - 1)-1}`))) {
                        if ((data.startsWith("startminute-") && getValue(
                                    `endminute-${data.charAt(data.length - 1)-1}`) < ui.draggable.clone()
                                .text()) ||
                            (data.startsWith("endminute-") && getValue(
                                    `startminute-${data.charAt(data.length - 1)}`) < ui.draggable.clone()
                                .text())) {
                            $('#addingCon input#' + data).val(ui.draggable.clone().text());
                        }
                    } else {
                        $('#addingCon input#' + data).val(ui.draggable.clone().text());
                    }
                } else {
                    alert("Invalid input");
                }
            }
        });
    }
    $(document).ready(function() {
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
            dragAndDrop($('#addingCon input#startminute-' + i));
            dragAndDrop($('#addingCon input#endtime-' + i));
            dragAndDrop($('#addingCon input#endminute-' + i));
            i++;
        });
    });
    </script>
</body>

</html>