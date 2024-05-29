<?php
include_once("../../configuracao.php");

$idaluno        = $_POST["idaluno"];
$idtiposervico  = $_POST["idtiposervico"];
$desconto       = $_POST["desconto"];

$id = 0;

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

$rows = $mysql->select('*', 'tiposervicos', "id = '".$idtiposervico."'");
foreach ($rows as $row) {
    $qtaulaspraticas = $row["qtaulaspraticas"];
    $qtaulasteoricas = $row["qtaulasteoricas"];
    $valor = $row["valor"];
}

$fields["idaluno"] = $idaluno;
$fields["idtiposervico"] = $idtiposervico;
$fields["data"] = 'now()';
$fields["desconto"] = float_to_db($desconto);
$fields["qtaulaspraticas"] = $qtaulaspraticas;
$fields["qtaulasteoricas"] = $qtaulasteoricas;
$fields["valor"] = $valor;

if ($validacao) {
    $id = $mysql->save($id, 'alunoservico', $fields);
    if ($id) {
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