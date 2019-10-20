<?php
if (isset($_SESSION) && array_key_exists("last_login", $_SESSION) && !empty($_SESSION["last_login"]) &&
    isset($_SESSION) && array_key_exists("last_email", $_SESSION) && !empty($_SESSION["last_email"])) {
        $last_login = $_SESSION["last_login"];
        $last_email = $_SESSION["last_email"];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
</head> 
<body>

<div class="sign_up_absolute_title">
    <h1 class="sign_up_title">Sign up to Camagru</h1>
</div>

<div class="sign_up_absolute_form">
    <form action="create" method="POST">
        email: <input type="email" name="email" value="<?php if(isset($last_email)) echo $last_email; ?>"/><br>
        login: <input type="text" name="login" value="<?php if(isset($last_login)) echo $last_login; ?>"/><br>
        password: <input type="password" name="password" value=""/><br>
        verif password: <input type="password" name="password_verif" value=""/><br>
        Profile pic: <input type="file" name="profile_pic" value="/assets/img_pic/"/><br>
        <input type="submit" name="submit_create" value="OK">
    </form>
</div>

</body>
</html>