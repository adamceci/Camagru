<?php

    // Current working directory ("/Camagru-MVC-/")
    $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);

    // Upload handler script location
    $upload_handler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload';

    // max file size for the html upload form
    $max_file_size = 400000; // size in bytes

?>

<!DOCTYPE html>
<html>
<body>
    <div id="container_title">
        <h1>MONTAGE</h1>
    </div>
    <div id="wrapper">
        <div id="container">
            <!-- <div id="main">
                <p>
                    Pick a filter :
                    <div id="display_filters">
                        <img class="filter" src="assets/post_imgs/trump.png" alt="">
                        <img class="filter" src="assets/post_imgs/trump.png" alt="">
                        <img class="filter" src="assets/post_imgs/trump.png" alt="">
                        <img class="filter" src="assets/post_imgs/trump.png" alt="">
                    </div>
                </p>
                <p id="choice">
                    <button id="chose_file">Upload file</button>
                    or
                    <button id="chose_vid">Take a picture</button>
                </p>
                <form class="hidden" id="form_file" enctype="multipart/form-data" action="success_upload" method="post">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size;?>" />
                    <input type="file" name="image" value="upload_pic" /><br />
                    <input type="submit" name="submit_create_post" value="Post" />
                    <input type="submit" name="save" value="Save" />
                </form>
                <div class="hidden" id="cam_div">
                    <video autoplay></video>
                    <img id="screenshot-img">
                    <canvas style="display: none;"></canvas>
                    <p>
                        <button id="screenshot-button">Take screenshot</button>
                    </p>
                </div>
            </div> -->

            <div id="main">
                <p id="choice">
                    <button id="file_choice">Upload file</button>
                    or
                    <button id="cam_choice">Take a picture</button>
                </p>
                <form class="hidden" id="form_file" enctype="multipart/form-data" action="success_upload" method="post">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size;?>" />
                    <input type="file" name="image" value="upload_pic" /><br />
                    <input type="submit" name="upload_image" value="Ok" />
                </form>
                <div class="hidden" id="cam_div">
                    <video autoplay></video>
                    <img id="screenshot-img">
                    <canvas style="display: none;"></canvas>
                    <p>
                        <button id="screenshot-button">Take screenshot</button>
                    </p>
                </div>
            </div>













            <div id="side">
                <h2>My uploads</h2>
                <?php
                $i = 0;
                if (isset($_SESSION["user_posts"])) {
                    // var_dump($_SESSION["user_posts"]); // array(0) {} il rentre dans le isset.... (faudrait unset())
                    foreach($_SESSION["user_posts"] as $post) {
                        // var_dump($_SESSION["user_posts"]);
                        ?>
                        <div class="user_posts">
                            <div class="hidden remove">
                                <button>-</button>
                            </div>
                            <?php
                                if ($post["posted"] == 0) {
                                    ?>
                                    <div class="hidden post">
                                        <button>POST</button>
                                    </div>
                                    <?php
                                }
                            ?>
                            <img id="<?= $i++; ?>" src="assets/post_imgs/<?= $post["image"]; ?>" alt="">
                        </div>
                        <?php
                    }
                }
                else {
                    echo "There are no posts to show"; // faire une balise html !
                }
                ?>
            </div>
        </div>
    </div>
    <script src="scripts/gallery.js"></script>
</body>
</html>