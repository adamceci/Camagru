<?php

    // Current working directory ("/Camagru-MVC-/")
    $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
    // Directory that will receive uploaded files
    $uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/post_imgs/';
    $i = 0;
    if (isset($information)) {
        foreach($information as $arr_nb_likes_or_comments_per_post) {
            foreach($arr_nb_likes_or_comments_per_post as $nb_likes_or_comments_per_post) {
                if (input_useable($nb_likes_or_comments_per_post, 'nb_comments')) {
                    if ($nb_likes_or_comments_per_post['nb_comments'] > 1) {
                        $nb_comments[] = $nb_likes_or_comments_per_post['nb_comments'] . " Comments";
                    } else {
                        $nb_comments[] = $nb_likes_or_comments_per_post['nb_comments'] . " Comment";
                    }
                } else {
                    if ($nb_likes_or_comments_per_post['nb_likes'] > 1) {
                        $nb_likes[] = $nb_likes_or_comments_per_post['nb_likes'] . " Likes";
                    } else {
                        $nb_likes[] = $nb_likes_or_comments_per_post['nb_likes'] . " Like";
                    }
                }
            }
        }
    }
?>

    <div id="container_title">
        <h1>GALLERY</h1>
        <!-- <a href="montage">Create a new post</a> -->
        <a href="montage"><button class="buttonplus">+</button></a>
    </div>
    <div id="container_box">
    <?php

        if (file_exists($uploads_directory) && isset($_SESSION["index_posts"])) {
            foreach($_SESSION["index_posts"] as $post) {
                ?>
                <div class="post_index">
                    <?php if (file_exists($uploads_directory . $post["image"])) {
                        ?>
                        <img src="assets/post_imgs/<?= $post["image"]; ?>" alt="">
                        <div class="display_nb">
                            <a href="comments&post_img=<?= $post["image"]; ?>&"><p><?= isset($nb_comments) ? $nb_comments[$i] : "0 Comment";?></p></a>
                            <p class="like_post post_id_<?= $post['post_id']; ?>"><?= isset($nb_likes) ? $nb_likes[$i] : "0 Like";?></p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                $i++;
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