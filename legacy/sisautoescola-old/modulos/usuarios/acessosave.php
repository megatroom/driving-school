<?php

include_once("../../configuracao.php");

$idgrupo = $_POST["idgrupo"];
$telas = explode("|", $_POST["telas"]);
$funcs = explode("|", $_POST["funcionalidades"]);

$validacao = true;
$msgValidacao = "";

$fields = null;
$where = null;

$mysql = new modulos_global_mysql();

$mysql->delete('acesso', "idgrupousuario = '".$idgrupo."'");

$fieldsAcesso = null;
if (is_array($telas)) {
    foreach ($telas as $value) {
        $fieldsAcesso = null;
        $fieldsAcesso["idgrupousuario"] = $idgrupo;
        $fieldsAcesso["idtela"] = $value;
        $mysql->save(0, 'acesso', $fieldsAcesso);
    }
}

$mysql->delete('acessofunc', "idgrupousuario = '".$idgrupo."'");

$fieldsAcesso = null;
if (is_array($funcs)) {
    foreach ($funcs as $value) {
        $fieldsAcesso = null;
        $fieldsAcesso["idgrupousuario"] = $idgrupo;
        $fieldsAcesso["idfuncionalidade"] = $value;
        $mysql->save(0, 'acessofunc', $fieldsAcesso);
    }
}

if ($validacao) {
    $msgValidacao["retornoStatus"][] = "save";
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
    echo json_encode($msgValidacao);
    exit;
}

echo json_encode($msgValidacao);

?>