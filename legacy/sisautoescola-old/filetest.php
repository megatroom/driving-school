<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$file = "C:\\Bruno\\Figuras\\demonhunter.gif";

$pont = fopen($file, "rb") or die ("erro file");

$dados = addslashes(fread($pont, filesize($file)));

//$conn = mysqli_connect("localhost", "root", "", "lethusged") or die("Erro na conexão com o BD");

//$sql = mysqli_query($conn, "INSERT INTO teste (id, arquivo) VALUES (1, '".$dados."') ");

//$sql = mysqli_query($conn, "select * from teste ");


  echo "<img src=\"gera.php?id=1\" width=\"100\" height=\"100\" border=\"1\">";
  echo "<br><br>";

  echo "tipo ". $_FILES["txtFoto"]["type"];

?>
<form action="filetest.php" method="POST" enctype="multipart/form-data">
<input type="file" name="txtFoto" />
<button type="submit" >Vai</button>
</form>