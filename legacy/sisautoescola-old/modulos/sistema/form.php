<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$fields = null;
$fieldList = $mysql->select('*', 'sistema');
if (is_array($fieldList)) {
    foreach ($fieldList as $fieldArray) {
        $fields[$fieldArray["campo"]] = $fieldArray["valor"];
    }
}

$themeList = null;
$themeList['black-tie'] = 'Black Tie';
$themeList['cupertino'] = 'Cupertino';
$themeList['humanity'] = 'Humanity';
$themeList['ui-lightness'] = 'Lightness';
$themeList['redmond'] = 'Redmond';
$themeList['smoothness'] = 'Smoothness';

if (!isset ($theme) or strlen($theme) == 0) {
    $theme = 'humanity';
}

$windowList = null;
//$windowList['1'] = 'Em Abas';
$windowList['2'] = 'Individuais';
//$windowList['3'] = 'Modal';

$form = new modulos_global_form('Sistema');

$form->divAlert();

$form->startFieldSet('aparence','Aparência');
//$form->inputText('fcompany_name', 'Título do Sistema (Nome da Empresa)', $fields["titulo_sistema"], "45", false, '310px');
$form->selectFixed('ftheme', 'Tema', true, $themeList, $fields["tema"], "150px");
$form->selectFixed('fwindow', 'Janela', false, $windowList, $fields["janela"], "150px");
$form->endFieldSet();

$form->startFieldSet('fdSistema');
$form->inputTime('fHoraNoturna', 'Hora Noturna', $fields["horanoturna"], true);
$form->inputDate('fDataCaixaInicio', 'Data Início Caixa', db_to_date($fields["datainiciocaixa"]), FALSE);
$form->endFieldSet();

$form->startFieldSet('fdBackup');
$form->inputText('fBackupDir', 'Diretório para Backup', $fields["backupdir"], null, false, "600px", null, null);
$form->endFieldSet();

$form->startFieldSet('fdConfRelatorios', 'Relatórios');
$form->inputText('fRelTitulo', 'Título', $fields["reltitulo"], 100, false, "600px", null, null);
$form->textArea('fRelDesc', 'Descrição', $fields["reldesc"], 500, false, "600px", null, 'rows="5"');
$form->endFieldSet();

$form->buttonSave("saveSistema");
$form->buttonClose($_GET["pCloseId"], "closeSistema");

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#blicenca").click(function(event){
        location.href = "setup.php";
        event.preventDefault();
    });
    $("#saveSistema").click(function(event){
        $.post(
            "modulos/sistema/save.php",
            {
                //titulo_sistema : $("#fcompany_name").val(),
                tema : $("#ftheme").val(),
                janela : $("#fwindow").val(),
                horanoturna : $("#fHoraNoturna").val(),
                reltitulo : $("#fRelTitulo").val(),
                reldesc : $("#fRelDesc").val(),
                datainiciocaixa : $("#fDataCaixaInicio").val(),
                backupdir : $("#fBackupDir").val()
            },
            function(data){
                if (data.retornoStatus == 'save') {
                    document.location = "index.php";
                } else {
                    postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/sistema/form.php','<?php echo $form->getdivAlertName(); ?>');
                }
            }, "json");
        event.preventDefault();
    });
});
</script>