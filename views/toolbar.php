<style type="text/css">
<?php echo $styles ?>
</style>

<script type="text/javascript">
<?php echo $scripts ?>
</script>

<div id="debug-toolbar">

	<!-- toolbar -->
	<div id="toolbar">
		<a href="http://kohanaphp.com/home" target="_blank">
			<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/kohana.png' ?>" />
		</a>
		<ul class="menu">
			<li>
				<a href="http://kohanaphp.com/home" target="_blank"><?php echo KOHANA_VERSION; ?></a>
			</li>
			<li id="time" onclick="debugToolbar.show('benchmarks'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/time.png' ?>" />
				<?php echo round(($benchmarks['system_benchmark_total_execution']['time'])*1000, 2)?> ms
			</li>
			<li id="memory" onclick="debugToolbar.show('benchmarks'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/memory.png' ?>" />
				<?php echo round(($benchmarks['system_benchmark_total_execution']['memory'])/(1024*1024), 2)?> MB
			</li>
			<li id="toggle-database" onclick="debugToolbar.show('database'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/database.png' ?>" />
				<?php echo count($queries)?>
			</li>
			<li id="toggle-vars" onclick="debugToolbar.show('vars'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/config.png' ?>" />
				vars &amp; config
			</li>
			<li id="toggle-log" onclick="debugToolbar.show('log'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/logs.png' ?>" />
				logs &amp; msgs
			</li>
			<li class="last" onclick="debugToolbar.close(); return false;"><img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/close.png' ?>" /></li>
		</ul>
	</div>
	
	<!-- benchmarks -->
	<div id="benchmarks" class="top" style="display: none;">
		<h1>Benchmarks</h1>
		<table cellspacing="0" cellpadding="0">
			<tr>
				<th align="left">benchmark</th>
				<th align="right">time</th>
				<th align="right">memory</th>
			</tr>
			<?php if (count($benchmarks)): ?>
				<?php foreach ((array)$benchmarks as $name => $benchmark): ?>
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
			<?php else: ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td align="left">no benchmarks to display</td><td align="right">-</td><td align="right">-</td>
				</tr>
				<tr>
					<th align="left">total</th><th align="right">-</th><th align="right">-</th>
				</tr>
			<?php endif ?>
		</table>
	</div>
	
	<!-- database -->
	<div id="database" class="top" style="display: none;">
		<h1>SQL  queries</h1>
		<table cellspacing="0" cellpadding="0">
			<tr align="left">
				<th>#</th>
				<th>query</th>
				<th>time</th>
				<th>rows</th>
			</tr>
			<?php
			$total_time = 0;
			$total_rows = 0;
			?>
			<?php foreach ((array)$queries as $id => $query): ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td><?php echo $id + 1 ?></td>
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
				<th>&nbsp;</th>
				<th><?php echo count($queries) ?> total</th>
				<th><?php echo sprintf('%.3f', $total_time * 1000) ?> ms</th>
				<th><?php echo $total_rows ?></th>
			</tr>
		</table>
	</div>
	
	<!-- vars and config -->
	<div id="vars" class="top" style="display: none;">
		<h1>vars &amp; config</h1>
		<ul class="varmenu">
			<li onclick="debugToolbar.showvar(this, 'vars-post'); return false;">POST</li>
			<li onclick="debugToolbar.showvar(this, 'vars-get'); return false;">GET</li>
			<li onclick="debugToolbar.showvar(this, 'vars-server'); return false;">SERVER</li>
			<li onclick="debugToolbar.showvar(this, 'vars-cookie'); return false;">COOKIE</li>
			<li onclick="debugToolbar.showvar(this, 'vars-session'); return false;">SESSION</li>
			<li onclick="debugToolbar.showvar(this, 'vars-config'); return false;">CONFIG</li>
		</ul>
		<div style="display: none;" id="vars-post"><?php echo Kohana::debug($_POST) ?></div>
		<div style="display: none;" id="vars-get"><?php echo Kohana::debug($_GET) ?></div>
		<div style="display: none;" id="vars-server"><?php echo Kohana::debug($_SERVER) ?></div>
		<div style="display: none;" id="vars-cookie"><?php echo Kohana::debug($_COOKIE) ?></div>
		<div style="display: none;" id="vars-session"><?php echo isset($_SESSION) ? Kohana::debug($_SESSION) : '' ?></div>
		<div style="display: none;" id="vars-config">
			<ul class="configmenu">
			<?php foreach ($config as $section => $vars): ?>
				<li class="<?php echo text::alternate('odd', 'even') ?>" onclick="debugToolbar.toggle('vars-config-<?php echo $section ?>'); return false;">
					<div><?php echo $section ?></div>
					<div style="display: none;" id="vars-config-<?php echo $section ?>">
						<?php echo Kohana::debug($vars) ?>
					</div>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	
	<!-- logs and messages -->
	<div id="log" class="top" style="display: none;">
		<h1>logs &amp; msgs</h1>
		<table cellspacing="0" cellpadding="0">
			<tr align="left">
				<th>#</th>
				<th>time</th>
				<th>level</th>
				<th>message</th>
			</tr>
			<?php foreach ((array)$logs as $id => $log): ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td width="1%"><?php echo $id + 1 ?></td>
					<td width="150"><?php echo $log[0] ?></td>
					<td width="100"><?php echo $log[1] ?></td>
					<td><?php echo $log[2] ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

</div>