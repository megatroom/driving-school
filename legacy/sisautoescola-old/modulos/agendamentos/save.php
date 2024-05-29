<?php
include_once("../../configuracao.php");

$id           = $_POST["id"];
$fieldsPost[] = getPost("idtipoagendamento");
$fieldsPost[] = getPost("data");
$fieldsPost[] = getPost("hora");
$fieldsPost[] = getPost("idaluno");
$fieldsPost[] = getPost("aprovado");

$descricao = NULL;
if (isset($_POST["descricao"])) {
    $descricao  = $_POST["descricao"];
}
$placa = NULL;
if (isset($_POST["placa"])) {
    $placa = $_POST["placa"];
}

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

foreach ($fieldsPost as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;
        if ($key == "idaluno" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Aluno' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "idtipoagendamento" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Tipo' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "data") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_date($value)) {
                    $tmp_value = date_to_db($value);
                } else {
                    $msgValidacao["msg"][] = "A Data informada é inválida.";
                    $validacao = false;
                }
            } else {
                $msgValidacao["msg"][] = "A Data é de preenchimento obrigatório.";
                $validacao = false;
            }
        }
        if ($key == "hora") {
            if (isset ($value) and strlen($value) > 0) {
                if (!is_valid_time($value)) {
                    $msgValidacao["msg"][] = "A Hora informada é inválida.";
                    $validacao = false;
                }
            }
        }

        if (isset ($tmp_value) and strlen($tmp_value) > 0){
            $fields[$key] = "'". $tmp_value ."'";
        } else {
            $fields[$key] = "null";
        }
    }
}

if ($validacao) {
    $id = $mysql->save($id, 'agendamentos', $fields, "id = '".$id."'");
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