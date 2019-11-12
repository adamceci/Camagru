<?php
if (isset($_SESSION) && array_key_exists("last_Login", $_SESSION) && array_key_exists("refresh", $_SESSION)) {
    $last_login = htmlspecialchars($_SESSION["last_Login"]);
}
?>
<form method="POST" action="profile&update=login" class="form_wrapper">
    <p class="text_inputs">New login<br/> <span class="format_login">Authorized chars: [a-z][A-Z][0-9] '_' and '.' between words. Minimum size: 3 letters</span></p><input type="text" class="profile_login_update profile_inputs" name="new_login" value="<?= isset($last_login) ? $last_login: ""; ?>"><br/>
    <input type="submit" name="submit_change_login" class="submit_update" value="Change login">
</form>