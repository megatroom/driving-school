<?php
include_once("../../configuracao.php");

$id = $_POST["id"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select('*', 'tiposervicos', "id = '".$id."'");

$resultado = NULL;

if (is_array($rows)) {
    foreach ($rows as $row) {
        $resultado["descricao"] = $row["descricao"];
        $resultado["qtaulaspraticas"] = $row["qtaulaspraticas"];
        $resultado["qtaulasteoricas"] = $row["qtaulasteoricas"];
        $resultado["valor"] = $row["valor"];
        $resultado["status"] = $row["status"];
    }
}

echo json_encode($resultado);

?>