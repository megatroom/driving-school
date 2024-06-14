<?php
include_once("../../configuracao.php");

$data = $_POST["data"];
$categoria = $_POST["categoria"];
$status = $_POST["status"];

$validacao = true;

if (!isset ($data) or !is_valid_date($data)) {
    $msgValidacao["msg"][] = 'Data inválida.';
    $validacao = false;
}
if ($categoria != "A" and $categoria != "B") {
    $msgValidacao["msg"][] = 'Categoria inválida.';
    $validacao = false;
}

$mysql = new modulos_global_mysql();

$countExistente = $mysql->getValue(
        "count(id) as total",
        "total",
        "examepratico",
        "data = '".date_to_db($data)."' and categoria = '".$categoria."'");

if ($countExistente > 0) {
    $msgValidacao["msg"][] = 'Já foi cadastrado esta data para esta categoria.';
    $validacao = false;
}

if ($validacao) {
    if ($idexamepratico == 0) {
        $fields = null;
        $fields["data"] = "'". date_to_db($data) ."'";
        $fields["categoria"] = "'". $categoria ."'";
        $fields["status"] = "'". $status ."'";

        $id = $mysql->save(0, 'examepratico', $fields, "id = '".$idexamepratico."'");

        if ($id) {
            $msgValidacao["retornoStatus"][] = "save";
        } else {
            $msgValidacao["retornoStatus"][] = "erro";
            $msgValidacao["titulo"][] = "Ocorreu um erro no banco de dados.";
            $msgValidacao["msg"][] = "Mensagem técnica: ". $mysql->getMsgErro();
            echo json_encode($msgValidacao);
            exit;
        }
    }
} else {
    $msgValidacao["retornoStatus"][] = "validacao";
    $msgValidacao["titulo"][] = "Aviso.";
    echo json_encode($msgValidacao);
    exit;
}

echo json_encode($msgValidacao);

?>