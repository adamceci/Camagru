<form method="POST" action="profile&update=password" class="form_wrapper">
    <p class="text_inputs">Old password<br/></p><input class="profile_inputs" type="password" name="old_password"><br />
    <p class="text_inputs">New password<br/><span class="format_password">Authorized chars: [a-z][A-Z][0-9]. At least one capital letter, a number and a minimum size of 5 letters</span></p><input class="profile_password_update profile_inputs" type="password" name="new_password"><br />
    <input type="submit" name="submit_change_password" class="submit_update" value="Change password">
</form>