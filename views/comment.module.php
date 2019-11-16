<h3 class="comment_title">Comments section</h3>

<div class="message_wrapper">
    <textarea class="message" placeholder="Write your comment here..."></textarea>
    <button class="create_comment_submit">Comment</button>
</div>
<div class="comments_wrapper">
<?php
    if (isset($information) && !empty($information)) {
?>
        <?php
        foreach ($information as $comment) {
            if (file_exists("./assets/profile_pics/" . htmlspecialchars($comment['profile_pic']))) {
                echo "<img class='comment_profile_pic' src='assets/profile_pics/" . htmlspecialchars($comment['profile_pic']) . "' />";
            } else {
                echo "<img class='comment_profile_pic' src='assets/profile_pics/default.png' />";
            }
            echo "<p class='poster'>" . htmlspecialchars($comment['login']) . "</p>";
            echo "<p class='comment'>" . htmlspecialchars($comment['message']) . "</p>";
        }
        ?>
<?php
    } else {
        echo "<p class='no_comments'>No comments posted yet</p>";
    }
?>
</div>
