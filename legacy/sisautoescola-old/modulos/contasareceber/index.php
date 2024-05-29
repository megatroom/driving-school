<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pCloseId = null;
if (isset($_GET["pCloseId"])) {
    $pCloseId = $_GET["pCloseId"];
}

$acesso = new modulos_usuarios_funcionalidades(19);

$form = new modulos_global_form('ContasAPagar');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewContasAPagar','Nova Conta','modulos/contasareceber/form.php');
}
$exibeButtonAlterar = "'N'";
if ($acesso->getFuncionalidade(3) or $acesso->getFuncionalidade(4)) {
    $exibeButtonAlterar = "'S'";
}
$exibeButtonExc = "'N'";
if ($acesso->getFuncionalidade(2)) {
    $exibeButtonExc = "'S'";
}
$form->buttonClose($pCloseId, "bCloseContasAPagar");

$form->null('<br /><br /><br />');
//$form->radiobutton('fPago', 'Exibir só contas não pagas', true);
//$form->radiobutton('fPago', 'Exibir todas contas', false);

$form->startFieldSet('fdFiltro', 'Filtro');
$form->inputText('fAluno', 'Nome do Aluno', null, null, false, "440px", null, null);
$form->inputText('fMatricula', 'Matrícula', null, null, true);
$form->inputText('fMatriculaCFC', 'Matrícula CFC', null, null, true);
$form->endFieldSet();

$form->close();

?>
<div id="contentGrid"></div>
<script type="text/javascript">
<?php

if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}

?>
    var vPage = 1;
    $(document).ready(function(){
        carregarGrid();
        $("#fAluno").keyup(function(key) {
            if(key.keyCode == 13) {
                carregarGrid();
            }
        });
        $("#fMatricula").keyup(function(key) {
            if(key.keyCode == 13) {
                carregarGrid();
            }
        });
        $("#fMatriculaCFC").keyup(function(key) {
            if(key.keyCode == 13) {
                carregarGrid();
            }
        });
    });
    function carregarGrid() {
        $("#contentGrid").html('<img src="images/carregando.gif" width="100px" height="100px" />');
        var vUrl = 'modulos/contasareceber/index.xml.php';
        if ($("#fPago").attr('checked')) {
            vUrl = vUrl + '?status=aberto';
        }
        var vAluno = $("#fAluno").val();
        var vMatricula = $("#fMatricula").val();
        var vMatrCFC = $("#fMatriculaCFC").val();
        $.post(vUrl, { 
                page : vPage,
                aluno : vAluno,
                matricula : vMatricula,
                matriculacfc : vMatrCFC,
                btnalterar : <?php echo $exibeButtonAlterar ?>,
                btnexcluir : <?php echo $exibeButtonExc ?>
            }, function(data) {
            $("#contentGrid").html(data);
        });
    }
</script>