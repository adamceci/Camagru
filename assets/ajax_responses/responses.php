<?php

if(isset($_GET)) {
    if (array_key_exists("toDelSrc", $_GET)) {
        try {
            PostsController::delete_post($_GET);
            echo "OK";
        }
        catch (exception $e) {
            echo "FAIL";
        }
    }
    else if (array_key_exists("toPubSrc", $_GET)) {
        try {
            PostsController::publish_post($_GET);
            echo "OK";
        }
        catch (exception $e) {
            echo "FAIL";
        }
    }
}

if (input_useable($_POST, "array_images")) {
    PostsController::create_montage();


    // $img_srcs = json_decode($_POST["images"])->imagesArray;


    // // Create GD images
    // $base_img = imagecreatefrompng("assets/tmp_pics/1573574411-alien3.png");
    // $filter = imagecreatefrompng("assets/filters/sumo.png");

    // // Create a new true color image
    // $final_img = imagecreatetruecolor(imagesx($base_img), imagesy($base_img));

    // imagealphablending($final_img, TRUE);
    // imagesavealpha($final_img, TRUE);

    // imagecopyresampled($final_img, $base_img, 0, 0, 0, 0, imagesx($base_img), imagesy($base_img), imagesx($base_img), imagesy($base_img));
    // imagecopyresampled($final_img, $filter, 0, 0, 0, 0, imagesx($base_img), imagesy($base_img), imagesx($filter), imagesy($filter));
   
    // imagepng($final_img, "assets/post_pics/test1.png");

    // echo json_encode(array("merged_img" => $merged_img));
}