<div id="main">
    <img id="tentage" src=<?= "assets/tmp_pics/" . $_SESSION["tmp_file_name"]; ?> alt="">

    <canvas id="my_canvas" width="1000px" height="1000px">
    </canvas>
    <p id="filters">
        Select filters (optional) :
        <div id="display_filters">
            <img class="filter unselected_filter" src="assets/filters/moustache.png" alt="">
            <img class="filter unselected_filter" src="assets/filters/bieber_hairs.png" alt="">
            <img class="filter unselected_filter" src="assets/filters/superman.png" alt="">
            <img class="filter unselected_filter" src="assets/filters/arnold.png" alt="">
        </div>
    </p>
    <script src="scripts/filters.js"></script>
    <script src="scripts/montage_file.js"></script>
</div>