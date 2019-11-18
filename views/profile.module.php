<?php


    if (isset($_SESSION) && array_key_exists('current_user', $_SESSION) && !empty($_SESSION['current_user'])) {
?>
        <div class="profile_description">
            <p>Welcome to your profile page!<br/>
            <p>Here you can modify your data</p>
        </div>
        <div class="profile_wrapper">
            <p class="profile_texts profile_first_input"><?php echo htmlspecialchars($_SESSION['current_user']); ?></p>
            <div class="update_buttons_login">
                <a class="profile_buttons_links" href="profile&update=login">
                    <button class="profile_change_buttons">Change login</button>
                </a>
                <a class="profile_buttons_links" href="profile&update=password">
                    <button class="profile_change_buttons">Change password</button>
                </a>
                <a class="profile_buttons_links" href="profile&update=profile_pic">
                    <button class="profile_change_buttons">Change profile picture</button>
                </a>
            </div>
            <p class="profile_email_input profile_texts">Email<br/><?php echo htmlspecialchars($_SESSION['current_user_email']); ?></p>
            <div class="update_buttons_email">
                <a class="profile_buttons_links" href="profile&update=email">
                    <button class="profile_change_buttons">Change email</button>
                </a>
            </div>
            <p class="profile_notif_email_input profile_texts">Notification email<br/><?php echo htmlspecialchars($_SESSION['current_user_notification_email']); ?></p>
            <div class="update_button_notif">
                <a class="profile_buttons_links" href="profile&update=notification_email">
                    <button class="profile_change_buttons">Change notification mail</button>
                </a>
                    <form method="POST" action="profile&update=notification_active">
                    <?php if ($_SESSION['current_user_notification_active'] === '1') { ?>
                        <input type="submit" name="update_notification_active" class="profile_change_buttons" value="Disable notifications">
                    <?php } else {?>
                        <input type="submit" name="update_notification_active" class="profile_change_buttons" value="Enable notifications">
                    <?php } ?>
                    </form>
            </div>
            <?php if (file_exists("./assets/profile_pics/" . $_SESSION['current_user_pic'])) { ?>
                <img class="profile_profile_pic" src="./assets/profile_pics/<?php echo htmlspecialchars($_SESSION['current_user_pic']); ?>">
            <?php } else { ?>
                <img class="profile_profile_pic" src="./assets/profile_pics/default.png">
            <?php } ?>
        </div>
<?php
    } else {
        header("Location: index");
    }
    ?>