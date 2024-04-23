<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$idturno = $_GET["pId"];

$acesso = new modulos_usuarios_funcionalidades(15);

$mysql = new modulos_global_mysql();

$nometurno = $mysql->getValue('descricao', null, 'turnos', "id = '".$idturno."'");

$lstSemana = null;
$lstSemana["1"] = 'Domingo';
$lstSemana["2"] = 'Segunda';
$lstSemana["3"] = 'Terça';
$lstSemana["4"] = 'Quarta';
$lstSemana["5"] = 'Quinta';
$lstSemana["6"] = 'Sexta';
$lstSemana["7"] = 'Sábado';

$pColNames = array('Código', 'Dia da Semana', 'Hora Inicial', 'Hora Final');
$pColModel = array("{name:'id',index:'id', hidden:true, width:80}",
                   "{name:'diasemana',index:'diasemana', width:200}",
                    "{name:'horai',index:'horai', width:200}",
                    "{name:'horaf',index:'horaf', width:200}");
$pSortName = 'diasemana';

$mainGrid = new modulos_global_grid('mainExpediente', 'Expedientes', 'modulos/expediente/form.xml.php?pIdTurno='.$idturno, $pColNames, $pColModel, $pSortName, true);

$form = new modulos_global_form('Expediente');

$form->divAlert();

$form->startFieldSet('Expediente_basic');
$form->null('<table><tr><td colspan="2">');
$form->inputTextStatic('fTurno', 'Turno', $nometurno, false, "460px");
$form->null('</td><td valign="bottom">');
$form->buttonCancel('bCancelarExp', 'Voltar', 'modulos/expediente/index.php');
$form->null('</td></tr><tr><td>');
$form->selectFixed('fDiaSemana', 'Dia da Semana', true, $lstSemana, null, "120px");
$form->inputTime('fhorai', 'Hora Inicial', null, true, "100px");
$form->inputTime('fhoraf', 'Hora Final', null, true, "100px");
$form->null('</td><td valign="bottom">');
if ($acesso->getFuncionalidade(1)) {
    $form->buttonAdicionar('bAddExpediente');
} else {
    $form->null('&nbsp;');
}
$form->null('</td><td valign="bottom">');
if ($acesso->getFuncionalidade(2)) {
    $form->buttonExc('bExcExpediente', null, $mainGrid->getGridName(), 'id', 'modulos/expediente/form.php?pId='.$idturno, 'modulos/expediente/delete.php');
} else {
    $form->null('&nbsp;');
}
$form->null('</td></tr></table>');
$form->null('<br />');
$form->nullArray($mainGrid->resultGrid());
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
<?php
if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}
?>
$(document).ready(function(){
    $("#bAddExpediente").click(function(event){
        $.post(
            "modulos/expediente/save.php",
            { id : '0',
                idturno : '<?php echo $idturno; ?>',
                diasemana : $("#fDiaSemana").val(),
                horai : $("#fhorai").val(),
                horaf : $("#fhoraf").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/expediente/form.php?pId=<?php echo $idturno; ?>','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>