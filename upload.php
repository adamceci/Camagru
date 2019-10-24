<?php

// The following function is an error handler which is used
// to output an HTML error page if the file upload fails
function error($error, $location, $seconds = 20)
{
    header("Refresh: $seconds; URL=$location");
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'.
    '"http://www.w3.org/TR/html4/strict.dtd">'.
    '<html lang="en">'.
    '    <head>'.
    '        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">'.
    '        <link rel="stylesheet" type="text/css" href="stylesheet.css">'.
    '    <title>Upload error</title>'.
    '    </head>'.
    '    <body>'.
    '    <div id="Upload">'.
    '        <h1>Upload failure</h1>'.
    '        <p>An error has occurred: '.
    '        <span class="red">' . $error . '...</span>'.
    '         The upload form is reloading</p>'.
    '     </div>'.
    '</html>';
    exit;
} // end error handler

// Current working directory ("/Camagru-MVC-/")
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);

// Directory that will receive uploaded files
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/post_imgs/';

// make a note of the location of the upload form in case we need it
$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload.php';

// location of the success page
$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload_success.php';

// fieldname used within the file <input> of the HTML form
$fieldname = "image";

// possible PHP upload errors
$errors = array(1 => 'php.ini max file size exceeded',
                2 => 'html form max file size exceeded',
                3 => 'file upload was only partial',
                4 => 'no file was attached');

// check the upload form was actually submitted else print the form 
// if (!(isset($_POST) && array_key_exists("submit_create_post", $_POST)))
    // error('the upload form is neaded', $uploadForm);

// check for PHP's built-in uploading errors 
// if ($_FILES[$fieldname]['error'] =! 0)
    // error($errors[$_FILES[$fieldname]['error']], $uploadForm);

// check that the file we are working on really was the subject of an HTTP upload
// @is_uploaded_file($_FILES[$fieldname]['tmp_name'])
    // or error('not an HTTP upload', $uploadForm);

// validation... since this is an image upload script we should run a check
// to make sure the uploaded file is in fact an image. Here is a simple check:
// getimagesize() returns false if the file tested is not an image.
// @getimagesize($_FILES[$fieldname]['tmp_name'])
    // or error('only image uploads are allowed', $uploadForm);

// make a unique filename for the uploaded file and check it is not already
// taken... if it is already taken keep trying until we find a vacant one
// sample filename: 1140732936-filename.jpg
$now = time();
while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['name']))
    $now++;

// now let's move the file to its final location and allocate the new filename to it
if (!(move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename)))
    error('receiving directory insuffiecient permission', $uploadForm);

// If you got this far, everything has worked and the file has been successfully saved.
// We are now going to redirect the client to a success page.
// header('Location: ' . $uploadSuccess);

// var_dump($_FILES);

?>