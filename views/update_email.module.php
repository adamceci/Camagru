<?php
if (isset($_SESSION) && array_key_exists("last_email", $_SESSION) && array_key_exists("refresh", $_SESSION)) {
    $last_login = htmlspecialchars($_SESSION["last_email"]);
}
?>

<form method="POST" action="profile">
    New email: <input type="text" name="new_email" value="<?= isset($last_login) ? $last_login: ""; ?>">
    <input type="submit" name="submit_change_email" class="submit_change_email" value="Change email">
</form>