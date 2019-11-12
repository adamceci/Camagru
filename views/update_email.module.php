<?php
if (isset($_SESSION) && array_key_exists("last_email", $_SESSION) && array_key_exists("refresh", $_SESSION)) {
    $last_login = htmlspecialchars($_SESSION["last_email"]);
}
?>

<form method="POST" action="profile&update=email" class="form_wrapper">
    <p class="text_inputs">New email</p><br/><input class="profile_inputs" type="text" name="new_email" value="<?= isset($last_login) ? $last_login: ""; ?>">
    <input type="submit" name="submit_change_email" class="submit_update" value="Change email">
</form>