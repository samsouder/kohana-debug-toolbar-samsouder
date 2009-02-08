<style type="text/css">
<?php echo $styles ?>
</style>

<script type="text/javascript">
<?php echo $scripts ?>
</script>

<div id="debug-toolbar">
	<div id="toolbar">
		<ul class="menu">
			<li>
				<a href="http://kohanaphp.com/home" target="_blank">
					<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . 'kohana.png' ?>" />
				</a>
			</li>
			<li>
				<a id="time" href="#" onclick="debugToolbar.show('benchmarks'); return false;">
					<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . 'time.png' ?>" />
					<?php echo round(($benchmarks['system_benchmark_total_execution']['time'])*1000, 2)?> ms
				</a>
			</li>
			<li>
				<a id="memory" href="#" onclick="debugToolbar.show('benchmarks'); return false;">
					<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . 'memory.png' ?>" />
					<?php echo round(($benchmarks['system_benchmark_total_execution']['memory'])/(1024*1024), 2)?> MB
				</a>
			</li>
			<li>
				<a id="toggle-database" href="#" onclick="debugToolbar.show('database'); return false;">
					<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . 'database.png' ?>" />
					<?php echo count($queries)?>
				</a>
			</li>
			<li>
				<a id="toggle-vars" href="#" onclick="debugToolbar.show('vars'); return false;">
					<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . 'config.png' ?>" />
					vars &amp; config
				</a>
			</li>
			<li>
				<a id="toggle-log" href="#" onclick="debugToolbar.show('log'); return false;">
					<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . 'logs.png' ?>" />
					logs &amp; msgs
				</a>
			</li>
			<li class="last">
				<a href="#" onclick="debugToolbar.close(); return false;">
					<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . 'close.png' ?>" />
				</a>
			</li>
		</ul>
	</div>
	<div id="benchmarks" class="top" style="display: none;">
		<h1>Benchmarks</h1>
		<table cellspacing="0" cellpadding="0">
			<tr>
				<th align="left">benchmark</th>
				<th align="right">time</th>
				<th align="right">memory</th>
			</tr>
			<?php foreach ($benchmarks as $name => $benchmark): ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td align="left"><?php echo $name ?></td>
					<td align="right"><?php echo sprintf('%.2f', $benchmark['time'] * 1000)?> ms</td>
					<td align="right"><?php echo sprintf('%.2f', $benchmark['memory'] / (1024 * 1024))?> MB</td>
				</tr>
			<?php endforeach; ?>
			<?php
			extract($benchmarks['system_benchmark_total_execution']);
			?>
			<tr class="<?php echo text::alternate('odd','even')?>">
				<th align="left">Total</th>
				<th align="right"><?php echo sprintf('%.2f', $time * 1000)?> ms</th>
				<th align="right"><?php echo sprintf('%.2f', $memory / (1024 * 1024))?> MB</th>
			</tr>
		</table>
	</div>
	<div id="database" class="top" style="display: none;">
		<h1>SQL  queries</h1>
		<table cellspacing="0" cellpadding="0">
			<tr align="left"><th>query</th><th>time</th><th>rows</th></tr>
			<?php
			$total_time = 0;
			$total_rows = 0;
			?>
			<?php foreach ($queries as $query): ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td><?php echo $query['query']?></td>
					<td><?php echo sprintf('%.3f', $query['time'] * 1000)?> ms</td>
					<td><?php echo $query['rows']?></td>
				</tr>
				<?php 
					$total_time += $query['time'];
					$total_rows += $query['rows'];
				?>
			<?php endforeach; ?>
			<tr align="left">
				<th><?php echo count($queries) ?> total</th>
				<th><?php echo sprintf('%.3f', $total_time * 1000) ?> ms</th>
				<th><?php echo $total_rows ?></th>
			</tr>
		</table>
	</div>
	<div id="vars" class="top" style="display: none;">
		<h1>vars &amp; config</h1>
		<ul class="varmenu">
			<li><a href="#" onclick="debugToolbar.showvar('vars-post'); return false;">POST</a></li>
			<li><a href="#" onclick="debugToolbar.showvar('vars-get'); return false;">GET</a></li>
			<li><a href="#" onclick="debugToolbar.showvar('vars-server'); return false;">SERVER</a></li>
			<li><a href="#" onclick="debugToolbar.showvar('vars-cookie'); return false;">COOKIE</a></li>
			<li><a href="#" onclick="debugToolbar.showvar('vars-session'); return false;">SESSION</a></li>
			<li><a href="#" onclick="debugToolbar.showvar('vars-config'); return false;">CONFIG</a></li>
		</ul>
		<div style="display: none;" id="vars-post"><?php echo Kohana::debug($_POST) ?></div>
		<div style="display: none;" id="vars-get"><?php echo Kohana::debug($_GET) ?></div>
		<div style="display: none;" id="vars-server"><?php echo Kohana::debug($_SERVER) ?></div>
		<div style="display: none;" id="vars-cookie"><?php echo Kohana::debug($_COOKIE) ?></div>
		<div style="display: none;" id="vars-session"><?php echo isset($_SESSION) ? Kohana::debug($_SESSION) : '' ?></div>
		<div style="display: none;" id="vars-config">
			<ul>
			<?php foreach ($config as $section => $vars): ?>
				<li class="<?php echo text::alternate('odd', 'even') ?>" onclick="debugToolbar.toggle('vars-config-<?php echo $section ?>'); return false;">
					<a href="javascript: void(0)"><?php echo $section ?></a>
					<div style="display: none;" id="vars-config-<?php echo $section ?>">
						<?php echo Kohana::debug($vars) ?>
					</div>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div id="log" class="top" style="display: none;">
		<h1>logs &amp; msgs</h1>
		<table cellspacing="0" cellpadding="0">
			<tr align="left"><th>time</th><th>level</th><th>msg</th></tr>
			<?php foreach ($logs as $log): ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td><?php echo $log[0]?></td>
					<td><?php echo $log[1]?></td>
					<td><?php echo $log[2]?></td>
				</tr>
			<?php endforeach; ?>
			<tr align="left">
				<th colspan="3" align="right"><?php echo number_format(count($logs))?> total</th>
			</tr>
		</table>
	</div>
</div>