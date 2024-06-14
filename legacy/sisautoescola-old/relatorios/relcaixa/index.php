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

$form->divAlert();

$form->startFieldSet('ctrCaixaFiltro');
$form->inputDate('fDataI', 'Data Inicial', $datai, true);
$form->inputDate('fDataF', 'Data Final', $dataf, false);
$form->selectFixed('fUsuario', 'Caixas', false, $lstCaixas, 0);
$form->divClear(1);
$form->checkbox('chckOpcoes', 'Consolidado Geral', '1', false, false);
$form->checkbox('chckOpcoes', 'Consolidado por Período', '2', false, false);
$form->checkbox('chckOpcoes', 'Consolidado por Caixa', '3', false, false);
$form->checkbox('chckOpcoes', 'Consolidado por Tipo de Serviço', '6', false, false);
$form->checkbox('chckOpcoes', 'Analítico Geral', '4', false, false);
$form->checkbox('chckOpcoes', 'Analítico Detalhado', '5', false, false);
$form->checkbox('chckOpcoes', 'Pagamento do Aluno Geral', '9', false, false);
$form->checkbox('chckOpcoes', 'Pagamento do Aluno Detalhado', '7', false, false);
$form->checkbox('chckOpcoes', 'Pagamento do Aluno Simples', '10', false, false);
$form->checkbox('chckOpcoes', 'Devedores por Período', '8', false, false);
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
        var usuario = "&usuario=" + $("#fUsuario").val();
        var vTipoRel = "tiporel="+pTipoRel;
        var vOpcoes = "";
        $(":checkbox").each(function(){
            if ($(this).is(':checked')) {
                if (vOpcoes == "") {
                    vOpcoes = "&opcoes=" + $(this).val();
                } else {
                    vOpcoes = vOpcoes + "," + $(this).val();
                }
            }
        });
        if (vOpcoes == "") {
            mensagemAlert("Selecione um tipo de relatório!");
        } else {
            openRelatorio("relatorios/relcaixa/emissao.php?"+vTipoRel+datai+dataf+usuario+vOpcoes);
        }
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>