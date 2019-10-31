<?php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
</head>
<body>

    <p class="profile_texts">Login: <?php echo htmlspecialchars($_SESSION['current_user']); ?></p>
    <p class="profile_texts">Email: <?php echo htmlspecialchars($_SESSION['current_user_email']); ?></p>
    <p class="profile_texts">Notification Email: <?php echo htmlspecialchars($_SESSION['current_user_notification_email']);?></p>
    <p class="profile_texts">profile_pic: <?php echo htmlspecialchars($_SESSION['current_user_pic']); ?></p>
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
        <button class="profile_change_notification">Change notification mail</button>
    </a>
</body>
</html>