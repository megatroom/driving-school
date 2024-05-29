<?php
include_once("../../configuracao.php");

//$mysql = new modulos_global_mysql();

$form = new modulos_global_form('RelResultadosET');

$form->divAlert();

$form->startFieldSet('fdRelResultadosET');
$form->inputDate('fDataI', 'Data Inicial', '01'.date('/m/Y'), true);
$form->inputDate('fDataF', 'Data Final', date('t/m/Y'), true);
$form->divClear();
$form->endFieldSet();

$form->startFieldSet('fdRelResultadosETEmail');
$form->checkbox('fExibeColunaEmail', 'Exibir coluna de e-mail', null, false, true);
$form->checkbox('fNaoExibirAlunoSemEmail', 'Não exibir aluno marcado como "Não possui e-mail"', null, false, true);
$form->divClear();
$form->endFieldSet();

$form->buttonVisualizar('btnVisualizarRelResultadosET');
$form->buttonImprimir('btnImprimirRelResultadosET');
$form->buttonExcel('btnGerarExcelRelResultadosET');
$form->buttonWord('btnGerarWordRelResultadosET');

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnVisualizarRelResultadosET").click(function(event){
            emitirRelatorio(1);
            event.preventDefault();
        });
        $("#btnImprimirRelResultadosET").click(function(event){
            emitirRelatorio(2);
            event.preventDefault();
        });
        $("#btnGerarExcelRelResultadosET").click(function(event){
            emitirRelatorio(3);
            event.preventDefault();
        });
        $("#btnGerarWordRelResultadosET").click(function(event){
            emitirRelatorio(4);
            event.preventDefault();
        });
    });
    function emitirRelatorio(pTipo) {
        var datai = "&datai=" + $("#fDataI").val();
        var dataf = "&dataf=" + $("#fDataF").val();
        var vTipo = "tipo="+pTipo;
        var vExibeColunaEmail = "&exibeColunaEmail=";
        if ($("#fExibeColunaEmail").is(':checked')) {
            vExibeColunaEmail += "S";
        } else {
            vExibeColunaEmail += "N";
        }
        var vNaoExibirAlunoSemEmail = "&naoExibirAlunoSemEmail=";
        if ($("#fNaoExibirAlunoSemEmail").is(':checked')) {
            vNaoExibirAlunoSemEmail += "S";
        } else {
            vNaoExibirAlunoSemEmail += "N";
        }
        openRelatorio("relatorios/examepratico/emissao.php?"+
                vTipo+datai+dataf+vExibeColunaEmail+vNaoExibirAlunoSemEmail);
    }
    function mensagemAlert(pTexto) {
        divAlertCustomBasic("<?php echo $form->getdivAlertName(); ?>", pTexto);
    }
</script>