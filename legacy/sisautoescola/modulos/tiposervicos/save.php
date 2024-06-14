<?php
include_once("../../configuracao.php");

$id           = $_POST["id"];
$fieldsPost[] = getPost("descricao");
$fieldsPost[] = getPost("qtaulaspraticas");
$fieldsPost[] = getPost("qtaulasteoricas");
$fieldsPost[] = getPost("valor");
$fieldsPost[] = getPost("status");
$fieldsPost[] = getPost("diasavencer");

$descricao  = $_POST["descricao"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'tiposervicos',
                        "lower(descricao) = lower('".$descricao."') and id <> '".$id."'");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Já existe um tipo cadastrado com essa descrição";
    $validacao = false;
}

foreach ($fieldsPost as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;
        if ($key == "descricao" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Descrição' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "qtaulaspraticas" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Qtd. Aulas Práticas' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "qtaulasteoricas" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Qtd. Aulas Teóricas' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "valor" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Valor' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "status" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Status' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "diasavencer" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Dias a Vencer' é de preenchimento obrigatório.";
            $validacao = false;
        }

        if ($key == "valor") {
            $fields[$key] = "'". float_to_db($tmp_value) ."'";
        } else if (isset ($tmp_value) and strlen($tmp_value) > 0){
            $fields[$key] = "'". $tmp_value ."'";
        } else {
            $fields[$key] = "null";
        }
    }
}

if ($validacao) {
    $id = $mysql->save($id, 'tiposervicos', $fields, "id = '".$id."'");
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