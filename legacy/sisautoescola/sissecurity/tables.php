<?php
include_once('connection.php');

$pTable = $_GET["pTable"];

$sql = "DESCRIBE ".$pTable;

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error());

?>
<script type="text/javascript">
	$(document).ready(function(){
		$("button").button();
		$("#btnSelectTable").click(function(event) {			
			var parametros = "&par=table=<?php echo $pTable; ?>";
			carregarPaginaSisSeguranca("Registros da tabela <?php echo $pTable; ?>", 'carregando.php?url=selecttable'+parametros);
			event.preventDefault();
		});
	});
</script>
<br />
<h2>Tabela: <?php echo $pTable; ?></h2>
<br /><br />
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
	<thead>
		<tr class="ui-widget-header">
			<th>Campo</th>			
			<th>Tipo</th>
			<th>Null</th>
			<th>Chave</th>			
			<th>Padrão</th>
			<th>Extra</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($rows) {
			while ($row = mysql_fetch_array($rows)) { 				
		?>
				<tr>					
					<td><?php echo $row["Field"]; ?></td>					
					<td><?php echo $row["Type"]; ?></td>
					<td><?php echo $row["Null"]; ?></td>
					<td><?php echo $row["Key"]; ?></td>
					<td><?php echo $row["Default"]; ?></td>
					<td><?php echo $row["Extra"]; ?></td>
				</tr>
		<?php
			}
		}
		?>
	</tbody>
</table>
<br /><br />
<button type="button" id="btnSelectTable">Mostrar todos os registros.</button>
<br /><br />
