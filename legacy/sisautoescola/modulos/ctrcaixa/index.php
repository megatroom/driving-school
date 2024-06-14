<?php
include_once("../../configuracao.php");

$datai = date('d/m/Y');
$dataf = date('d/m/Y');

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
            "a.id, a.nome",
            "vusuarios a ",
            "(a.id in ".
            "(select b.idusuario from usuariosgrupousuario b, acesso c ".
            "where c.idgrupousuario = b.idgrupousuario and c.idtela = 21) or a.id in (select idusuario from contasareceber))",
            null,
            'nome');
$lstCaixas = null;
$lstCaixas["0"] = "TODOS";
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstCaixas[$row["id"]] = $row["nome"];
    }
}

$form = new modulos_global_form('frmCtrCaixa');

$form->startFieldSet('ctrCaixaFiltro');
$form->inputDate('fDataI', 'Data Inicial', $datai, true);
$form->inputDate('fDataF', 'Data Final', $dataf, false);
$form->selectFixed('fUsuario', 'Caixas', false, $lstCaixas, 0);
$form->null("<br />");
$form->buttonPesquisar('btnPesquisarCaixas');
$form->endFieldSet();

$form->close();

?>
<div id="tblCaixas"></div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnPesquisarCaixas").click(function(event) {
            $.post('modulos/ctrcaixa/index.xml.php',
            {
                datai : $("#fDataI").val(),
                dataf : $("#fDataF").val(),
                usuario : $("#fUsuario").val()
            }, function(data){
                $("#tblCaixas").html(data);
            });
            event.preventDefault();
        });
    });
</script>