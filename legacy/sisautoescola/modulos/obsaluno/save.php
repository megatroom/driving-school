<?php
ob_start(); session_start(); ob_end_clean();
include_once("../../configuracao.php");

$id = $_POST["id"];
$newObs = $_POST["observacoes"];

$validacao = true;
$msgValidacao = "";

$mysql = new modulos_global_mysql();

$oldObs = $mysql->getValue('observacoes', 'observacoes', 'alunos', "id = '".$id."'");
$currentDatetime = $mysql->getValue("DATE_FORMAT(NOW(), '%d/%m/%y %H:%i') as cdate", 'cdate', null);

$observacoes = '';
if (isset($oldObs) && $oldObs !== '') {
    $observacoes = $oldObs .'\n\n';
}

$observacoes .= '====== '. $_SESSION["USUARIO_NOME"] .' '. $currentDatetime ." ======\n";
$observacoes .= $newObs;

$fields = null;
$fields["observacoes"] = "'". $observacoes ."'";

if ($validacao) {
    $idAlunoSaved = $mysql->save($id, 'alunos', $fields,"id = '".$id."'");
    if (!$idAlunoSaved) {
        $msgValidacao["retornoStatus"][] = "erro";
        $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
        $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
    } else {
        $msgValidacao["retornoStatus"][] = "save";
        $msgValidacao["titulo"][] = "Aluno salvo com sucesso!";
        $msgValidacao["idaluno"][] = $idAlunoSaved;
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
}

echo json_encode($msgValidacao);

?>