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

$acesso = new modulos_usuarios_funcionalidades(9);

$pColNames = array('Código', 'Matrícula', 'Nome', 'Telefone', 'Celular');
$pColModel = array("{name:'id',index:'id', hidden:true}",
                    "{name:'matricula',index:'matricula', width:80}",
                    "{name:'nome',index:'nome', width:300}",
                    "{name:'telefone',index:'telefone', width:100}",
                    "{name:'celular',index:'celular', width:100}");
$pSortName = 'nome';

$mainGridFun = new modulos_global_grid('mainFun', 'Funcionários', 'modulos/funcionarios/index.xml.php', $pColNames, $pColModel, $pSortName, true);


$form = new modulos_global_form('Funcionarios');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewFunc','Novo Funcionário','modulos/funcionarios/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltFunc',null,$mainGridFun->getGridName(), 'id', 'modulos/funcionarios/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcFunc',null,$mainGridFun->getGridName(), 'id', 'modulos/funcionarios/index.php', 'modulos/funcionarios/delete.php');
}
$form->buttonClose($pCloseId, "bCloseFunc");

$form->close();

?>
<br /><br /><br />
<?php

if ($acesso->getFuncionalidade(2)) {
    $mainGridFun->eventOnDblClickRowAlterRow('id', 'modulos/funcionarios/form.php');
}
$mainGridFun->drawGrid();

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