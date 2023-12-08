<?php
session_start(); // Start or resume the session
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('connection.php');
$id = $_SESSION['id'];
$username = $_SESSION['name'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- css styles -->
    <link rel="stylesheet" href="project.css">
</head>

<body>
    <h1 class="text-center">Dashboard</h1>
    <div class="container d-block m-auto border border-bottom mt-4 ">
        <div class="row border-bottom border-dark">
            <div class="col-9">Welcome
                <?php echo " " . $username; ?>!
            </div>
            <div class="col-1 mt-2 " id="add">
                <h3><i class="fa-solid fa-plus align-center"></i></h3>
            </div>
            <div class="col-2  mt-2" id="hamburger">
                <a href="" class='btn ' data-bs-toggle="collapse" data-bs-target="#options">
                    <h3> <i class="fa-solid fa-bars me-3 "></i></h3>
                </a>
                <div id="options" class="collapse">
                    <a href="profile.php">Profile</a><br>
                    <?php if ($role === 'Admin' || $role === 'HR') { ?>
                        <a href="createUser.php">Create User </a><br>
                        <a href="manageUser.php">Manage User</a><br>
                    <?php } ?>
                    <a href="login.php">Log out</a>
                </div>
            </div>
        </div>

        <div class="row mx-1 w-100">
            <?php
            $queryCombined = "
                SELECT DISTINCT date 
                FROM user_details 
                WHERE date > (
                    SELECT date_sub(max(date), INTERVAL 5 DAY) 
                    FROM user_details
                ) 
                AND date <= (
                    SELECT max(date) 
                    FROM user_details
                ) 
                AND empid = :id
                ORDER BY date DESC
            ";
            $stmt = $conn->prepare($queryCombined);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result1) {
                $prev = 1;
                foreach ($result1 as $res1) {
                    echo "<div class='col-4  px-0 mt-3  border' id='day-$prev'>
                            <h5 class='text-center'>Day-$prev</h5>";
                    $DOF = $res1['date'];
                    echo "<h6 class='text-center h66'  id='date-$DOF'>Date-" . $DOF . "</h6>
                          <div class='border border-bottom'></div>";

                    $selectid = "SELECT id FROM user_details WHERE date=:DOF AND empid=:id";
                    $idresult = $conn->prepare($selectid);
                    $idresult->bindParam(':DOF', $DOF);
                    $idresult->bindParam(':id', $id);
                    $idresult->execute();

                    if ($idresult) {
                        $rec = $idresult->fetch(PDO::FETCH_ASSOC);
                        $userid = $rec['id'];

                        $query2 = "SELECT * FROM timesheet_data WHERE userid=:userid ORDER BY start_time ASC";
                        $result2 = $conn->prepare($query2);
                        $result2->bindParam(':userid', $userid);
                        $result2->execute();

                        foreach ($result2 as $res) {
                            $workid = $res["worktypeid"];
                            $workquery = "SELECT work_type FROM work_type WHERE id=:workid";
                            $workexec = $conn->prepare($workquery);
                            $workexec->bindParam(':workid', $workid);
                            $workexec->execute();

                            if ($workexec) {
                                $workfetch = $workexec->fetch(PDO::FETCH_ASSOC);
                                $work = $workfetch['work_type'];

                                $starttime = $res["start_time"];
                                $starthour = (int) ($starttime / 60);
                                $startminute = (int) ($starttime % 60);
                                $endtime = $res["end_time"];
                                $endhour = (int) ($endtime / 60);
                                $endminute = (int) ($endtime % 60);

                                echo '<div class="row mx-0 w-100  ms-3 me-2 my-2 text-center">
                                        <div class=" width col-4 px-0 py-2  mx-1  border">' . $work . '</div>
                                        <div class="col-4 px-0 py-2 w-25 mx-1  border">' . $starthour . ":" . ((strlen((string) $startminute) > 1) ? $startminute : ('0' . $startminute)) . '</div>
                                        <div class="col-4 px-0 py-2 w-25 mx-1 border">' . $endhour . ":" . ((strlen((string) $endminute) > 1) ? $endminute : ('0' . $endminute)) . '</div>
                                      </div>';
                            }
                        }

                        $blockId = 'day-' . $prev;
                        $blockDate = $DOF;
                        ?>

                        <div class="row">
                            <div class="col d-flex justify-content-around pe-0">
                                <button name='edit' data-param='<?php echo $blockId; ?>' data-param1='<?php echo $blockDate; ?>' class='edit btn align-center btn-light border '>Edit</button>
                                <button type="button" onclick="deleteDate('<?php echo $blockDate; ?>')" id='delete-<?php echo $prev; ?>' class='btn align-center btn-light border'>Delete</button>
                            </div>
                        </div>

                        <?php
                        echo "</div> ";
                        $prev++;
                    }
                }
            }
            ?>
        </div>
    </div>
</body>

</html>

        <script>
        function deleteDate(date) {
            var deletedate = date;
            if (window.confirm('ARE YOU SURE TO DELETE?')) {
                window.location.href = "delete.php?date=" + deletedate;
            } else {
                // Handle the 'No' case or do nothing
            }
            // var confirmation = confirm("Do you want to delete?");

            //     if (confirmation) {
            //         // If the user clicks "OK", navigate to page1.html
            //         window.location.href = "delete.php?date="+deletedate;
            //     } else {
            //         // If the user clicks "Cancel", navigate to page2.html
            //     console.log("not deleting");
            //     }

        }


        $(document).ready(function() {
            $('#add').click(function() {
                window.location.href = "timetracker.php";
            });

            $('.edit').click(function() {
                var date = $(this).data('param1');
                window.location.href = 'editmain.php?date=' + date;
                var id = JSON.stringify($(this).data('param'));

            });



        });
        </script>
