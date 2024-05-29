<?php
include_once('connection.php');

$pDataBase = $_GET["pDatabase"];

$sql = "SHOW TABLE STATUS FROM ".$pDataBase;

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error());


?>
<script type="text/javascript">
	$(document).ready(function(){
		$("button").button();
		$("#btnDropDatabse").click(function(event) {
			var parametros = '&par=database=<?php echo $pDataBase; ?>';
			if (confirm('Você irá excluir todo o conteúdo do banco de dados. Deseja continuar?')) {
				carregarPaginaSisSeguranca("Exclusão do Banco de Dados", 'carregando.php?url=dropdatabase'+parametros);
			}
			event.preventDefault();
		});
		$("#btnDeleteFromTable").live('click', function(event) {			
			vTabela = $(this).val();
			var parametros = '&par=table='+vTabela;
			if (confirm('Você irá excluir todo o conteúdo da tabela '+vTabela+'. Deseja continuar?')) {
				carregarPaginaSisSeguranca("Limpar tabela "+vTabela, 'carregando.php?url=deletefromtable'+parametros);
			}
			event.preventDefault();
		});
		$("#btnDeleteTable").live('click', function(event) {			
			vTabela = $(this).val();
			var parametros = '&par=table='+vTabela;
			if (confirm('Você irá excluir todo o conteúdo da tabela '+vTabela+'. Deseja continuar?')) {
				carregarPaginaSisSeguranca("Excluir tabela "+vTabela, 'carregando.php?url=deletetable'+parametros);
			}
			event.preventDefault();
		});
	});
	function showTable(pTable) {
		carregarPaginaSisSeguranca("Excluir tabela "+vTabela, 'tables.php?pTable='+pTable);
	}
</script>
<br />
<h2>Banco de Dados: <?php echo $pDataBase; ?></h2>
<br />
<button type="button" id="btnDropDatabse" >Deletar a Base de Dados</button>
<br /><br />
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
	<thead>
		<tr class="ui-widget-header">
			<th>Tabela</th>			
			<th>Registros</th>
			<th>Auto Incrimento</th>
			<th>Tamanho</th>
			<th colspan="2">Ações</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($rows) {
			while ($row = mysql_fetch_array($rows)) { 				
		?>
				<tr>					
					<td>
						<a href="#" onclick="javascript:showTable('<?php echo $row["Name"]; ?>');">
							<?php echo $row["Name"]; ?>
						</a>
					</td>					
					<td><?php echo $row["Rows"]; ?></td>
					<td><?php echo $row["Auto_increment"]; ?></td>
					<td><?php echo $row["Data_length"]; ?></td>
					<td><button type="button" id="btnDeleteFromTable" value="<?php echo $row["Name"]; ?>">Limpar tabela</button></td>
					<td><button type="button" id="btnDeleteTable" value="<?php echo $row["Name"]; ?>">Excluir tabela</button></td>
				</tr>
		<?php
			}
		}
		?>
	</tbody>
</table>
<br /><br />
