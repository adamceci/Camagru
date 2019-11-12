<?php
if (isset($_SESSION) && array_key_exists("last_email", $_SESSION) && array_key_exists("refresh", $_SESSION)) {
    $last_login = htmlspecialchars($_SESSION["last_email"]);
}
?>

<form method="POST" action="profile&update=notification_email" class="form_wrapper">
    <p class="text_inputs">New notification email</p><br/><input type="text" class="profile_inputs" name="new_notification_email" value="<?= isset($last_login) ? $last_login: ""; ?>">
    <input type="submit" name="submit_change_notification_email" class="submit_update" value="Change email">
</form>