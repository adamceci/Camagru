<?php
if (!input_useable($_SESSION, 'current_user')) {

    ?>
    <div class="password_rec_wrapper">
        <form method="POST" action="#" onsubmit="return false;">
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
