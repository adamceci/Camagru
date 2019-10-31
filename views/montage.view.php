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
            <div id="main">
                <form enctype="multipart/form-data" action="success_upload" method="post">
                    <!-- webcam or picture -->
                    <!-- filters -->
                    <!-- submit_create_post -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size;?>" />
                    Picture: <input type="file" name="image" value="upload_pic" /><br />
                    <input type="submit" name="submit_create_post" value="Post" />
                    <input type="submit" name="save" value="Save" />
                </form>
            </div>
            <div id="side">
                <h2>My uploads</h2>
                <?php
                $i = 0;
                if (isset($_SESSION["user_posts"])) {
                    foreach($_SESSION["user_posts"] as $post) {
                        var_dump($_SESSION["user_posts"]);
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
    <script src="scripts/post.js"></script>
</body>
</html>