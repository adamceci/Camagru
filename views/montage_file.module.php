<div id="main">
    <div id="canvas_container">
        <img class="hidden" id="base_img" src=<?= "assets/tmp_pics/" . $_SESSION["tmp_file_name"]; ?> alt="">
        <canvas id="my_canvas" width="1000" height="1000" style="width:100%; height:100%"></canvas>
    </div>
    <p id="filters">
        Select filters (optional) :
        <div id="display_filters">
            <img class="filter unselected_filter" src="assets/filters/moustache.png" alt="">
            <img class="filter unselected_filter" src="assets/filters/bieber_hairs.png" alt="">
            <img class="filter unselected_filter" src="assets/filters/sumo.png" alt="">
            <img class="filter unselected_filter" src="assets/filters/arnold.png" alt="">
        </div>
    </p>
    <div class="flex right">
        <div>

        </div>
        <p id="submit_btns">
            <button class="clickeable" id="save_btn">Save</button>
            <button class="clickeable" id="post_btn">Post</button>
        </p>
    </div>
</div>