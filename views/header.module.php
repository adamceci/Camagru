<?php



if (input_useable($_SESSION, 'current_user') && input_useable($_SESSION, 'current_user_pic')) {

?>
	<div class="header">
        <div id="elem1">
            <a href="index"><img class="profile_cat_img" src="assets/imgs/tebe.png" /></a>
            <a id="camagru" href="index">CAMAGRU</a>
        </div>
        <div id="elem2" class="blank"></div>
        <div id="elem3">
            <a class="current_user_link_header" href="profile">
                <p class="current_user_header"><?= htmlspecialchars(($_SESSION["current_user"])); ?></p>
                <div class="profile_pic_wrapper_header">
                    <?php if (file_exists("./assets/profile_pics/" . $_SESSION['current_user_pic'])) { ?>
                        <img class="profile_pic_header" src="./assets/profile_pics/<?= htmlspecialchars($_SESSION['current_user_pic']) ;?>" />
                    <?php } else {?>
                        <img class="profile_pic_header" src="./assets/profile_pics/default.png">
                    <?php } ?>
                </div>
            </a>
        </div>
        <div id="elem4"><a href="logout"><button class="clickeable" type="button">Log out</button></a></div>
    </div>
<?php

}
else {

?>
	<div class="header">
        <div id="elem1">
            <a href="index"><img class="profile_cat_img" src="assets/imgs/tebe.png" /></a>
            <a href="index">CAMAGRU</a>
        </div>
		<div id="elem2" class="blank"> </div>
		<div id="elem3"><a class="current_user" href="sign_up">Sign up</a></div>
        <div id="elem4"><a href="login"><button class="clickeable" type="button">Sign in</button></a></div>
	</div>
<?php

}

?>
<div class="error_wrapper">
    <?php
     if (input_useable($_SESSION, 'errors')) {
        foreach ($_SESSION['errors'] as $error)
            echo "<p class='error'>$error</p>";
        $_SESSION['errors'] = "";
    }
    ?>
</div>

<div class="success_wrapper">
    <?php
    if (input_useable($_SESSION, 'success')) {
        $msg = Controller::show_success_msg();
        echo "<p class='success_msg'>" . $msg . "</p>";
    }

    ?>
</div>

