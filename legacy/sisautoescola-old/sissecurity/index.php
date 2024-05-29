<?php

$theme = 'cupertino';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" /> 
<title>Sistema de Segurança</title>
<link type="text/css" rel="stylesheet" href="css/<?php echo $theme ?>/jquery-ui-1.8.5.custom.css" />
<link type="text/css" rel="stylesheet" href="css/default.css" />
<link type="text/css" rel="stylesheet" href="css/global.css" /> 
<style type="text/css">
 html, body {
    width: 100%; height: 100%;
    border: 0; padding: 0; margin: 0;
 }
 img {
	border: 0;
 }
</style>
</style>

<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>    
	
<script type="text/javascript">
	$(document).ready(function(){
		$("#btnTop1").click(function(event){
			carregarPaginaSisSeguranca("Listagem de Backups", "listagem.php");
			event.preventDefault();
		});
		$("#btnTop2").click(function(event){
			carregarPaginaSisSeguranca("Agendar Backup", "agendarbackup.php");
			event.preventDefault();
		});
		$("#btnTop3").click(function(event){
			carregarPaginaSisSeguranca("LOG de Usuários", "logusuarios.php");
			event.preventDefault();
		});
                $("#btnTop4").click(function(event){
			carregarPaginaSisSeguranca("Importação", "importacao.php");
			event.preventDefault();
		});
		$("#btnTop10").click(function(event){
			carregarPaginaSisSeguranca("Manutenção da Base de Dados", "manutencao.php");
			event.preventDefault();
		});
	});
	function carregarPaginaSisSeguranca(pTitulo, pURL, pParametros) {
		$.post(pURL, null, function(data) {
			$("#windowSisSegTitulo").html(pTitulo);
			$("#windowSisSegContent").html(data);
		});
	}
</script>

</head>
<body>
	<div style="height:70px;right:5px;left:5px;top:5px;position:absolute;" class="ui-widget-content ui-corner-all">
		<table style="margin-left:5px" cellspacing="5">
			<tr>
				<td>
					<a id="btnTop1" href="#"><img src="images/web-server-1.png" width="50px" height="50px" alt="Listagem de Backups" title="Listagem de Backups" /></a>
				</td>
				<td>
					<a id="btnTop2" href="#"><img src="images/folder-clock-1.png" width="55px" height="55px" alt="Agendar Backup" title="Agendar Backup" /></a>
				</td>
				<td>
					<a id="btnTop3" href="#"><img src="images/user-group-1.png" width="50px" height="50px" alt="LOG de Usuários" title="LOG de Usuários" /></a>
				</td>
                                <td>
					<a id="btnTop4" href="#"><img src="images/export.png" width="50px" height="50px" alt="Importação" title="Importação" /></a>
				</td>
				<td>
					<a id="btnTop10" href="#"><img src="images/config-app.png" width="50px" height="50px" alt="Manutenção da Base de Dados" title="Manutenção da Base de Dados" /></a>
				</td>
			</tr>
		</table>
	</div>
	<div style="right:5px;left:5px;top:80px;position:absolute;width:auto;" class="ui-dialog ui-widget ui-widget-content ui-corner-all">
	   <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
		  <span id="windowSisSegTitulo" class="ui-dialog-title">Bemvindo</span>
	   </div>
	   <div class="ui-dialog-content ui-widget-content" id="windowSisSegContent">
			<table width="100%" cellspacing="20">
				<tr>
					<td width="40%" align="right"><img src="images/comodo-internet-security.png" width="100px" height="100px" /></td>
					<td><h1>Sistema de Segurança</h1></td>
				</tr>
			</tr>
	   </div>
	</div>
</body>
</html>