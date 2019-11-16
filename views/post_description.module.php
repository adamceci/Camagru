<?php

if (isset($_SESSION) && array_key_exists('post_creator', $_SESSION) && !empty($_SESSION['post_creator'])
    && array_key_exists('post_img', $_SESSION) && !empty($_SESSION['post_img'])) {
    $creator = $_SESSION['post_creator'];
    $img_src = $_SESSION['post_img'];
    $creator_profile_pic = $_SESSION['current_user_pic'];
}
?>

<div class="post_description_wrapper">
    <h2>Creator</h2>
    <img class="post_description_pic" src="assets/profile_pics/<?= isset($creator_profile_pic) ? $creator_profile_pic : "default.png" ?>" />

    <p class="post_description_creator"><?= isset($creator) ? htmlspecialchars($creator) : ""; ?></p>
    <img class="post_description_img" src="assets/post_pics/<?= isset($img_src) ? htmlspecialchars($img_src) : ""; ?>" />
</div>
