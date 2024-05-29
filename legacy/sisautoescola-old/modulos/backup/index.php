<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$backupDir = $mysql->getValue("valor", null, "sistema", "campo = 'backupdir'");

$validacao = false;

if (strlen($backupDir) > 1) {
    if (is_dir($backupDir)) {
        $validacao = true;
    } else {
        echo "<h2>Diretório inválido: ".$validacao."</h2>";
        exit;
    }
} else {
    echo "<h2>Diretório de backup não definido!</h2>";
    exit;
}

if ($validacao) {
    $ponteiro  = opendir($backupDir);

    if ($ponteiro) {
        $arquivos = null;
        while ($nome_itens = readdir($ponteiro)) {
                if (strtoupper(substr($nome_itens,-4)) == ".ZIP") {
                        $data = substr($nome_itens,13,8);
                        $data = substr($data,6)."/".substr($data,4,2)."/".substr($data,0,4);
                        $arquivos[] = array(
                                "arquivo" => $nome_itens,
                                "data" => $data);
                }
        }
    } else {
        echo "<h2>Erro ao abrir diretório: ".$validacao."</h2>";
    }
}

?>
<script type="text/javascript">
	function restaurar(pArquivo) {
            novaAbaMenuPrincipalComParametro("modulos/backup/restaurar.php", { arquivo : pArquivo }, "Restauração do Backup do Banco de Dados");
	}
</script>
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header">
            <th colspan="4">Listagem de Backups</th>
        </tr>
        <tr class="ui-widget-header">
            <th>Data</th>
            <th>Restaurar</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($arquivos)) { foreach ($arquivos as $arquivo) { ?>
            <tr>
                <td><?php echo $arquivo["data"]; ?></td>
                <!-- <td><a href="#" onclick="javascript:restaurar('<?php echo $arquivo["arquivo"]; ?>');">Restaurar</a></td> -->
            </tr>
        <?php } } ?>
    </tbody>
</table>