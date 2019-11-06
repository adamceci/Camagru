<?php
    if (isset($information) && !empty($information)) {
?>
    <div class="comment_wrapper">
        <?php
        foreach ($information as $comment) {
            echo "<p class='poster'>" . $comment['login'] . "</p>";
            echo "<p class='comment'>" . $comment['message'] . "</p>";
        }

        ?>
    </div>
<?php
    } else {
        echo "<p class='no_comments'>No comments posted yet</p>";
    }
?>
<textarea class="message"></textarea>
<button class="create_comment_submit">Create Comment</button>
