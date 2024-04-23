<?php

include_once("../../configuracao.php");

$id = $_POST["id"];
$horario = $_POST["horario"];

$validacao = true;
$msgValidacao = "";

$fields = null;
$where = null;

$mysql = new modulos_global_mysql();

if (isset ($horario) and strlen($horario) > 0) {
    $fields['hora'] = "'". $horario ."'";
} else {
    $msgValidacao["msg"][] = "Campo 'Horário' é de preenchimento obrigatório.";
    $validacao = false;
}

$countfuncao = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'examepraticohorario',
                        "lower(horario) = lower('".$horario."') and id <> '".$id."'");

if ($countfuncao > 0) {
    $msgValidacao["msg"][] = "Já existe este horário cadastrado.";
    $validacao = false;
}

if ($id > 0) {
    $where = "id = ".$id;
}

if ($validacao) {
    if ($mysql->save($id, 'examepraticohorario', $fields, $where)) {
        $msgValidacao["retornoStatus"][] = "save";
    } else {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
        echo json_encode($msgValidacao);
        exit;
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
    echo json_encode($msgValidacao);
    exit;
}

echo json_encode($msgValidacao);

?>