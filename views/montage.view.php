<?php

    // Current working directory ("/Camagru-MVC-/")
    $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);

    // Upload handler script location
    $upload_handler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload';

    // max file size for the html upload form
    $max_file_size = 30000; // size in bytes

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/style.css?rnd=132" type="text/css">
</head> 
<body>

    <div class="wrapper">
        <form enctype="multipart/form-data" action="success_upload" method="post">
			<!-- webcam or picture -->
			<!-- filters -->
			<!-- submit_create_post -->
			<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size;?>" />
			Picture: <input type="file" name="image" value="upload_pic" /><br />
        	<input type="submit" name="submit_create_post" value="OK" />
    	</form>
    </div>

</body>
</html>