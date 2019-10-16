<?php
if (isset($_SESSION) && array_key_exists("last_login", $_SESSION)) {
    $last_input = (!empty($_SESSION["last_login"])) ? $_SESSION["last_login"] : "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
</head>
<body>

<form action="login" method="POST">
    email or login: <input type="login" name="login" value="<?php if(isset($last_input)) echo $last_input; $last_input = ""; ?>"/><br>
    password: <input type="password" name="password" value=""/><br>
    <input type="submit" name="submit_login" value="OK">
</form>

</body>
</html>