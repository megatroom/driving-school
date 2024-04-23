<?php
include_once('connection.php');

$pTable = $_GET["table"];

$fields = null;
$sqlSelect = mysql_query("describe ".$pTable) or die ("ERRO: ".mysql_error());
while ($field = mysql_fetch_row($sqlSelect)){  
	$fields[] = $field[0]; 
} 

$sql = "SELECT * FROM ".$pTable;

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error());

?>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
	<thead>
		<tr class="ui-widget-header">
			<th colspan="4"><?php echo $pTable; ?></th>					
		</tr>
		<tr class="ui-widget-header">
			<?php
			foreach($fields as $field) {
				echo "<th>".$field."</th>";
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($rows) {
			while ($row = mysql_fetch_row($rows)) { 
				echo "<tr>";
				foreach ($row as $value) {
					echo "<td>".$value."</td>";
				}
				echo "</tr>";
			}
		}
		?>
	</tbody>
</table>
</table>