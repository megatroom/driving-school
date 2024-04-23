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

$acesso = new modulos_usuarios_funcionalidades(23);

$pColNames = array('Código', 'Login', 'Nome');
$pColModel = array("{name:'id',index:'id', hidden:true}",
                    "{name:'login',index:'login', width:80}",
                    "{name:'nome',index:'nome', width:300}");
$pSortName = 'nome';

$mainGridUsers = new modulos_global_grid('mainUsuarios', 'Usuários', 'modulos/usuarios/index.xml.php', $pColNames, $pColModel, $pSortName, true);

$form = new modulos_global_form('Usuarios');

$form->divAlert();

if ($acesso->getFuncionalidade(1)) {
    $form->buttonNew('bNewUser','Novo Usuário','modulos/usuarios/form.php');
}
if ($acesso->getFuncionalidade(2)) {
    $form->buttonAlt('bAltUser',null,$mainGridUsers->getGridName(), 'id', 'modulos/usuarios/form.php');
}
if ($acesso->getFuncionalidade(3)) {
    $form->buttonExc('bExcUser',null,$mainGridUsers->getGridName(), 'id', 'modulos/usuarios/index.php', 'modulos/usuarios/delete.php');
}
$form->buttonClose($pIdClose, "bCloseUser");

$form->close();

?>
<br /><br /><br />
<?php

if ($acesso->getFuncionalidade(2)) {
    $mainGridUsers->eventOnDblClickRowAlterRow('id', 'modulos/usuarios/form.php');
}
$mainGridUsers->drawGrid();

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