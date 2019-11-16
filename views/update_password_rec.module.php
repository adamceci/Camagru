<?php
if (!input_useable($_SESSION, 'current_user')) {

    ?>
    <div class="password_rec_wrapper">
        <form method="POST" action="#" onsubmit="return false;">
            <span style="display: block;" class="format_password">Authorized chars: [a-z][A-Z][0-9]. At least one capital letter, a number and a minimum size of 6 letters</span>
            New password: <input class="pass_rec_inputs" type="password" name="password"><br />
            Confirm password: <input class="pass_rec_inputs" type="password" name="password_verif"><br />
            <input type="submit" name="submit_change_password_rec" class="submit_change_password_rec" value="Change password">
        </form>
    </div>
<?php
} else {
    echo "<p class='permission_error'>Why are you here?</p>";
}
?>
