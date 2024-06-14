<?php
include_once("../../configuracao.php");

$id           = $_POST["id"];
$fieldsPost[] = getPost("idtipocarro");
$fieldsPost[] = getPost("descricao");
$fieldsPost[] = getPost("duracaoaula");

$descricao  = $_POST["descricao"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'turnos',
                        "lower(descricao) = lower('".$descricao."') and id <> '".$id."'");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Já existe um turno cadastrado com essa descrição";
    $validacao = false;
}

foreach ($fieldsPost as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;
        if ($key == "idtipocarro" and (!isset ($value) or !is_numeric($value) or $value < 1)) {
            $msgValidacao["msg"][] = "Campo 'Tipo' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "descricao" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Descrição' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "duracaoaula" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Duração da Aula' é de preenchimento obrigatório.";
            $validacao = false;
        }

        if (isset ($tmp_value) and strlen($tmp_value) > 0){
            $fields[$key] = "'". $tmp_value ."'";
        } else {
            $fields[$key] = "null";
        }
    }
}

if ($validacao) {
    $id = $mysql->save($id, 'turnos', $fields, "id = '".$id."'");
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