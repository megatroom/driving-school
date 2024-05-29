<?php
include_once("../../configuracao.php");

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$pIdClose = null;
if (isset($_GET["pCloseId"])) {
    $pIdClose = $_GET["pCloseId"];
}

$acesso = new modulos_usuarios_funcionalidades(10);

$pColNames = array('Código', 'Matrícula', 'Matrícula CFC', 'Nome');
$pColModel = array( "{name:'id',index:'id', hidden:true}",
                    "{name:'matricula',index:'matricula', width:100}",
                    "{name:'matriculacfc',index:'matriculacfc', width:100}",
                    "{name:'nome',index:'nome', width:300}");
$pSortName = 'nome';

$mainGridCli = new modulos_global_grid('mainGrdAluno', 'Alunos', 'modulos/obsaluno/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$form = new modulos_global_form('Clientes');

$form->divAlert();

$form->buttonAlt('bAltAluno',null,$mainGridCli->getGridName(), 'id', 'modulos/obsaluno/form.php');

$form->buttonClose($pIdClose, "bCloseAluno");

$form->close();

?>
<br /><br /><br />
<?php

if ($acesso->getFuncionalidade(2)) {
    $mainGridCli->eventOnDblClickRowAlterRow('id', 'modulos/obsaluno/form.php');
}

$mainGridCli->drawGrid();

?>
<script type="text/javascript">
<?php

if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";        
    }
}

?>
</script>