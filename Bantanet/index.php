<?php
session_start();
require('includes/reqdocs.php');

if(!empty($_SESSION['Id']))
{
    header("Location: timetable.php");
    exit();
}

include("includes/header.php");
?>
<ul class="basic-block">
    <li class="one">
        <div class="suai">
            <span>
                <h1>bantanet</h1>
                <h3>Share your university timetable with friends</h3>
            </span>
            <div id="suib">
                <form action="signup.php" method="POST">
                    <input type="text" placeholder="Student Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';}?>>
                    <input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="' . $_POST['password'] . '"';}?>>
                    <input type="password" placeholder="Repeat Password" name="password2" <?php if(!empty($_POST['password2'])){echo 'value="' . $_POST['password2'] . '"';}?>>
                    <input type="text" placeholder="First Name" class="n" name="firstname" <?php if(!empty($_POST['firstname'])){echo 'value="' . $_POST['firstname'] . '"';}?>>
                    <input type="text" placeholder="Last Name" class="n" name="lastname" <?php if(!empty($_POST['lastname'])){echo 'value="' . $_POST['lastname'] . '"';}?>>
                    <p>on clicking submit, you agree to bantanet's <a href="terms-and-conditions.php">Terms and Conditions</a> and <a href="privacy-policy.php">Privacy Policy</a>.</p>
                    <input type="submit" class="s" value="Sign Up">
                    <p class="ti">or <a onclick="clkr()">Log In</a></p>
                </form>
            </div>
            <div id="liib" style="display:none;">
                <form action="login.php" method="POST">
                    <input type="text" placeholder="Email" name="email"<?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';}?>>
                    <input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="' . $_POST['password'] . '"';}?>>
                    <input type="submit" class="s st" value="Log In">
                    <p class="ti ap">or <a onclick="clkr()">Sign Up</a></p>
                </form>
            </div>
            <div>
                <h2><span class="icon-input-checked"></span>Easy View Timetable</h2>
                <h2><span class="icon-plane"></span>Setup In Seconds</h2>
                <h2><span class="icon-world"></span>Share with Friends</h2>
                <h2><span class="icon-adjust-brightness"></span>Sit Back and Relax</h2>
            </div>
        </div>
    </li>
    <li class="two">
        <span>
            <h1>Pick, Edit and Compare Your Timetable.</h1>
            <h3>Then share it with your friends.</h3>
        </span><img src="images/1324809183412.png">
    </li>
    <li class="three">
        <span>
            <h1>Currently Supporting</h1>
            <h3> Students of The University of Western Australia</h3>
            <div class="btn" onclick="window.location.href='contact.php'" style="width: 130px;">Add Your Uni</div>
        </span>
    </li>
</ul>
<script type="text/javascript">
function clkr()
{
    if($('#liib').is(":visible"))
    {
        $('#liib').hide();
        $('#suib').show();
    }else{
        $('#suib').hide();
        $('#liib').show();
    }
}
</script>
<?php
include("includes/footer.php");
?>