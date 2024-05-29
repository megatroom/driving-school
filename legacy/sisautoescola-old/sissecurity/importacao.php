<?php
include_once('connection.php');

$sql = "SHOW DATABASES";

$rows = mysql_query($sql) or die ("ERRO: ".mysql_error());

$databases = null;
while ($row = mysql_fetch_row($rows)) {
	if ($row[0] != "information_schema" and $row[0] != "test" and $row[0] != "mysql") {
		$databases[] = $row[0];
	}
}

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("button").button();
        $("#btnImport").click(function() {
            var vOrigemDB = $("#origemDB").val();
            var vDestinoDB = $("#destinoDB").val();
            var parametros = '&par=origemDB='+vOrigemDB+"|destinoDB="+vDestinoDB;
            carregarPaginaSisSeguranca("Importação", "carregando.php?url=executarimportacao"+parametros);
        });
    });
</script>
<table>
    <tr>
        <td colspan="2" align="center"><h2>Origem</h2></td>
    </tr>
    <tr>
        <td>Banco de Dados:</td>
        <td>
            <select id="origemDB">
                <?php
                    foreach ($databases as $database) {
                        echo '<option value="'.$database.'">'.$database.'</option>';
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><h2>Destino</h2></td>
    </tr>
    <tr>
        <td>Banco de Dados:</td>
        <td>
            <select id="destinoDB">
                <?php
                    foreach ($databases as $database) {
                        echo '<option value="'.$database.'">'.$database.'</option>';
                    }
                ?>
            </select>
        </td>
    </tr>
</table>
<br /><br />
<button type="button" id="btnImport" >Executar Importação!</button>
<br /><br />