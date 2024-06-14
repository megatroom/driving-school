<?php

//RECEBE PARÂMETRO
//$id = $_GET["id"];

//CONECTA AO MYSQL
$conn = mysqli_connect("localhost", "root", "", "lethusged");

//EXIBE IMAGEM
$sql = mysqli_query($conn, "SELECT arquivo FROM teste WHERE id = 1");

$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
$bytes = $row["arquivo"];

$type = "";
$subtype = "";

//EXIBE IMAGEM
header("Content-Type:image/gif");
echo $bytes;

// http://imasters.uol.com.br/artigo/3831/mysql/cadastrando_e_exibindo_imagens_diretamente_do_mysql_5/

?>