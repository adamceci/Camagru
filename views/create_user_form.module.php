<?php
if (isset($_SESSION) && array_key_exists("last_login", $_SESSION) && !empty($_SESSION["last_login"]) &&
    isset($_SESSION) && array_key_exists("last_email", $_SESSION) && !empty($_SESSION["last_email"])) {
        $last_login = $_SESSION["last_login"];
        $last_email = $_SESSION["last_email"];
    }
?>

<div class="create_user_body">
<div class="create_user_responsive">
    <div class="create_user_absolute_title">
        <h1 class="create_user_join">Join our Camagru community</h1>
        <h1 class="create_user_title">Create your account</h1>
    </div>

    <div class="create_user_absolute_form">
        <form action="#" method="POST" enctype="multipart/form-data">
            <p class="input_text">Email <span class="asterix_obligatory">*</span></p><input class="create_user_inputs" type="email" name="email" value="<?php if(isset($last_email)) echo $last_email; ?>"/><br>
            <p class="input_text">Username <span class="asterix_obligatory">*</span></p> <input class="create_user_inputs" type="text" name="login" value="<?php if(isset($last_login)) echo $last_login; ?>"/><br>
            <p class="input_text">Password <span class="asterix_obligatory">*</span></p> <input class="create_user_inputs" type="password" name="password" value=""/><br>
            <p class="input_text">Password confirmation <span class="asterix_obligatory">*</span></p> <input class="create_user_inputs" type="password" name="password_verif" value=""/><br>
            <p class="input_text">Profile picture: </p><input class="create_user_file_input" type="file" name="profile_pic" value="profile_pic"/><br>
            <input class="create_user_submit" type="submit" name="submit_create" value="Create">
        </form>
    </div>
</div>
