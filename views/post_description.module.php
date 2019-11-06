<?php

if (isset($_SESSION) && array_key_exists('post_creator', $_SESSION) && !empty($_SESSION['post_creator'])
    && array_key_exists('post_img', $_SESSION) && !empty($_SESSION['post_img'])) {
    $creator = $_SESSION['post_creator'];
    $img_src = $_SESSION['post_img'];
}
?>

<div class="post_description_wrapper">
<p class="post_creator_login"><?= isset($creator) ? $creator : ""; ?></p>
<img class="post_description_img" src="assets/post_imgs/<?= isset($img_src) ? $img_src : ""; ?>" />
</div>
