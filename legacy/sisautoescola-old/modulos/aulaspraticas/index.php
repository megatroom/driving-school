<?php
include_once("../../configuracao.php");

$parIdAluno = NULL;
if (isset($_POST["pIdAluno"])) {
    $parIdAluno = $_POST["pIdAluno"];
}
$parReturn = NULL;
if (isset($_POST["pReturn"])) {
    $parReturn  = $_POST["pReturn"];
}

$mysql = new modulos_global_mysql();

$lstTurnos = null;
$rows = $mysql->select('id, descricao', 'turnos', null, null, 'descricao');
if (is_array($rows)) {
    foreach ($rows as $row) {
        $lstTurnos[$row["id"]] = $row["descricao"];
    }
} else {
    echo '<h2>Não há Turno cadastrado.</h2>';
    exit;
}

$lstDias = null;
$lstDias["todos"] = 'Todos';
$lstDias["par"] = 'Par';
$lstDias["impar"] = 'Ímpar';

$lstExibir = null;
$lstExibir["todos"] = 'Todos';
$lstExibir["marcadas"] = 'Somente aulas marcadas';
$lstExibir["naomarcadas"] = 'Somente aulas não marcadas';

$form = new modulos_global_form('AulasPraticas');

$datai = date('d/m/Y');
$dataf = date('d/m/Y', time()+3600*24*15);
$horai = '00:00';
$horaf = '23:59';

if (isset ($parReturn)) {
    $form->buttonCancel('fVoltarServico', 'Voltar para o Serviços', $parReturn);
    $form->null('<br /><br />');
}

$form->startFieldSet('fdAulasPraticasIndx');
$form->inputDate('fDataI', 'Data Inicial', $datai, true);
$form->inputDate('fDataF', 'Data Final', $dataf, true);
$form->inputTime('fHoraI', 'Hora Inicial', $horai, true);
$form->inputTime('fHoraF', 'Hora Final', $horaf, false);
$form->selectFixed('fDias', 'Dias', true, $lstDias, 'todos', '110px');
$form->selectFixed('fTurno', 'Turno', true, $lstTurnos, null, '110px');
$form->selectFixed('fExibir', 'Exibir', false, $lstExibir, 'todos', '210px');
$form->null('<div style="clear:both"></div><br>');
$form->checkbox('fSemana1', 'Domingo', null, true, true);
$form->checkbox('fSemana2', 'Segunda', null, true, true);
$form->checkbox('fSemana3', 'Terça', null, true, true);
$form->checkbox('fSemana4', 'Quarta', null, true, true);
$form->checkbox('fSemana5', 'Quinta', null, true, true);
$form->checkbox('fSemana6', 'Sexta', null, true, true);
$form->checkbox('fSemana7', 'Sábado', null, true, false);
$form->null('<div style="clear:both"></div><br>');
$form->inputTextStaticLookUp('carros', 'fCarro', 'fIdCarro', 'Carro', 'bCarro', null, false);
$form->null('<br />');
$form->buttonCustom('bPesqAulasPraticas', 'Pesquisar', 'ui-icon-search', NULL, 'fg-button-red');
$form->endFieldSet();

$form->startFieldSet('fdBackTabAulaPratica');
$form->inputTextStaticLookUp('alunos', 'fAluno', 'fIdAluno', 'Aluno', 'bAluno', null, false, null, null, null, $parIdAluno);
$form->null('<div id="fdTabelaAulasPraticas"></div>');
$form->endFieldSet();

$form->close();

?>
<div style="position: fixed;top: 0px;right: 0px;" id="dAPPlacar"></div>
<div id="dvCarregandoTblAulasPraticas" style="display: none;">
    <table border="0">
        <tr>
            <td width="100px" align="center"><img src="images/carregando.gif" width="60px" height="60px" /></td>
            <td><h2>Carregando...</h2></td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    var idTipoCarro = 0;
    function atualizarPlacar() {
        $.post('modulos/aulaspraticas/placar.php',
            {
                idaluno: $("#fIdAluno").val()
            },
            function(data) {
                $("#dAPPlacar").html(data);
            }
        );
    }
    $(document).ready(function(){
        atualizarCarros();
        atualizarPlacar();

        $("#fdTabelaAulasPraticas").hide();

        $("#fAluno").change(function(){
            atualizarPlacar();
        });

        $("#fTurno").change(function(){
            atualizarCarros();
        });

        $("#bPesqAulasPraticas").click(function(event){
            $("#fdTabelaAulasPraticas").html($("#dvCarregandoTblAulasPraticas").html());
            $("#fdTabelaAulasPraticas").show('slow');
            
            var vIdCarro;

            if ($("#fIdCarro").val() == '') {
                vIdCarro = 0;
            } else {
                vIdCarro = $("#fIdCarro").val();
            }

            var vSemana1 = 'N';
            var vSemana2 = 'N';
            var vSemana3 = 'N';
            var vSemana4 = 'N';
            var vSemana5 = 'N';
            var vSemana6 = 'N';
            var vSemana7 = 'N';
            if ($("#fSemana1").attr('checked')) {
                vSemana1 = 'S';
            }
            if ($("#fSemana2").attr('checked')) {
                vSemana2 = 'S';
            }
            if ($("#fSemana3").attr('checked')) {
                vSemana3 = 'S';
            }
            if ($("#fSemana4").attr('checked')) {
                vSemana4 = 'S';
            }
            if ($("#fSemana5").attr('checked')) {
                vSemana5 = 'S';
            }
            if ($("#fSemana6").attr('checked')) {
                vSemana6 = 'S';
            }
            if ($("#fSemana7").attr('checked')) {
                vSemana7 = 'S';
            }

            $.post('modulos/aulaspraticas/tabela.php', {
                datai : $("#fDataI").val(),
                dataf : $("#fDataF").val(),
                horai : $("#fHoraI").val(),
                horaf : $("#fHoraF").val(),
                idturno : $("#fTurno").val(),
                exibir : $("#fExibir").val(),
                idcarro : vIdCarro,
                idtipocarro : idTipoCarro,
                dias : $("#fDias").val(),
                semana1 : vSemana1,
                semana2 : vSemana2,
                semana3 : vSemana3,
                semana4 : vSemana4,
                semana5 : vSemana5,
                semana6 : vSemana6,
                semana7 : vSemana7
            }, function(data) {
                $("#fdTabelaAulasPraticas").html(data);
                $("#fdTabelaAulasPraticas").show('slow');
            });

            event.preventDefault();
        });
    });
    function atualizarCarros() {
        var vUrl = "";
        var idTurno = $("#fTurno").val();        
        $("#fCarro").val('');
        $("#fIdCarro").val('');
        $.post('modulos/aulaspraticas/alunoturno.php', { idturno: idTurno }, function(data){
            idTipoCarro = data.idtipocarro;
            vUrl = 'modulos/carros/carro.xml.php?idtipocarro='+idTipoCarro;
            jQuery("#tblgrdgrdCnsCarro").jqGrid('setGridParam',{url:vUrl}).trigger("reloadGrid");
        }, "json");
    }
</script>