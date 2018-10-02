<?php
session_start();
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

if(!$user->get("Verified"))
{
    header("Location: verify.php");
    exit();
}

$curTime = idate('H');
include("includes/header.php");
?>
<div class="filter-back">
    <p>This is your timetable. Select <u><a href="preferences.php">courses</a></u> that you are enrolled in and then add them to your table on the <u><a href="edit.php">edit</a></u> page.</p>
</div>
<div class="calender">
    <!-- DAYS START -->
    <div class="wrap-days">
        <div class="day">
            <?php
            for($x = 8; $x < 20; $x++)
            {
                $isCur = $curTime == $x ? 'isCur': '';
                if($x < 13){$time = $x;$local = "AM";}else{$time = $x - 12;$local = "PM";}
                if($x == 12){$local = "PM";}
                echo    '<div class="hour ' . $isCur . '" style="overflow: hidden;">
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
                    $isCur = $curTime == $x ? 'isCur': '';
                    $unitCode = $day . $x;
                    echo '<div class="' . $isCur . ' hour unit' . $unitCode . '">';
                    $queryFriend = "SELECT Sender, Receiver FROM Friends WHERE (Receiver = '$myId' OR Sender = '$myId') AND Active = '1'";
                    if($result = $mysqli->query($queryFriend))
                    {
                        while($row = $result->fetch_assoc())
                        {
                            if($_SESSION['Id'] == $row['Receiver']){$userId = $row['Sender'];}else{$userId = $row['Receiver'];}
                            $query = "SELECT Id, CourseCode FROM Preferences WHERE UnitCode = '$unitCode' AND UserId = '$userId'";
                            if($resultAssoc = $mysqli->query($query))
                            {
                                if($mysqli->affected_rows > 0)
                                {
                                    $prefassoc = $resultAssoc->fetch_assoc();
                                    $queryUser = "SELECT FirstName, LastName FROM Users WHERE Id = '$userId'";
                                    if($resultUser = $mysqli->query($queryUser))
                                    {
                                        $assoc = $resultUser->fetch_assoc();
                                        if($userId == $_SESSION['Id'])
                                        {
                                            echo    '<script>
                                                        $(document).ready(function(){
                                                            $(".unit' . $unitCode . '").css("background-color", "#ccf2f4");
                                                        });
                                                    </script>';
                                        }
                                        if(!empty($assoc['LastName']) && !empty($assoc['LastName']))
                                        {
                                            echo    '<div class="person">
                                                        <div class="dp no-dp" style="background-color: ' . genColorCodeFromText($assoc['FirstName'].$assoc['LastName'].$userId,50,7) . '">
                                                            <p class="dp-text">' . substr($assoc['FirstName'], 0, 1) . substr($assoc['LastName'], 0, 1) . '</p>
                                                        </div>
                                                        <p class="first-name">' . $assoc['FirstName'] . ' ' . $assoc['LastName'] . '</p>
                                                        <p class="last-name">' . $prefassoc['CourseCode'] . '</p>
                                                    </div>';
                                        }else{
                                            echo    '<div class="person">
                                                        <div class="dp no-dp" style="background-color: ' . genColorCodeFromText("FredBlogs",50,10) . '">
                                                            <p class="dp-text">' . substr("Fred", 0, 1) . substr("Blogs", 0, 1) . '</p>
                                                        </div>
                                                        <p class="first-name">Fred</p>
                                                        <p class="last-name">Blogs</p>
                                                    </div>';
                                        }
                                        $resultUser->free();
                                    }
                                }
                                $resultAssoc->free();
                            }
                        }
                    }
                    $query = "SELECT Id, CourseCode FROM Preferences WHERE UnitCode = '$unitCode' AND UserId = '$myId'";
                    if($resultAssoc = $mysqli->query($query))
                    {
                        if($mysqli->affected_rows > 0)
                        {
                            $prefassoc = $resultAssoc->fetch_assoc();
                            $queryUser = "SELECT FirstName, LastName FROM Users WHERE Id = '$myId'";
                            if($resultUser = $mysqli->query($queryUser))
                            {
                                $assoc = $resultUser->fetch_assoc();
                                    echo    '<script>
                                                $(document).ready(function(){
                                                    $(".unit' . $unitCode . '").css("background-color", "#ccf2f4");
                                                });
                                            </script>';
                                if(!empty($assoc['LastName']) && !empty($assoc['LastName']))
                                {
                                    echo    '<div class="person">
                                                <div class="dp no-dp" style="background-color: ' . genColorCodeFromText($assoc['FirstName'].$assoc['LastName'].$myId,50,7) . '">
                                                    <p class="dp-text">' . substr($assoc['FirstName'], 0, 1) . substr($assoc['LastName'], 0, 1) . '</p>
                                                </div>
                                                <p class="first-name">' . $assoc['FirstName'] . ' ' . $assoc['LastName'] . '</p>
                                                <p class="last-name">' . $prefassoc['CourseCode'] . '</p>
                                            </div>';
                                }else{
                                    echo    '<div class="person">
                                                <div class="dp no-dp" style="background-color: ' . genColorCodeFromText("FredBlogs",50,10) . '">
                                                    <p class="dp-text">' . substr("Fred", 0, 1) . substr("Blogs", 0, 1) . '</p>
                                                </div>
                                                <p class="first-name">Person Information</p>
                                                <p class="last-name">Not Found</p>
                                            </div>';
                                }
                                $resultUser->free();
                            }
                        }
                        $resultAssoc->free();
                    }
                    echo '</div>';
                }
                echo '</div>';
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