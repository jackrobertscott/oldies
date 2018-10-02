 <!-- PROFILE START-->
<div class="wrap-profile">
    <div class="dp-border">
        <div class="element"></div>
    </div>
    <p class="profile-name">Jack Scott</p>
    <div class="profile-friends">
        <?php
            for($i = 0; $i < 10; $i++)
            {
                echo    '<div class="wrap-friend">
                            <div class="dp"><p class="friend dp-text">FB</p></div>
                            <p class="friend">Fred Blogs</p>
                        </div>';
            }
        ?>
    </div>
</div>
<!-- PROFILE END -->

/**
* Profile
*/

.wrap-profile {
  float: left;
  width: 240px;
  height: 1580px;
  padding-bottom: 16px;
  background-color: rgb(62, 57, 57);
}

.wrap-friend {
  float: left;
  width: 100%;
  height: 42px;
}

.wrap-friend .dp {
  position: relative;
  margin-left: 20px;
  background-color: rgb(215, 215, 215);
}

.wrap-friend .dp-text {
  position: relative;
}

.dp-border {
  width: 130px;
  height: 130px;
  margin: 30px auto 0;
  background-color: rgb(222, 222, 222);
}

.element {
  float: left;
  width: 120px;
  height: 120px;
  margin: 5px 0 0 5px;
  background-image: url('../images/logo_small.png');
  background-size: cover;
  background-position: center center;
}

.friend {
  float: left;
  width: 73.3333333333%;
  margin: 15px 0 0 5%;
  font-size: 1em;
  font-weight: 400;
  line-height: 1.38;
  color: rgb(250, 250, 250);
}

.friend-name {
  float: left;
  width: 73.3333333333%;
  font-size: 1em;
  font-weight: 400;
  line-height: 1.38;
  color: rgb(250, 250, 250);
  margin: -24px 0 0 26.6666666666%;
}

.profile-friends {
  float: left;
  width: 100%;
  margin-top: 14px;
  padding-top: 10px;
  border-top: 1px solid rgb(215, 215, 215);
}

.profile-name {
  float: left;
  clear: both;
  width: 100%;
  margin-top: 16px;
  font-size: 1em;
  font-weight: 400;
  line-height: 1.38;
  text-align: center;
  color: rgb(215, 215, 215);
}
