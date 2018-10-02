<div class="left">
    <div class="left-head">
        <a href="index.php">
            <h1>bantanet</h1>
        </a>
    </div>
    <div class="left-overflow">
        <ul class="left-menu">
            <li class="left-menu-title"><p>Menu</p></li>
            <?php if(!empty($_SESSION['Id']) && !isset($logout)): ?>
            <a href="timetable.php"><li><p><span class="icon-home"></span> Home</p></li></a>
            <?php if(!$user->get("Verified")): ?>
            <a href="verify.php"><li><p><span class="icon-clipboard"></span> Verify Account</p></li></a>
            <?php endif; ?>
            <a href="preferences.php"><li><p><span class="icon-clipboard"></span> Courses</p></li></a>
            <a href="edit.php"><li><p><span class="icon-edit"></span> Edit</p></li></a>
            <a href="timetable.php"><li><p><span class="icon-th-small"></span> Timetable</p></li></a>
            <li class="friend-op-button"><p><span class="icon-group"></span> Friends
            <?php 
            if($mysqli->query("SELECT Sender, Active FROM Friends WHERE Receiver = '$myId' AND Active = '0'"))
            {
                if($mysqli->affected_rows > 0)
                {
                    $numReqsLeft = $mysqli->affected_rows;
                    echo '('.$numReqsLeft.')';
                }
            } 
            ?><span class="icon-th-menu" style="float:right;padding:0 10px;"></span></p></li>
            <div class="friend-op-wrap" style="display:none;">
                <a href="friends.php"><li class="highlight"><p>My Friends</p></li></a>
                <a href="find.php"><li><p>Find</p></li></a>
                <a href="requests.php"><li><p>Requests <?php if($mysqli->affected_rows > 0) echo '('.$numReqsLeft.')'; ?></p></li></a>
            </div>
            <a href="update.php"><li><p><span class="icon-cog"></span> Account</p></li></a>
            <a href="logout.php"><li><p><span class="icon-power"></span> Logout</p></li></a>
            <?php else: ?>
            <a href="index.php"><li><p><span class="icon-home"></span> Home</p></li></a>
            <a href="login.php"><li><p><span class="icon-power"></span> Login</p></li></a>
            <a href="signup.php"><li><p><span class="icon-user-add"></span> Sign Up</p></li></a>
            <?php endif; ?>
        </ul>
    </div>
</div>