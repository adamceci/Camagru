<?php
if (isset($_SESSION) && array_key_exists("last_Login", $_SESSION) && array_key_exists("refresh", $_SESSION)) {
    $last_login = htmlspecialchars($_SESSION["last_Login"]);
}
?>
<form method="POST" action="profile">
    New login: <input type="text" name="new_login" value="<?= isset($last_login) ? $last_login: ""; ?>">
    <input type="submit" name="submit_change_login" value="Change login">
</form>