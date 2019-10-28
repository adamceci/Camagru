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

        if (isset($_SESSION["index_posts"])) {
            foreach($_SESSION["index_posts"] as $post) {
                ?>
                <div class="post_index">            
                    <img src="assets/post_imgs/<?= $post["image"]; ?>" alt="">
                    <div class="display_nb">
                        <p><?=$nb_comments?>X Comment(s)</p>
                        <p><?=$nb_likes?>X Like(s)</p>
                    </div>
                </div>
                <?php
            }
        }
        else {
            echo "There are no posts to show"; // faire une balise html !
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