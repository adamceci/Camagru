<div id="side">
    <h2>My uploads</h2>
    <?php
    $i = 0;
    if (isset($information)) {
        // var_dump($_SESSION["user_posts"]); // array(0) {} il rentre dans le isset.... (faudrait unset())
        foreach($information as $post) {
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
                <img id="<?= $i++; ?>" src="assets/post_pics/<?= $post["image"]; ?>" alt="">
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