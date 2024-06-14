<script type="text/javascript">
	$(document).ready(function(){
		$("#bProximo").click(function(event){
			location.href = "index.php";
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
				<tr><td align="center"><p>Instalação concluída! Obrigado pela preferência.</p></td></tr>
				<tr><td align="center">&nbsp;</td></tr>
				<tr><td align="right">
					<a href="#" id="bProximo" class="fg-button ui-state-default ui-corner-all fg-button-icon-right " style="float:none">				
						Próximo
						<span class="ui-icon ui-icon-circle-arrow-e"/>
					</a>
				</td></tr>				
			</table>			
		</td>
	</tr>
</table>