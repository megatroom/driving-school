<script type="text/javascript">
	$(document).ready(function(){
		$("button").button();
		$("txtDataExecucao").datepicker();
		$("#btnBackupNow").click(function(event) {
			carregarPaginaSisSeguranca("Agendar Backup", "carregando.php?url=executarbackup");
		});
		$("#btnBackupAgendado").click(function(event) {
			$.post("execscheduler.php", null, function(data) {
				listarAgendamentos();
			});			
		});
		listarAgendamentos();
	});
	function listarAgendamentos() {
		$.post("scheduler.php", null, function(data) {
			$("#dListagemAgendamentos").html(data);
		});
	}
</script>
<br />
<button type="button" id="btnBackupNow" >Executar Backup Agora!</button>
<br /><br />
<!--
<hr />
<br />
<div id="dListagemAgendamentos"></div>
<br /><br />
<table cellpadding="5">
	<tr>
		<td>
			<label for="txtDataExecucao">Data de Execução</label><br />
			<input type="text" id="txtDataExecucao" value="<?php echo date("d/m/Y"); ?>" />
		</td>
		<td>
			<label for="txtIntervalo">Intervalo</label><br />
			<select id="txtIntervalo">
				<option value="0">Nunca</option>
				<option value="YEAR">Todo ano</option>
				<option value="MONTH">Todo mês</option>
				<option value="WEEK">Toda semana</option>
				<option value="DAY">Todo dia</option>
			</select>
		</td>
	</tr>
</table>
<br />
<button type="button" id="btnBackupAgendado" >Agendar Backup</button>
<br /><br />
-->