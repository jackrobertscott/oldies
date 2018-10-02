<?php
session_start();
$TITLE = "Edit Timetable";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

$id = $user->get("Id");

if(!$user->get("Verified"))
{
    header("Location: verify.php");
    exit();
}

$query = "SELECT CourseJSONObj FROM Users WHERE Id = '$id'";
if($result = $mysqli->query($query))
{
    $row = $result->fetch_assoc();
    $tempArray = json_decode($row['CourseJSONObj'], true);
    if(is_array($tempArray))
    {
        foreach($tempArray as $cid)
        {
            $query = "SELECT CourseCode, Id FROM Courses WHERE Id = '$cid'";
            if($result = $mysqli->query($query))
            {
                while($row = $result->fetch_assoc())
                {
                    $optArray[] = $row;
                }
                $result->free();
            }else{
                $errors[] = "QUERY TO SERVER FAILED: " . $mysqli->error;
            }
        }
    }
}else{
    $errors[] = "QUERY TO SERVER FAILED: " . $mysqli->error;
}

$thisUser = $_SESSION['Id'];
include("includes/header.php");
?>
<div class="filter-back">
    <p>You can add more option to your timetable by visiting the <u><a href="preferences.php">courses</a></u> page.</p>
</div>
<div class="calender">
    <!-- DAYS START -->
    <div class="wrap-days">
        <div class="day">
            <?php
            for($x = 8; $x < 20; $x++)
            {
                if($x < 13){$time = $x;$local = "AM";}else{$time = $x - 12;$local = "PM";}
                if($x == 12){$local = "PM";}
                echo    '<div class="hour" style="overflow: hidden;">
                            <p class="hour-text">' . $time . ':00' . $local . '</p>
                        </div>';
            }
            ?>
        </div>
        <?php
        $uni = $user->get("UniversityId");
        for($r = 0; $r < 5; $r++)
        {
            echo    '<div class="day">';
                        switch($r){
                            case 0: $day = "MON"; break; 
                            case 1: $day = "TUE"; break; 
                            case 2: $day = "WED"; break; 
                            case 3: $day = "THU"; break; 
                            case 4: $day = "FRI"; break;
                        }
                        for($x = 8; $x < 20; $x++)
                        {
                            $unitCode = $day . $x;
                            echo '<div class="hour unit' . $unitCode . '">
                                        <ul>';
                            if(is_array($optArray))
                            {
                                foreach($optArray as $val){
                                    $status = "";
                                    $tempcid = $val['Id'];
                                    $query = "SELECT Id FROM Preferences WHERE UnitCode = '$unitCode' AND UserId = '$thisUser' AND CourseId = '$tempcid'";
                                    if($mysqli->query($query))
                                    {
                                        if($mysqli->affected_rows > 0)
                                        {
                                            $status = " highlight";
                                        }
                                    }
                                    if(!empty($status))
                                    {
                                        echo    '<script>
                                                    $(document).ready(function(){
                                                        $(".unit' . $unitCode . '").css("background-color", "#dff5fa");
                                                    });
                                                </script>';
                                    }
                                    echo '<li class="courseId_'.$val['Id'].' uniId_'.$uni.' unitCode_'.$unitCode.' courseCode_'.$val['CourseCode'].$status.'"><p>'.$val['CourseCode'].'</p></li>';
                                }
                            }
                            $status = "";
                            $query = "SELECT Id FROM Preferences WHERE UnitCode = '$unitCode' AND UserId = '$thisUser' AND CourseId = '1'";
                            if($mysqli->query($query))
                            {
                                if($mysqli->affected_rows > 0)
                                {
                                    $status = " highlight";
                                }
                            }
                            if(!empty($status))
                            {
                                echo    '<script>
                                            $(document).ready(function(){
                                                $(".unit' . $unitCode . '").css("background-color", "#dff5fa");
                                            });
                                        </script>';
                            }
                            echo       '<li class="courseId_1 uniId_'.$uni.' unitCode_'.$unitCode.' courseCode_FREETIME'.$status.'"><p>Free Time @ Uni</p></li>';
                            echo       '</ul>
                                    </div>';
                        }
            echo        '</div>';
        }
        ?>
        <div class="wrap-day-title">
            <?php
            for($r = 0; $r < 6; $r++)
            {
                echo    '<div class="day-title">
                            <p class="day-title-text">';
                            switch($r){
                                case 0: echo 'Time'; break;
                                case 1: echo 'Monday'; break; 
                                case 2: echo 'Tuesday'; break; 
                                case 3: echo 'Wednesday'; break; 
                                case 4: echo 'Thursday'; break; 
                                case 5: echo 'Friday'; break;
                            }
                echo        '</p>
                        </div>';
            }
            ?>
        </div>
    </div>
    <!-- DAYS END -->
</div>
<?php
include("includes/footer.php");
?>