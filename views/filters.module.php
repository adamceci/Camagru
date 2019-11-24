<?php
	if ($_GET["url"] == "montage")
		$msg = "(at least one)";
	else
		$msg = "(optional)";
?>

<div class="hidden" id="filters_div">
	<p id="filters" style="display: none;">
		Select filters <?= $msg ?> :
	</p>
	<div id="display_filters">
		<img class="filter unselected_filter" src="assets/filters/curtains.png" alt="">
		<img class="filter unselected_filter" src="assets/filters/frame.png" alt="">
		<img class="filter unselected_filter" src="assets/filters/gate.png" alt="">
		<img class="filter unselected_filter" src="assets/filters/grunge_banner.png" alt="">
	</div>
	<div class="flex right" id="div_save_post">
		<p id="submit_btns">
			<button class="clickeable" id="save_btn">Save</button>
			<button class="clickeable" id="post_btn">Post</button>
		</p>
	</div>
</div>