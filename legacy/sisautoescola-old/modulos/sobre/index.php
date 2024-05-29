<?php
include_once("../../configuracao.php");

$mysql = new modulos_global_mysql();

$versaoSistema = $mysql->getValue('valor', null, 'sistema', "campo = 'versao'");

?>
<br />
<br />
<table style="width: 100%;text-align: center;">
    <tr>
        <td><h2>Sistema Auto Escola 4 Rodas</h2></td>
    </tr>
    <tr>
        <td><h2>Versão <?php echo $versaoSistema; ?></h2></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><h3>Copyright © 20011/2012 - Todos direitos reservados</h3></td>
    </tr>
</table>
<br />
<br >