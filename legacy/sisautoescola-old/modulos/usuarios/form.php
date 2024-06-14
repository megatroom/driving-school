<?php

include_once("../../configuracao.php");

$pId = null;
if (isset($_GET["pId"])) {
    $pId = $_GET["pId"];
}

$login         = null;
$nome          = null;
$idfuncionario = null;
$observacao    = null;

$mysql = new modulos_global_mysql();

if (isset ($pId)) {    
    $fieldList = $mysql->select(
            'u.login, u.observacao, u.nome, u.idcliente, u.idfuncionario',
            'usuarios u ',
            "u.id = '".$pId."'");
    if (is_array($fieldList)) {
        foreach ($fieldList as $fields) {
            extract($fields);
        }
    }
}

$qryFuncionario = $mysql->select(
        'f.id, p.nome',
        'funcionarios f '.
            'left join pessoas p on f.idpessoa = p.id ',
        null, null, 'nome');
$funcionarios = null;
$funcionarios[""] = "";
if (is_array($qryFuncionario)) {
    foreach($qryFuncionario as $query) {
        $funcionarios[$query["id"]] = $query["nome"];
    }
}

$form = new modulos_global_form('Usuarios');

$form->buttonSave("saveUsuario");
$form->buttonCancel("closeUsuario",null,"modulos/usuarios/index.php");
$form->divClear(1);

$form->divAlert();

$form->inputHidden('fid',$pId);

$form->startFieldSet('funcao_basic');
$form->inputText('flogin', 'Login', $login, "20", true);
$form->inputPassword('fsenha', 'Senha', null, "10", true);
$form->inputPassword('fsenhaconfirm', 'Confirmar Senha', null, "10", false);
$form->inputText('fnome', 'Nome', $nome, '60', false, "455px");
$form->selectFixed('ffun', 'Funcionário', false, $funcionarios, $idfuncionario, '460px');
$form->textArea('fobs', 'Observação', $observacao, null, false, '455px');
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveUsuario").click(function(event){
        $.post(
            "modulos/usuarios/save.php",
            { id : $("#fid").val(),
                login : $("#flogin").val(),
                nome : $("#fnome").val(),
                senha : $("#fsenha").val(),
                senhaconfirm : $("#fsenhaconfirm").val(),
                idfuncionario : $("#ffun").val(),
                observacao : $("#fobs").val()
            },
            function(data){
                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/usuarios/index.php','<?php echo $form->getdivAlertName(); ?>');
            }, "json");
        event.preventDefault();
    });
});
</script>