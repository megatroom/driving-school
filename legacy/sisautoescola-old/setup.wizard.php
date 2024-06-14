<?php

$filename = "config.ini";
$texto = 'Em alguns passos você irá instalar o sistema.';
$bd_button = '';
$next_page = "setup.db.php";

if (file_exists($filename)) {
	$ini_array = parse_ini_file($filename);
	$texto = 'O banco de dados já foi configurado. Continue a instalação ou reconfigure o banco.';
	$bd_button = '<a href="#" id="bDB" class="fg-button ui-state-default ui-corner-all fg-button-icon-left " style="float:none"><span class="ui-icon ui-icon-wrench"/>Configurar Banco de Dados</a>';
	$next_page = "setup.licenca.php";
}

?>
<script type="text/javascript">
	$(document).ready(function(){
		<?php if ($bd_button != "") { ?>
			$("#bDB").click(function(event){
				carregarBody("setup.db.php");
				event.preventDefault();
			});		
		<?php } ?>
		$("#bProximo").click(function(event){
			carregarBody("<?php echo $next_page; ?>");
			event.preventDefault();
		});
	});
</script>
<table width="100%">
	<tr>
		<td align="left"><img src="images/wizard-icon.png" /></td>
		<td>
			<table cellpadding="5">
				<tr><td align="center"><h2>Assistente de Instalação</h2></td></tr>
				<tr><td align="center"><p><?php echo $texto; ?></p></td></tr>
				<tr><td align="center">&nbsp;</td></tr>
				<tr><td align="right">
					<?php echo $bd_button; ?>
					<a href="#" id="bProximo" class="fg-button ui-state-default ui-corner-all fg-button-icon-right " style="float:none">				
						Próximo
						<span class="ui-icon ui-icon-circle-arrow-e"/>
					</a>
				</td></tr>				
			</table>			
		</td>
	</tr>
</table>