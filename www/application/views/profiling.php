<? if ($auth->checkPermission('profiler_stats')) : ?>
	<div id="profiler_container_text" onclick="$('#profiler_container').toggle()">stats</div>
	<div id="profiler_container">
		<?php echo View::factory('profiler/stats') ?>
	</div>
<? endif; ?>
