<?php
include_once("../../configuracao.php");

$postArray = &$_POST;

$mysql = new modulos_global_mysql();

$postedValue = null;
if (is_array($postArray)) {
    foreach ( $postArray as $key => $value )
    {
        if ( get_magic_quotes_gpc() )
            $postedValue[] = stripslashes($value);
        else
            $postedValue[] = $value;
    }
}

$iddeclaracao = $mysql->getValue('max(id) as id', "id", "declaracaopagto");

if ($iddeclaracao == "") {
    $iddeclaracao = 0;
}

if (is_array($postedValue)) {
    foreach ($postedValue as $value) {
        $pFields = null;
        $pFields["texto"] = "'".$value."'";
        $id = $mysql->save($iddeclaracao, 'declaracaopagto', $pFields, "id = '".$iddeclaracao."'");
        if (!$id) {
            echo '<h1>Erro ao gravar no banco de dados o texto!</h1>';
            echo '<h3>'.$mysql->getMsgErro().'</h3>';
            exit;
        }
    }
}

?>
<script type="text/javascript" src="../../js/ckeditor/jquery15.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        alert('Declaração salva com sucesso!');
        window.close();
    });
</script>