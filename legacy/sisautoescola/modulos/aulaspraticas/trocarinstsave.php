<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$idfuncionario = $_POST["idfuncionario"];
$idcarro = $_POST["idcarro"];
$data = $_POST["data"];
$hora = $_POST["hora"];
$idturno = $_POST["idturno"];

$validacao = true;
$retorno = null;

$fields = null;

$id = $mysql->getValue(
        'id',
        'id',
        'carrofuncionario',
        "idcarro = '".$idcarro."' and data = '".date_to_db($data)."' and hora = '".$hora."'");

$duracao = $mysql->getValue(
        "duracaoaula",
        null,
        "turnos",
        "id = '".$idturno."'");

if (isset ($id) and is_numeric($id) and $id > 0) {
    $fields["idfuncionario"] = $idfuncionario;

    $ultimoId = $mysql->getValue(
        'max(id) as id',
        'id',
        'carrofuncionario',
        "idcarro = '".$idcarro."' and data < '".date_to_db($data)."'");

} else {
    $id = 0;
    $fields["idfuncionario"] = $idfuncionario;
    $fields["idcarro"] = $idcarro;
    $fields["data"] = "'".date_to_db($data)."'";
    $fields["hora"] = "'".$hora."'";

    $ultimoId = $mysql->getValue(
        'max(id) as id',
        'id',
        'carrofuncionario',
        "idcarro = '".$idcarro."'");
}

if ($validacao) {
    if ($mysql->save($id, 'carrofuncionario', $fields, "id = '".$id."'")) {

        if (isset ($ultimoId) and is_numeric($ultimoId) and $ultimoId > 0) {

            $idfuncionario = $mysql->getValue("idfuncionario", null, "carrofuncionario", "id = '".$ultimoId."'");
            $hora = strftime("%H:%M", strtotime($hora." + ".$duracao." minutes"));

            $totExiste = $mysql->getValue(
                    "count(*) as total",
                    "total",
                    "carrofuncionario",
                    "idcarro = '".$idcarro."' and data = '".date_to_db($data)."' and hora = '".$hora."'");

            if ($totExiste == 0) {
                unset ($fields);
                $fields = null;
                $fields["idfuncionario"] = $idfuncionario;
                $fields["idcarro"] = $idcarro;
                $fields["data"] = "'".date_to_db($data)."'";
                $fields["hora"] = "'".$hora."'";

                if ($mysql->save(0, 'carrofuncionario', $fields, "id = '".$id."'")) {
                    $retorno["returnStatus"] = 'save';
                } else {
                    $retorno["returnStatus"] = 'erro';
                    $retorno["msg"] = 'Erro: '.$mysql->getMsgErro();
                }
            } else {
                $retorno["returnStatus"] = 'save';
            }
        } else {
            $retorno["returnStatus"] = 'save';
        }

    } else {
        $retorno["returnStatus"] = 'erro';
        $retorno["msg"] = 'Erro: '.$mysql->getMsgErro();
    }
} else {
    $retorno["returnStatus"] = 'validacao';
}

echo json_encode($retorno);

?>