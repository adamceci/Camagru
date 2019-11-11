<?php
    if (isset($_SESSION) && array_key_exists('current_user', $_SESSION) && !empty($_SESSION['current_user'])) {
?>

        <p class="profile_texts">Login: <?php echo htmlspecialchars($_SESSION['current_user']); ?></p>
        <p class="profile_texts">Email: <?php echo htmlspecialchars($_SESSION['current_user_email']); ?></p>
        <p class="profile_texts">Notification
            Email: <?php echo htmlspecialchars($_SESSION['current_user_notification_email']); ?></p>
        <p class="profile_texts">profile_pic: <img
                    src="<?php echo htmlspecialchars($_SESSION['current_user_pic']); ?>"></p>
        <a href="profile&update=password">
            <button class="profile_change_buttons">Change password</button>
        </a>
        <a href="profile&update=login">
            <button class="profile_change_buttons">Change login</button>
        </a>
        <a href="profile&update=email">
            <button class="profile_change_buttons">Change email</button>
        </a>
        <a href="profile&update=notification_email">
            <button class="profile_change_buttons">Change notification mail</button>
        </a>
        <a href="profile&update=profile_pic">
            <button class="profile_change_buttons">Change profile picture</button>
        </a>
<?php
    } else {
        echo "You don't have the rights to be here!";
    }
    ?>