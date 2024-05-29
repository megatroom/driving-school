<?php

$diretorio = "backup";

$ponteiro  = opendir($diretorio);

$arquivos = null;
while ($nome_itens = readdir($ponteiro)) {
	if (strtoupper(substr($nome_itens,-4)) == ".ZIP") {
		$cont = strlen($nome_itens) - 16;
		$data = substr($nome_itens,$cont,8);
		$hora = substr($nome_itens,$cont+8,4);
		$data = substr($data,6)."/".substr($data,4,2)."/".substr($data,0,4);
		$hora = substr($hora,0,2).":".substr($hora,2);
		$arquivos[] = array(
			"arquivo" => $nome_itens, 
			"database" => substr($nome_itens,0,$cont-1), 
			"data" => $data,
			"hora" => $hora); 
	}
}

?>
<script type="text/javascript">
	function restaurar(pArquivo) {
		carregarPaginaSisSeguranca("Restauração do Backup do Banco de Dados", 'carregando.php?url=restauracao&par=arquivo='+pArquivo+'');
	}
</script>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
	<thead>
		<tr class="ui-widget-header">
			<th colspan="4">Listagem de Backups</th>					
		</tr>
		<tr class="ui-widget-header">
			<th>Data</th>
			<th>Hora</th>
			<th>Banco de Dados</th>
			<th>Restaurar</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (is_array($arquivos)) {
			foreach ($arquivos as $arquivo) {				
		?>
				<tr>					
					<td><a href="<?php echo $diretorio."/".$arquivo["arquivo"]; ?>"><?php echo $arquivo["data"]; ?></a></td>
					<td><a href="<?php echo $diretorio."/".$arquivo["arquivo"]; ?>"><?php echo $arquivo["hora"]; ?></a></td>
					<td><a href="<?php echo $diretorio."/".$arquivo["arquivo"]; ?>"><?php echo $arquivo["database"]; ?></a></td>
					<td><a href="#" onclick="javascript:restaurar('<?php echo $arquivo["arquivo"]; ?>');">Restaurar</a></td>
				</tr>
		<?php
			}
		}
		?>
	</tbody>
</table>
