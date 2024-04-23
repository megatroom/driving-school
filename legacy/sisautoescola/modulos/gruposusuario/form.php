<?php

include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$descricao = null;

$mysql = new modulos_global_mysql();

if (isset ($pId)) {
    $fieldList = $mysql->select('*', 'gruposusuario', "id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$lstUsuarios = $mysql->select(
        'a.id, a.login, coalesce(b.nome, a.nome) as nome',
        'usuarios a left join vfuncionarios b on a.idfuncionario = b.id',
        "a.id in (select idusuario from usuariosgrupousuario where idgrupousuario = '".$pId."')",
        null,
        'nome');
$usuariosin = null;
if (is_array($lstUsuarios)) {
    foreach ($lstUsuarios as $usuario) {
        $usuariosin[$usuario["id"]] = $usuario["nome"] ." (". $usuario["login"] .")";
    }
}
$lstUsuarios = $mysql->select(
        'a.id, a.login, coalesce(b.nome, a.nome) as nome',
        'usuarios a left join vfuncionarios b on a.idfuncionario = b.id',
        "a.login <> 'admin' and a.id not in (select idusuario from usuariosgrupousuario where idgrupousuario = '".$pId."')",
        null,
        'nome');
$usuariosout = null;
if (is_array($lstUsuarios)) {
    foreach ($lstUsuarios as $usuario) {
        $usuariosout[$usuario["id"]] = $usuario["nome"] ." (". $usuario["login"] .")";
    }
}

$form = new modulos_global_form('GruposUsers');

$form->buttonSave("saveTiposDoc");
$form->buttonCancel("closeTiposDoc", null, "modulos/gruposusuario/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fid',$pId);

$form->startFieldSet('fdGpUsr');
$form->inputText('fdescricao', 'Descrição', $descricao, "60", true, "300px");
$form->endFieldSet();

$form->startFieldSet('fdUserOfGpr');
$form->selectFixed('fusuariosout', 'Usuários', false, $usuariosout, null, "400px");
$form->null('<br />');
$form->buttonAdicionar('btnAdd', 'Adiconar Usuário');
$form->buttonCustom('btnExc', 'Remover Usuário', 'ui-icon-minus');
$form->null('<br /><br /><br />');
$form->selectMultiple('fusuariosin', 'Usuários do grupo', false, $usuariosin, null, "400px", 10);
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#btnAdd").click(function(event) { 
        if ($("#fusuariosout").val() == null || $("#fusuariosout").val() == "") {
            divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", "Selecione um usuário.");
        } else {
            $("#fusuariosin").append($("#fusuariosout option:selected"));
        }
        event.preventDefault();
    });
    $("#btnExc").click(function(event) {
        if ($("#fusuariosin").val() == null || $("#fusuariosin").val() == "") {
            divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", "Selecione um usuário.");
        } else {
            $("#fusuariosout").append($("#fusuariosin option:selected"));
        }
        event.preventDefault();
    });
    $("#saveTiposDoc").click(function(event){
        var itensIn = "";
        $("#fusuariosin option").each(function() {
            if (itensIn == "") {
                itensIn = $(this).val();
            } else {
                itensIn = itensIn + "|" + $(this).val();
            }
        });
        $.post(
            "modulos/gruposusuario/save.php",
            { id : $("#fid").val(),
                descricao : $("#fdescricao").val(),
                itens : itensIn
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/gruposusuario/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");  
        event.preventDefault();
    });
});
</script>