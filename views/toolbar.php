<?php defined('SYSPATH') or die('No direct script access.') ?>

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
				<a href="http://kohanaphp.com/home" target="_blank"><?php echo KOHANA_VERSION ?></a>
			</li>
			<li id="time" onclick="debugToolbar.show('debug-benchmarks'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/time.png' ?>" />
				<?php echo round(($benchmarks['system_benchmark_total_execution']['time'])*1000, 2) ?> ms
			</li>
			<li id="memory" onclick="debugToolbar.show('debug-benchmarks'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/memory.png' ?>" />
				<?php echo text::bytes($benchmarks['system_benchmark_total_execution']['memory']) ?>
			</li>
			<li id="toggle-database" onclick="debugToolbar.show('debug-database'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/database.png' ?>" />
				<?php echo count($queries)?>
			</li>
			<li id="toggle-vars" onclick="debugToolbar.show('debug-vars'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/config.png' ?>" />
				vars &amp; config
			</li>
			<li id="toggle-log" onclick="debugToolbar.show('debug-log'); return false;">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/logs.png' ?>" />
				logs &amp; msgs
			</li>
			<li id="toggle-ajax" onclick="debugToolbar.show('debug-ajax'); return false;" style="display: none">
				<img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/ajax.png' ?>" />
				ajax
			</li>
			<li class="last" onclick="debugToolbar.close(); return false;"><img src="<?php echo Kohana::config('debug_toolbar.icon_path') . '/close.png' ?>" /></li>
		</ul>
	</div>
	
	<!-- benchmarks -->
	<div id="debug-benchmarks" class="top" style="display: none;">
		<h1>Benchmarks</h1>
		<table cellspacing="0" cellpadding="0">
			<tr>
				<th align="left">benchmark</th>
				<th align="right">time</th>
				<th align="right">memory</th>
			</tr>
			<?php if (count($benchmarks)): ?>
				<?php foreach ((array)$benchmarks as $benchmark): ?>
					<tr class="<?php echo text::alternate('odd','even')?>">
						<td align="left"><?php echo $benchmark['name'] ?></td>
						<td align="right"><?php echo sprintf('%.2f', $benchmark['time'] * 1000) ?> ms</td>
						<td align="right"><?php echo text::bytes($benchmark['memory']) ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr class="<?php echo text::alternate('odd','even') ?>">
					<td colspan="3">no benchmarks to display</td>
				</tr>
			<?php endif ?>
		</table>
	</div>
	
	<!-- database -->
	<div id="debug-database" class="top" style="display: none;">
		<h1>SQL  queries</h1>
		<table cellspacing="0" cellpadding="0">
			<tr align="left">
				<th>#</th>
				<th>query</th>
				<th>time</th>
				<th>rows</th>
			</tr>
			<?php $total_time = $total_rows = 0; ?>
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
	<div id="debug-vars" class="top" style="display: none;">
		<h1>vars &amp; config</h1>
		<ul class="varmenu">
			<li onclick="debugToolbar.showvar(this, 'vars-post'); return false;">POST</li>
			<li onclick="debugToolbar.showvar(this, 'vars-get'); return false;">GET</li>
			<li onclick="debugToolbar.showvar(this, 'vars-server'); return false;">SERVER</li>
			<li onclick="debugToolbar.showvar(this, 'vars-cookie'); return false;">COOKIE</li>
			<li onclick="debugToolbar.showvar(this, 'vars-session'); return false;">SESSION</li>
			<li onclick="debugToolbar.showvar(this, 'vars-config'); return false;">CONFIG</li>
		</ul>
		<div style="display: none;" id="vars-post">
			<?php echo isset($_POST) ? Kohana::debug($_POST) : Kohana::debug(array()) ?>
		</div>
		<div style="display: none;" id="vars-get">
			<?php echo isset($_GET) ? Kohana::debug($_GET) : Kohana::debug(array()) ?>
		</div>
		<div style="display: none;" id="vars-server">
			<?php echo isset($_SERVER) ? Kohana::debug($_SERVER) : Kohana::debug(array()) ?>
		</div>
		<div style="display: none;" id="vars-cookie">
			<?php echo isset($_COOKIE) ? Kohana::debug($_COOKIE) : Kohana::debug(array()) ?>
		</div>
		<div style="display: none;" id="vars-session">
			<?php echo isset($_SESSION) ? Kohana::debug($_SESSION) : Kohana::debug(array()) ?>
		</div>
		<div style="display: none;" id="vars-config">
			<ul class="configmenu">
				<?php foreach ($configs as $section => $vars): ?>
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
	<div id="debug-log" class="top" style="display: none;">
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
					<td width="200"><?php echo $log[0] ?></td>
					<td width="100"><?php echo $log[1] ?></td>
					<td><?php echo $log[2] ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	
	<!-- ajax -->
	<div id="debug-ajax" class="top" style="display:none;">
		<h1>ajax</h1>
			<table cellspacing="0" cellpadding="0">
			<tr align="left">
				<th>#</th>
				<th>request</th>
				<th>code</th>
			</tr>
		</table>
	</div>

</div>