<?php

include_once("../../configuracao.php");

function fieldSystemId($mysql, $campo) {
    $id = $mysql->getValue('id', 'id', 'sistema', "campo = '$campo'");
    if (isset ($id) and strlen($id) > 0) {
        return $id;
    } else {
        return 0;
    }
}

$mysql = new modulos_global_mysql();

$validacao = true;

if (!isset($_POST["tema"])) {
    $validacao = false;
    $msgValidacao["msg"][] = "Campo 'Tema' é de preenchimento obrigatório.";
}

if (!isset($_POST["janela"])) {
    $validacao = false;
    $msgValidacao["msg"][] = "Campo 'Janela' é de preenchimento obrigatório.";
} else {
    if ($_POST["janela"] != "2") {
        $validacao = false;
        $msgValidacao["msg"][] = "Esta versão suporta somente janelas individuais. Selecione-a.";
    }
}

if (!isset($_POST["horanoturna"])) {
    $validacao = false;
    $msgValidacao["msg"][] = "Campo 'Hora Noturna' é de preenchimento obrigatório.";
}

if (isset($_POST["backupdir"])) {
    if ($_POST["backupdir"][0] != "/") {
        $validacao = false;
        $msgValidacao["msg"][] = "Diretórios devem comerçar com '/', que é o diretório raiz.";
    } else if (strpos($_POST["backupdir"], "\\")) {
        $validacao = false;
        $msgValidacao["msg"][] = "Diretórios devem ser separados por '/' e não por '\'.";
    } else if ($_POST["backupdir"][strlen($_POST["backupdir"])-1] == "/") {
        $validacao = false;
        $msgValidacao["msg"][] = "Diretórios não podem terminar com '/'.";
    }
} else {
    $validacao = false;
    $msgValidacao["msg"][] = "Campo 'Diretório para Backup' é de preenchimento obrigatório.";
}

if ($validacao) {

    $campo = 'titulo_sistema';
    if (isset ($_POST[$campo]) and strlen($_POST[$campo]) > 0) {
        $valor = "'$_POST[$campo]'";
    } else {
        $valor = 'null';
    }
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $campo = 'tema';
    $valor = "'$_POST[$campo]'";
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $campo = 'janela';
    $valor = "'$_POST[$campo]'";
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $campo = 'horanoturna';
    $valor = "'$_POST[$campo]'";
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $campo = 'reltitulo';
    $valor = "'$_POST[$campo]'";
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $campo = 'reldesc';
    $valor = "'$_POST[$campo]'";
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $campo = 'datainiciocaixa';
    $valor = "'".date_to_db($_POST[$campo])."'";
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $campo = 'backupdir';
    $valor = "'$_POST[$campo]'";
    $id = fieldSystemId($mysql, $campo);
    $mysql->save($id, 'sistema', array("campo"=>"'$campo'", "valor"=>$valor), "id = '$id'");

    $msgValidacao["retornoStatus"][] = "save";
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
    echo json_encode($msgValidacao);
    exit;
}

echo json_encode($msgValidacao);

?>