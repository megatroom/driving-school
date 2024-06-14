<?php
include_once("../../configuracao.php");

$id = $_POST["id"];
$status = $_POST["status"];

$validacao = true;

$mysql = new modulos_global_mysql();

if ($validacao) {
    if ($idexamepratico == 0) {
        $fields = null;
        $fields["status"] = "'". $status ."'";

        $id = $mysql->save($id, 'examepratico', $fields, "id = '".$id."'");

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