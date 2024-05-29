<?php
include_once("../../configuracao.php");

$id           = $_POST["id"];
$fieldsPost[] = getPost("idturno");
$fieldsPost[] = getPost("diasemana");
$fieldsPost[] = getPost("horai");
$fieldsPost[] = getPost("horaf");

$idturno = $_POST["idturno"];
$diasemana = $_POST["diasemana"];
$horai = $_POST["horai"];
$horaf = $_POST["horaf"];

$validacao = true;
$msgValidacao = "";
$tmp_value = "";

$fields = null;

$mysql = new modulos_global_mysql();

$countexistente = $mysql->getValue(
                        'count(id) as total',
                        'total',
                        'expediente',
                        "diasemana = '".$diasemana."' and idturno = '".$idturno."' and id <> '".$id."'");
if ($countexistente > 0) {
    $msgValidacao["msg"][] = "Dia da semana já cadastrado para este turno.";
    $validacao = false;
}

foreach ($fieldsPost as $field) {
    foreach($field as $key => $value) {
        $tmp_value = $value;
        if ($key == "descricao" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Descrição' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "idturno" and (!isset ($value) or strlen($value) == 0)) {
            $msgValidacao["msg"][] = "Campo 'Turno' é de preenchimento obrigatório.";
            $validacao = false;
        }
        if ($key == "diasemana") {
            if (isset ($value) and strlen($value) > 0) {

            } else {
                $msgValidacao["msg"][] = "O campo 'Dia da Semana' é de preenchimento obrigatório.";
                $validacao = false;
            }
        }
        if ($key == "horai") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_time($value)) {
                    $tmp_value = $value;
                } else {
                    $msgValidacao["msg"][] = "A Hora Inicial informada é inválida.";
                    $validacao = false;
                }
            } else {
                $msgValidacao["msg"][] = "O campo 'Hora Inicial' é de preenchimento obrigatório.";
                $validacao = false;
            }
        }
        if ($key == "horaf") {
            if (isset ($value) and strlen($value) > 0) {
                if (is_valid_time($value)) {
                    $tmp_value = $value;
                } else {
                    $msgValidacao["msg"][] = "A Hora Final informada é inválida.";
                    $validacao = false;
                }
            } else {
                $msgValidacao["msg"][] = "O campo 'Hora Final' é de preenchimento obrigatório.";
                $validacao = false;
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
    if (strtotime($horai) >= strtotime($horaf)) {
        $msgValidacao["msg"][] = "A hora inicial deve ser menor que a hora final.";
        $validacao = false;
    }
}

if ($validacao) {
    $id = $mysql->save($id, 'expediente', $fields, "id = '".$id."'");
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