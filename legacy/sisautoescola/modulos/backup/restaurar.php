<?php
include_once("../../configuracao.php");

$arquivo = $_POST["arquivo"];

$form = new modulos_global_form('frmBackup');

$form->checkbox('chckChavePrimaria', "Ignorar erros de registros duplicados.", null, true);
$form->checkbox('chckValoresNull', "Ignorar erros de valor nulo.", null, true);

$form->divClear(1);

$form->buttonCustom('btnExecutar', 'Restaurar Backup', 'ui-icon-play');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnExecutar").click(function(){
            var parametros = "?arquivo=<?php echo $arquivo; ?>";
            if ($("#chckChavePrimaria").attr("checked")) {
                    parametros = parametros + '&key=S';
            } else {
                    parametros = parametros + '&key=N';
            }
            if ($("#chckValoresNull").attr("checked")) {
                    parametros = parametros + '&vnull=S';
            } else {
                    parametros = parametros + '&vnull=N';
            }
            if (confirm("Você irá substituir o banco de dados atual com o arquivo escolhido.\nDeseja continuar?")) {
                openRelatorio("modulos/backup/execrestauracao.php"+parametros);
            }
            event.preventDefault();
        });
    });
</script>