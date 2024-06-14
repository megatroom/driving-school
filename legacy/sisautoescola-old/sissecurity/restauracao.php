<?php
include_once('connection.php');

$arquivo = $_GET["arquivo"];
	
?>
<script type="text/javascript">
	$(document).ready(function(){
	    $("button").button();
		$( "#dialog-confirm" ).dialog({
			autoOpen: false,
			resizable: false,
			height:180,
			width:350,
			modal: true,
			buttons: {
				"Restaurar BD": function() {
					executar_restauracao();
					$( this ).dialog( "close" );
				},
				Cancelar: function() {
					$( this ).dialog( "close" );
				}
			}
		});		
	});
	$("#btnRestaurar").click(function(event) {
		$("#dialog-confirm").dialog('open');
		event.preventDefault();
	});
	function executar_restauracao() {
		var parametros = 'par=arquivo=<?php echo $arquivo; ?>';
		if ($("#primarykey").attr("checked")) {
			parametros = parametros + '|key=S';
		} else {
			parametros = parametros + '|key=N';
		}
		carregarPaginaSisSeguranca("Restauração do Backup do Banco de Dados", 'carregando.php?url=execrestauracao&'+parametros);
	}
</script>
<div id="dialog-confirm" title="Restaurar Banco de Dados">
	<h2 style="color:red;">Atenção!</h2>
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Você irá sobrescrever todo o conteúdo do banco de dados. Deseja continuar?</p>
</div>
<h3>Executar backup para o arquivo: <?php echo $arquivo; ?></h3>
<table cellspacing="5" style="font-size:10pt;">
	<tr>
		<td width="20px" align="center" valign="middle">
			<input type="checkbox" id="primarykey" />
		</td>
		<td><label for="primarykey">Ignorar erros de registros duplicados.</label></td>
	</tr>
</table>
<br />
<button id="btnRestaurar" >Restaurar Backup</button>