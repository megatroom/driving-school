<script type="text/javascript">
	$(document).ready(function(){
		$.post('<?php echo $_GET["url"] ?>.php<?php if (isset($_GET["par"])) { echo "?".str_replace("|","&",$_GET["par"]); } ?>', null, function(data) {
			$("#contentLoad").html(data);
		});
	});
</script>
<div id="contentLoad">
	<table width="100%">
		<tr>
			<td width="45%" align="right"><img src="images/carregando.gif" /></td>
			<td valign="middle"><span style="font-size:12pt">Executando...</span></td>
		</tr>
	</table>
</div>