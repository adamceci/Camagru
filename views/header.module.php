<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
</head> 
<body>

<?php

if (isset($_SESSION) && array_key_exists("current_user", $_SESSION)) {

?>
	<div class="header">
        <div><a href="index.php">CAMAGRU</a></div>
        <div class="hidden"></div>
        <div><a href="profile.php"><?=$_SESSION["current_user"]?></a></div>
        <div><a href="logout"><button type="button">Log out</button></a></div>
    </div>
<?php

}
else {

?>
	<div class="header">
        <div><a href="index.php">CAMAGRU</a></div>
		<div class="hidden"></div>
		<div><a href="sign_up">Sign up</a></div>
        <div><a href="login"><button type="button">Log in</button></a></div>
	</div>
<?php

}

?>

</body>
</html>