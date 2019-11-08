<div class="comments_wrapper">

<?php
    if (isset($information) && !empty($information)) {
?>
        <?php
        foreach ($information as $comment) {
            echo "<p class='poster'>" . $comment['login'] . "</p>";
            echo "<p class='comment'>" . $comment['message'] . "</p>";
        }

        ?>
<?php
    } else {
        echo "<p class='no_comments'>No comments posted yet</p>";
    }
?>
</div>
<textarea class="message"></textarea>
<button class="create_comment_submit">Create Comment</button>
