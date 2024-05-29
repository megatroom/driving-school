<?php
include_once("../../configuracao.php");

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

$form = new modulos_global_form('RelResultadosET');

$form->divAlert();

$form->startFieldSet('fdRelResultadosET');
$form->inputDate('fDataI', 'Data Inicial', '01'.date('/m/Y'), true);
$form->inputDate('fDataF', 'Data Final', date('t/m/Y'), false);
$form->divClear(1);
$form->selectFixed('fUsuario', 'Caixas', false, $lstCaixas, 0);
$form->endFieldSet();

$form->buttonVisualizar('btnVisualizar');
$form->buttonImprimir('btnImprimir');
$form->buttonExcel('btnGerarExcel');
$form->buttonWord('btnGerarWord');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnVisualizar").click(function(event){
            emitirRelatorio(1);
            event.preventDefault();
        });
        $("#btnImprimir").click(function(event){
            emitirRelatorio(2);
            event.preventDefault();
        });
        $("#btnGerarExcel").click(function(event){
            emitirRelatorio(3);
            event.preventDefault();
        });
        $("#btnGerarWord").click(function(event){
            emitirRelatorio(4);
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipoRel) {
        var datai = "&datai=" + $("#fDataI").val();
        var dataf = "&dataf=" + $("#fDataF").val();
        var idusuario = "&idusuario=" + $("#fUsuario").val();
        var vTipoRel = "tipoRel="+pTipoRel;
        openRelatorio("relatorios/caixaporusuario/emissao.php?"+vTipoRel+datai+dataf+idusuario);
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>