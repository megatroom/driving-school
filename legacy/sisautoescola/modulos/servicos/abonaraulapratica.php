<?php
include_once("../../configuracao.php");

$idaulapratica = $_POST["idaulapratica"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select('data, hora, carro', 'vaulaspraticas', "id = '".$idaulapratica."'");
if (is_array($rows)) {
    foreach ($rows as $row) {
        $data = db_to_date($row["data"]);
        $hora = $row["hora"];
        $carro = $row["carro"];
    }
}

$form = new modulos_global_form('frmAbonarAP');

$form->startFieldSet('fdAbonarAP');
$form->inputTextStatic('fData', 'Data', $data, true);
$form->inputTextStatic('fHora', 'Hora', $hora, false);
$form->inputTextStatic('fCarro', 'Carro', $carro, false, "400px");
$form->textArea('fMotivo', 'Motivo', null, null, false, "400px", null, 'rows="8"');
$form->endFieldSet();

$form->buttonSave('fSaveAbono');
$form->buttonCustom('fCancAbono', 'Cancelar', 'ui-icon-close');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fSaveAbono").click(function(event){
            $.post('modulos/servicos/abonarsave.php', {
                idaulapratica : '<?php echo $idaulapratica; ?>',
                abonomotivo : $("#fMotivo").val()
            }, function(data) {
                if (data.status == "ok") {
                    carregarAulasPraticas();
                } else {
                    alert(data.msg);
                }
            }, "json");
            event.preventDefault();
        });
        $("#fCancAbono").click(function(event){
            carregarAulasPraticas();
            event.preventDefault();
        });
    });
</script>