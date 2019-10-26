<?php
if (isset($_SESSION) && array_key_exists("last_Login", $_SESSION)) {
    $last_login = htmlspecialchars($_SESSION["last_Login"]);
}
?>
<form method="POST" action="">
    new_login: <input type="text" name="new_login" value="<?php $last_login; ?>">
    <input type="submit" name="submit_change_login" value="Change login">
</form>