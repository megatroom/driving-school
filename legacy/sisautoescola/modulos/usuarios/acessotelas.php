<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$idgrupo = $_GET["pId"];

$gruponome = $mysql->getValue('descricao', 'descricao', 'gruposusuario', "id = '".$idgrupo."'");

$lstTelas = $mysql->select(
        'a.id, b.descricao as modulo, a.descricao as tela, coalesce(c.id, 0) as idgrupousuario',
        'telas a '.
        'inner join modulos b on a.idmodulo = b.id '.
        'left join acesso c on c.idtela = a.id and c.idgrupousuario = '.$idgrupo,
        'padrao = 0',
        null,
        'b.ordem, a.ordem');

$form = new modulos_global_form('Usuarios');

$form->buttonSave("saveAcesso");
$form->buttonCancel('btnCancelAcesso', null, 'modulos/usuarios/acesso.php');
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fidgrupo', $idgrupo);

$form->startFieldSet('fdSelectUsuarios');
$form->null('Grupo de Usuário: '.$gruponome);
$form->endFieldSet();

$form->startFieldSet('fdAcesso');
$lastMod = "";
$checked = false;
foreach ($lstTelas as $tela) {
    if ($lastMod != $tela["modulo"]) {
        $form->null('<h2>'.$tela["modulo"].'</h2>');
        $lastMod = $tela["modulo"];
    }
    $checked = $tela["idgrupousuario"] > 0;
    $form->checkbox('chTela', $tela["tela"], $tela["id"], $checked);

    $lstFuncionalidades = $mysql->select(
            "a.id, a.descricao, coalesce(b.id, 0) as idacessofunc",
            "funcionalidades a ".
            "left join acessofunc b on a.id = b.idfuncionalidade and b.idgrupousuario = ".$idgrupo,
            "a.idtela = '".$tela["id"]."'",
            null,
            "a.codigo");
    if (is_array($lstFuncionalidades)) {
        $form->null('<div style="padding-left:15px;">');
        foreach ($lstFuncionalidades as $funcionalidade) {
            $checked = $funcionalidade["idacessofunc"] > 0;
            $form->checkbox('chFuncionalidade', $funcionalidade["descricao"], $funcionalidade["id"], $checked);
        }
        $form->null('</div>');
    }
}
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveAcesso").click(function(event){
        var telasId = "";
        var funcId = "";
        $("input[name=chTela]").each(function() {
            if ($(this).attr("checked")) {
                if (telasId == "") {
                    telasId = $(this).val();
                } else {
                    telasId = telasId + "|" + $(this).val();
                }
            }
        });
        $("input[name=chFuncionalidade]").each(function() {
            if ($(this).attr("checked")) {
                if (funcId == "") {
                    funcId = $(this).val();
                } else {
                    funcId = funcId + "|" + $(this).val();
                }
            }
        });
        $.post(
            "modulos/usuarios/acessosave.php",
            { idgrupo : $("#fidgrupo").val(),
                telas : telasId,
                funcionalidades : funcId
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/usuarios/acesso.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json"); 
        event.preventDefault();
    });
});
</script>