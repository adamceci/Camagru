<?php
if (isset($_SESSION) && array_key_exists("last_login", $_SESSION)) {
    $last_input = (!empty($_SESSION["last_login"])) ? $_SESSION["last_login"] : "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
</head>
<body class="sign_in_body">
<div class="sign_in_responsive">
    <div class="sign_in_absolute_title">
        <h1 class="sign_in_title">Sign in to Camagru</h1>
    </div>
    <div class="sign_in_absolute_form">
        <form class="sign_in_form" action="login" method="POST">
            <p class="input_text">Username or email address</p><input class="sign_in_inputs" type="login" name="login" value="<?php if(isset($last_input)) echo $last_input; $last_input = ""; ?>"/><br>
            <p class="input_text">Password</p><input class="sign_in_inputs" type="password" name="password" value=""/><br>
            <input class="sign_in_submit" type="submit" name="submit_login" value="Sign in">
        </form>
    </div>
</div>
</body>
</html>