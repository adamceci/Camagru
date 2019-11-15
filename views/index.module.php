<?php

    // Current working directory ("/Camagru-MVC-/")
    $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
    // Directory that will receive uploaded files
    $uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/post_pics/';
    $u = 0;
    if (isset($information)) {
        if (array_key_exists('nb_comments', $information)) {
            foreach ($information['nb_comments'] as $test) {
                $nb_comments[] = $test;
            }
            unset($information['nb_comments']);
        }
        if (array_key_exists('nb_likes', $information)) {
            foreach ($information['nb_likes'] as $nb_likes_output) {
                $nb_likes[] = $nb_likes_output;
            }
            unset($information['nb_likes']);
        }
        if (array_key_exists('users_likes_it', $information)) {
            foreach ($information['users_likes_it'] as $user_like_it) {
                $user_likes_it[] = $user_like_it;
            }
            unset($information['users_likes_it']);
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
        if (file_exists($uploads_directory) && isset($information)) {
            foreach($information as $post) {
                ?>
                <div class="post_index">
                    <?php if (file_exists($uploads_directory . $post["image"])) {
                        ?>
                        <img class="post_index_img" src="assets/post_pics/<?= $post["image"]; ?>" alt="">
                        <div class="display_nb">
                            <div class="comment_wrapper">
                                <a class="comment_post_link" href="comments&post_img=<?= $post["image"]; ?>&">
                                    <img class="post_index_heart" src="./assets/imgs/comment.png" />
                                    <p class="comment_post">
                                        <?= isset($nb_comments[$u]) ? htmlspecialchars($nb_comments[$u]) : "0";?>
                                    </p>
                                </a>
                            </div>
                            <div class="like_wrapper">
                                <p class="like_post post_id_<?= $post['post_id']; ?>"><?= !empty($nb_likes[$u]) ? $nb_likes[$u] : "0";?></p>
                                <?= !empty($user_likes_it[$u]) ? "<img class=\"post_index_heart\" src=\"./assets/imgs/heart_fill.png\" />" : "<img class=\"post_index_heart\" src=\"./assets/imgs/heart.png\" />" ; ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                $u++;
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