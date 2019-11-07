<?php

?>

<div class="sign_in_body">
    <div class="sign_in_responsive">
        <div class="sign_in_absolute_title">
            <h1 class="sign_in_title">Sign in to Camagru</h1>
        </div>
        <div class="sign_in_absolute_form">
            <form class="sign_in_form" action="#" method="POST" onsubmit="return false">
                <p class="input_text">Username or email address</p><input class="sign_in_inputs pass_rec_input" type="login" name="login" value="<?php if(isset($last_input)) echo $last_input; $last_input = ""; ?>" required/><br>
                <input class="pass_rec_submit" type="submit" name="submit_pass_rec" value="Recover password">
            </form>
        </div>
    </div>
</div>
