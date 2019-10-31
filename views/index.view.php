<?php

    // Current working directory ("/Camagru-MVC-/")
    $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
    // Directory that will receive uploaded files
    $uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/post_imgs/';

?>

<!DOCTYPE html>
<html>
<body>
    <div id="container_title">
        <h1>GALLERY</h1>
        <!-- <a href="montage">Create a new post</a> -->
        <a href="montage"><button class="buttonplus">+</button></a>
    </div>
    <div id="container_box">
    <?php

        if (file_exists($uploads_directory) && isset($_SESSION["index_posts"])) {
            // var_dump($_SESSION);
            foreach($_SESSION["index_posts"] as $post) {
                ?>
                <div class="post_index">
                    <?php if (file_exists($uploads_directory . $post["image"])) {
                        echo $uploads_directory . $post["image"];
                        ?>
                        <img src="assets/post_imgs/<?= $post["image"]; ?>" alt="">
                        <div class="display_nb">
                            <p><?=$nb_comments?>X Comment(s)</p>
                            <p><?=$nb_likes?>X Like(s)</p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
            }
        }
        else {
            echo "<h1>There are currently no posts to show</h1>"; // make a better html tag. !
        }

    ?>
    </div>
    <div class="pagination">
        <?php
            if ($_SESSION["current_page"] > 1) {
            ?>
                <a href="#">&laquo;</a>
            <?php

            }
                for ($i = 1; $i <= $_SESSION["nb_pages"]; $i++) {
                    if ($_SESSION["current_page"] == $i) {
                    ?>
                        <p><a href="#" class="active"><?= $i; ?></a></p>
                    <?php
                    }
                    else {
                    ?>
                        <p><a href="#"><?= $i; ?></a></p>
                    <?php
                    }
                }
                if ($_SESSION["current_page"] != $_SESSION["nb_pages"] && $_SESSION["nb_pages"] > 1) {
                    ?>
                    <p><a href="#">&raquo;</a></p>
                    <?php
                }

        ?>
    </div>
        <!-- <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div> -->
        <!-- <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
            <img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
            <img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div> -->
</body>
</html>