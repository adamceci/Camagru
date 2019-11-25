<div id="side">
    <h2>My uploads</h2>
    <?php
    $i = 0;
    if (isset($information)) {
        foreach($information as $post) {
            ?>
            <div class="user_posts">
                <div class="hidden remove">
                    <button class="delete_btn">DELETE</button>
                </div>
                <?php
                    if ($post["posted"] == 0) {
                        ?>
                        <div class="hidden post">
                            <button class="post_btn">POST</button>
                        </div>
                        <?php
                    }
                ?>
                <img id="<?= $i++; ?>" src="assets/post_pics/<?= $post["image"]; ?>" alt="">
            </div>
            <?php
        }
    }
    else {
        echo "There are no posts to show";
    }
    ?>
</div>