<script type="text/javascript">
	$(document).ready(function(){
		$("#bAnterior").click(function(event){
			carregarBody("setup.wizard.php");
			event.preventDefault();
		});
		$("#bProximo").click(function(event){
			var vCriar = "N";
			if ($("#chckCriar").is(':checked')) {
				vCriar = "S";
			}
			$.post("setup.db.save.php", {
				host: $("#txtHost").val(),
				user: $("#txtUser").val(),
				pwd: $("#txtSenha").val(),
				database: $("#txtDatabase").val(),
				criar: vCriar
			}, function (data) {
				if (data == "ok") {
					carregarBody("setup.end.php");
				} else {
					$("#dAviso").html(data);
					$("#dAviso").show("slow");
				}
			});			
			event.preventDefault();
		});
	});
</script>
<h2 class="ui-widget-header">Configuração do Banco de Dados</h2>
<div id="dAviso" class="ui-state-error ui-corner-all" style="margin:5px;display:none;"></div>
<p>Entre com as informações do Banco de Dados que hospedará a base de dados do Lethus GED:</p>
<table>
	<tr>
		<td>Host:</td>
		<td><input id="txtHost" type="text" /></td>
	</tr>
	<tr>
		<td>Usuário:</td>
		<td><input id="txtUser" type="text" /></td>
	</tr>
	<tr>
		<td>Senha:</td>
		<td><input id="txtSenha" type="password" /></td>
	</tr>
	<tr>
		<td>Database:</td>
		<td><input id="txtDatabase" type="text" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="checkbox" id="chckCriar" /> Criar database se não existir.</td>
	</tr>
</table>
<br />
<table width="100%">
	<tr>
		<td align="right">
			<a href="#" id="bAnterior" class="fg-button ui-state-default ui-corner-all fg-button-icon-left" style="float:none">
				<span class="ui-icon ui-icon-circle-arrow-w"/>
				Voltar
			</a>
			<a href="#" id="bProximo" class="fg-button ui-state-default ui-corner-all fg-button-icon-right " style="float:none">				
				Próximo
				<span class="ui-icon ui-icon-circle-arrow-e"/>
			</a>
		</td>
	</tr>
</table>