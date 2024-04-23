<?php
include_once('config.php');

$db_connection = mysql_connect($db_host,$db_user,$db_password,$db_database) or die ("Erro de Conexão:".mysql_error());

mysql_select_db("mysql",$db_connection);

$rows = mysql_query("select * from event") or die ("ERRO: ".mysql_error());

while ($row = mysql_fetch_array($rows)) {
echo var_dump($row); 
}
exit;

?>
<table class="ui-widget ui-widget-content" cellpadding="5">
	<thead>
		<tr class="ui-widget-header">
			<th colspan="4">Agendamentos de Backup</th>					
		</tr>
		<tr class="ui-widget-header">
			<th>Agendamento</th>
			<th>Valor</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($rows) {
			while ($row = mysql_fetch_array($rows)) { 				
		?>
				<tr>					
					<td><a href="<?php echo ""; ?>"><?php echo $row["Name"]; ?></a></td>
					<td><a href="<?php echo ""; ?>"><?php echo $row["Value"]; ?></a></td>
				</tr>
		<?php
			}
		}
		?>
	</tbody>
</table>
