<?php
    $max_file_size = 400000;
?>

<form method="POST" action="profile&update=profile_pic" enctype="multipart/form-data" class="form_wrapper">
    <p class="text_inputs">New picture<br/></p><input class="profile_inputs_file" type="file" name="new_profile_pic" value="new_profile_pic">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size;?>" />
    <input type="submit" name="submit_change_profile_pic" class="submit_update" value="Change profile picture">
</form>