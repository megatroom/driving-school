<?php
session_start();
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$pId = $mysql->getValue('id', 'id', 'usuarios', "login = '".$_SESSION["LOGIN"]."'");

$form = new modulos_global_form('Usuarios');

$form->divAlert();

$form->inputHidden('fid',$pId);

$form->startFieldSet('login');
$form->null('Login: '.$_SESSION["LOGIN"]);
$form->endFieldSet();

$form->startFieldSet('senha');
$form->inputPassword('fsenha', 'Senha', null, "10", true);
$form->inputPassword('fsenhaconfirm', 'Confirmar Senha', null, "10", false);
$form->endFieldSet();

$form->buttonSave("saveSenha");
$form->buttonClose($_GET["pCloseId"], "closeSenha");

$form->close();

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#saveSenha").click(function(event){
        $.post(
            "modulos/usuarios/senhasave.php",
            { id : $("#fid").val(),
                senha : $("#fsenha").val(),
                senhaconfirm : $("#fsenhaconfirm").val()
            },
            function(data){
                if (data.retornoStatus == 'save') {
                    document.location = "index.php";
                } else {
                    postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/usuarios/senha.php','<?php echo $form->getdivAlertName(); ?>');
                }
            }, "json");
        event.preventDefault();
    });
});
</script>