<?php

if (input_useable($_SESSION, 'current_user') && input_useable($_SESSION, 'current_user_pic')) {

?>
	<div class="header">
        <div><a href="index">CAMAGRU</a></div>
        <div class="blank"></div>
        <div>
            <a class="current_user_link_header" href="profile">
                <p class="current_user_header"><?= htmlspecialchars(($_SESSION["current_user"])); ?></p>
                <img class="profile_pic_header" src="./<?= htmlspecialchars($_SESSION['current_user_pic']) ;?>"
            </a>
        </div>
        <div><a href="logout"><button class="clickeable" type="button">Log out</button></a></div>
    </div>
<?php

}
else {

?>
	<div class="header">
        <div><a href="index">CAMAGRU</a></div>
		<div class="blank"></div>
		<div><a class="current_user" href="sign_up">Sign up</a></div>
        <div><a href="login"><button class="clickeable" type="button">Log in</button></a></div>
	</div>
<?php

}

?>
<div class="error_wrapper">

</div>

<div class="success_wrapper">
    <?php
    if (input_useable($_SESSION, 'success')) {
        $msg = Controller::show_success_msg();
        echo "<p class='success_msg'>" . $msg . "</p>";
    }
    ?>
</div>

