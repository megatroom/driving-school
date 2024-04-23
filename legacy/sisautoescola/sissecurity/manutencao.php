<?php
include_once('connection.php');

$sql = "SHOW DATABASES";

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error());

$databases = null;
while ($row = mysql_fetch_row($rows)) { 
	if ($row[0] != "information_schema" and $row[0] != "test" and $row[0] != "mysql") {
		$databases[] = $row[0];
	}
}

?>
<script type="text/javascript">
	function useDatabase(pDataBase) {
		carregarPaginaSisSeguranca("Database "+pDataBase, "databases.php?pDatabase="+pDataBase);
	}
</script>
<br />
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
	<thead>
		<tr class="ui-widget-header">
			<th>Databases</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (is_array($databases)) {
			foreach ($databases as $database) {				
		?>
				<tr>					
					<td>
						<a href="#" onclick="javascript:useDatabase('<?php echo $database; ?>');">
							<?php echo $database; ?>
						</a>
					</td>
				</tr>
		<?php
			}
		}
		?>
	</tbody>
</table>
<br /><br />
