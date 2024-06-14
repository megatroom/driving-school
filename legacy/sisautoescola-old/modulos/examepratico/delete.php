<?php
include_once("../../configuracao.php");

$id = $_POST["id"];

$validacao = true;
$msgValidacao = "";

$mysql = new modulos_global_mysql();

if (!isset ($id) or !is_numeric($id) or $id < 1) {
    $msgValidacao["msg"][] = "O código não foi informado.";
    $validacao = false;
}

$isTomorrowAndAfter = $mysql->getValue(
        "case when c.data <= NOW() then 'NAO' else 'SIM' end as resultado", 
        "resultado",
        "examepraticoalunos a, examepraticocarro b, examepratico c", 
        "a.idexamepraticocarro = b.id and b.idexamepratico = c.id and a.id = '".$id."'");

if (isset($isTomorrowAndAfter)) {
    if ($isTomorrowAndAfter == 'NAO') {
        $msgValidacao["msg"][] = "Não é possível remover um exame anterior a data atual.";
        $validacao = false;
    }
}

if ($validacao) {
    if ($mysql->delete('examepraticoalunos', "id = '".$id."'")) {
        $msgValidacao["retornoStatus"][] = "delete";
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