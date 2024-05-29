<?php
include_once("../../configuracao.php");
?>
<style type="text/css">
.aulapraticaadd {
    color: red !important;
}
.aulapraticaexc {
    color: blue !important;
}
.aulapraticabloq {
    color: black !important;
}
</style>
<?php

$acesso = new modulos_usuarios_funcionalidades(2);
$hasValidacaoAccess = $acesso->getFuncionalidade(4);

$datai = $_POST["datai"];
$dataf = $_POST["dataf"];
$timei = $_POST["horai"];
$timef = $_POST["horaf"];
$idturno = $_POST["idturno"];
$exibir = $_POST["exibir"];
$idcarro = $_POST["idcarro"];
$idtipocarro = $_POST["idtipocarro"];
$dias = $_POST["dias"];
$semana1 = $_POST["semana1"];
$semana2 = $_POST["semana2"];
$semana3 = $_POST["semana3"];
$semana4 = $_POST["semana4"];
$semana5 = $_POST["semana5"];
$semana6 = $_POST["semana6"];
$semana7 = $_POST["semana7"];

if (!isset ($datai) or !isset ($dataf)) {
    echo '<h3>Preecha todos os campos!</h3>';
    exit;
}
if (!is_valid_date($datai) or !is_valid_date($dataf)) {
    echo '<h3>Datas inválidas!</h3>';
    exit;
}
if (strtotime(date_to_number($datai)) > strtotime(date_to_db($dataf))) {
    echo '<h3>A data inicial deve ser menor que a data final!</h3>';
    exit;
}
if ($idcarro < 0 or !is_numeric($idcarro)) {
    echo '<h3>Carro inválido!</h3>';
    exit;
}
if (isset ($idturno) and $idturno != '') {
    if ($idturno < 0 or !is_numeric($idturno)) {
        echo '<h3>Turno inválido!</h3>';
        exit;
    }
} else {
    echo '<h3>O turno deve ser preenchido!</h3>';
    exit;
}

$arrayTime = explode(':', $timei);
$vTimeI = mktime($arrayTime[0], $arrayTime[1], 0, 0, 0, 0);
unset ($arrayTime);
$arrayTime = explode(':', $timef);
$vTimeF = mktime($arrayTime[0], $arrayTime[1], 0, 0, 0, 0);

$mysql = new modulos_global_mysql();

$form = new modulos_global_form('frmTblAulasPraticas');
$form->divAlertFlying();

$excDialogId = $form->divDialogOpen();
$pDialogNoFunction = null;
$pDialogNoFunction[] = '$(this).dialog("close");';
$pDialogNoFunction[] = 'idLastAultaPratica = 0;';
$pDialogYesFunction = null;
$pDialogYesFunction[] = '$(this).dialog("close");';
$pDialogYesFunction[] = 'linkAlunoAulaPratica();';
$form->divDialogAddButton('Não', $pDialogNoFunction);
$form->divDialogAddButton('Sim', $pDialogYesFunction);
$form->divDialogClose();

$lstCarrosFun = null;
if ($idcarro == 0) {
    $lstCarros = $mysql->select(
        "id, concat(descricao, ' - ', placa) as carro",
        "carros",
        "idtipocarro = '".$idtipocarro."'",
        null,
        "carro");

    if (!is_array($lstCarros)) {
        echo '<h3>Não foi encontrado nenhum carro!</h3>';
        exit;
    }
} else {
    $lstCarros = $mysql->select(
        "id, concat(descricao, ' - ', placa) as carro",
        "carros",
        "id = '".$idcarro."'");

    if (!is_array($lstCarros)) {
        echo '<h3>Não foi encontrado o carro!</h3>';
        exit;
    }

    foreach ($lstCarros as $value) {
        $form->inputTextStatic('fCarro', 'Carro', $value["carro"], false, "420px");
    }
}

$form->close();

$horai = null;
$horaf = null;
$duracaoaula = 60;
$rows = $mysql->select(
        'diasemana, horai, horaf, duracaoaula',
        'vexpedientes',
        "idturno = '".$idturno."'",
        null,
        'diasemana');
if (is_array($rows)) {
    foreach($rows as $row) {
        $horai[$row['diasemana']] = $row['horai'];
        $horaf[$row['diasemana']] = $row['horaf'];
        $duracaoaula = $row['duracaoaula'];
    }
} else {
    echo '<h3>Não há nenhum expediente cadastrado para este turno.</h3>';
    exit;
}
unset ($rows);

$countDivInstrutor = 0;

$lstFunCarro = null;
$whereFunCarro = "";
if (is_numeric($idcarro) and $idcarro > 0) {
    $whereFunCarro = "and a.idcarro = '".$idcarro."' ";
}
$rowsFunCarro = $mysql->select(
        "a.data, a.hora, a.idcarro, a.idfuncionario, b.nome",
        "carrofuncionario a, vfuncionarios b",
        "a.idfuncionario = b.id ".
            $whereFunCarro,
        null,
        "data, hora");
if (is_array($rowsFunCarro)) {
    foreach ($rowsFunCarro as $row) {
        $lstFunCarro[$row["idcarro"]][] = array(
            "datahora" => $row["data"] ." ". $row["hora"],
            "idfuncionario" => $row["idfuncionario"],
            "nome" => $row["nome"]);
    }
    unset ($rowsFunCarro);
} else {
    echo '<h3>Não há nenhum instrutor lançado para o(s) carro(s) neste período.</h3>';
    exit;
}

function getNomeInstrutor($pLista, $pIdCarro, $pDataHora) {
    $retorno = "";
    if (is_array($pLista) and array_key_exists($pIdCarro, $pLista) and is_array($pLista[$pIdCarro])) {
        foreach ($pLista[$pIdCarro] as $value) {
            $vDataHora = new DateTime($value["datahora"]);
            if ($pDataHora >= $vDataHora) {
                $retorno = $value["nome"];
            }
        }
    }
    return $retorno;
}

?>
<br />
<table id="users" class="ui-widget ui-widget-content" cellpadding="5">
    <thead>
        <tr class="ui-widget-header ">
            <th>Data</th>
            <th>Semana</th>
            <th>Hora</th>
            <?php
            if ($idcarro == 0) {
                foreach ($lstCarros as $carros) {
                    echo "<th>Instrutor</th>";
                    echo "<th>".$carros["carro"]."</th>";    
                    echo '<th>Comentário</th>';
                    echo '<th>Falta</th>';
                    echo '<th>Bloq.</th>';
                    echo '<th>Valid.</th>';
                }
            } else {
                echo "<th>Instrutor</th>";
                echo '<th>Aluno</th>';
                echo '<th>Comentário</th>';
                echo '<th>Falta</th>';
                echo '<th>Bloq.</th>';
                echo '<th>Valid.</th>';
            }
            ?> 
        </tr>
    </thead>
    <tbody>
        <?php
            $cor = '';
            $idaulapratica = 0;
            $ImprimirLinha = true;
            $datacount = new DateTime(date_to_db($datai));
            $datacond  = new DateTime(date_to_db($dataf));
            while ($datacount <= $datacond) {

                $ImprimirLinha = true;
                $vDiaSemana = diasemana($datacount->format('d/m/Y'));

                if ($semana1 == 'N' and $vDiaSemana == 1) {
                    $ImprimirLinha = false;
                }
                if ($semana2 == 'N' and $vDiaSemana == 2) {
                    $ImprimirLinha = false;
                }
                if ($semana3 == 'N' and $vDiaSemana == 3) {
                    $ImprimirLinha = false;
                }
                if ($semana4 == 'N' and $vDiaSemana == 4) {
                    $ImprimirLinha = false;
                }
                if ($semana5 == 'N' and $vDiaSemana == 5) {
                    $ImprimirLinha = false;
                }
                if ($semana6 == 'N' and $vDiaSemana == 6) {
                    $ImprimirLinha = false;
                }
                if ($semana7 == 'N' and $vDiaSemana == 7) {
                    $ImprimirLinha = false;
                }

                $vDiaDoMes = null;
                if ($dias == 'par') {
                    $vDiaDoMes = explode("/", $datacount->format('d/m/Y'));
                    if ($vDiaDoMes[0] % 2) {
                        $ImprimirLinha = false;
                    }
                } else if ($dias == 'impar') {
                    $vDiaDoMes = explode("/", $datacount->format('d/m/Y'));
                    if (!($vDiaDoMes[0] % 2)) {
                        $ImprimirLinha = false;
                    }
                }

                if ($ImprimirLinha) {
                    if (isset ($horai[diasemana($datacount->format('d/m/Y'))])) {
                        $howarr = explode(':', $horai[diasemana($datacount->format('d/m/Y'))]);
                        $horacount = mktime($howarr[0], $howarr[1], 0, 0, 0, 0);
                        $howarr = explode(':', $horaf[diasemana($datacount->format('d/m/Y'))]);
                        $horacond  = mktime($howarr[0], $howarr[1], 0, 0, 0, 0);                        
                        while ($horacount < $horacond) {

                            $ImprimirHora = true;
                            if ($exibir == "marcadas" || $exibir == "naomarcadas") {
                                $whereExibirHoraIdCarros = "";
                                $countExibirHoraCarros = 0;
                                foreach ($lstCarros as $carros) {
                                    if ($whereExibirHoraIdCarros == "") {
                                        $whereExibirHoraIdCarros = $carros["id"];
                                    } else {
                                        $whereExibirHoraIdCarros .= ", ".$carros["id"];
                                    }
                                    $countExibirHoraCarros++;
                                }

                                $totalAExibir = $mysql->getValue(
                                        "count(a.id) as total",
                                        "total",
                                        "aulaspraticas a",
                                        "a.data = '".$datacount->format('Y-m-d')."' and a.hora = '".date('H:i', $horacount)."' ".
                                            "and a.idcarro in (".$whereExibirHoraIdCarros.")");

                                if ($exibir == "marcadas" && $totalAExibir == 0) {
                                    $ImprimirHora = false;
                                }
                                if ($exibir == "naomarcadas" && $totalAExibir >= $countExibirHoraCarros) {
                                    $ImprimirHora = false;
                                }
                            }

                            if ($ImprimirHora == true and $horacount >= $vTimeI and $horacount <= $vTimeF) {
                                echo '<tr '.$cor.'>';
                                echo "<td>".$datacount->format('d/m/Y').'</td>';
                                echo '<td>'.diasemanaextenso($datacount->format('d/m/Y')).'</td>';
                                echo '<td>'.date('H:i', $horacount).'</td>';                                

                                foreach ($lstCarros as $carros) {

                                    $nomeInstrutor = getNomeInstrutor($lstFunCarro, $carros["id"], new DateTime($datacount->format('Y-m-d')." ".date('H:i', $horacount)));
                                    echo '<td><a href="#" id="instrutorNome">';
                                    if ($nomeInstrutor == "") {
                                        echo 'Instrutor n�o definido';
                                    } else {
                                        echo $nomeInstrutor;
                                    }
                                    echo '</a>';
                                    echo '<input type="hidden" value="dvInstrutor'.$countDivInstrutor.'" />';
                                    echo '<input type="hidden" value="'.$carros["id"].'" />';
                                    echo '<input type="hidden" value="'.$datacount->format('d/m/Y').'" />';
                                    echo '<input type="hidden" value="'.date('H:i', $horacount).'" />';
                                    echo '<div id="dvInstrutor'.$countDivInstrutor.'"></div>';
                                    echo '</td>';
                                    $countDivInstrutor++;

                                    /* Adicionar Remover Aluno */
                                    echo '<td align="center">';
                                    $where = "a.data = '".$datacount->format('Y-m-d')."' and a.hora = '".date('H:i', $horacount)."' and a.idcarro = '".$carros["id"]."'";
                                    $where .= " and a.idaluno = b.id and b.idpessoa = c.id ";
                                    $rowsItensAulas = $mysql->select(
                                                            'a.id, c.nome, a.comentario, a.falta, a.validado', 
                                                            'aulaspraticas a, alunos b, pessoas c', 
                                                            $where);
                                    $comentario = '';
                                    $idaulapratica = 0;
                                    $idbloqueio = 0;
                                    $falta = 'N';
                                    $bloqueio = 'N';
                                    $validado = 'N';
                                    if (is_array($rowsItensAulas)) {
                                        foreach($rowsItensAulas as $itensAulas) {
                                            echo '<a href="#" id="bAlunoToAulasPraticas" class="aulapraticaexc">'.$itensAulas["nome"].'</a>';
                                            echo '<input type="hidden" value="'.$itensAulas["id"].'" />';
                                            $comentario = $itensAulas["comentario"];
                                            $idaulapratica = $itensAulas["id"];
                                            $falta = $itensAulas["falta"];
                                            $validado = $itensAulas["validado"];                                            
                                        }
                                    } else {
                                        $whereBloqueio = "a.data = '".$datacount->format('Y-m-d')."' and a.hora = '".date('H:i', $horacount)."' and a.idcarro = '".$carros["id"]."'";
                                        $rowsBloqueio = $mysql->select('a.id, a.motivo', 'aulaspraticasbloqueio a', $whereBloqueio);

                                        if (is_array($rowsBloqueio)) {
                                            $bloqueio = 'S';
                                            foreach ($rowsBloqueio as $rowBloq) {
                                                $idbloqueio = $rowBloq["id"];
                                                $comentario = $rowBloq["motivo"];
                                            }
                                            echo '<a class="aulapraticabloq" href="#" id="bAlunoToAulasPraticas">BLOQUEADO</a>';
                                            echo '<input type="hidden" value="0" />';
                                        } else {
                                            echo '<a class="aulapraticaadd" href="#" id="bAlunoToAulasPraticas">Adicionar Aluno</a>';
                                            echo '<input type="hidden" value="0" />';
                                        }
                                    }
                                    echo '<input type="hidden" value="'.$datacount->format('d/m/Y').'" />';
                                    echo '<input type="hidden" value="'.date('H:i', $horacount).'" />';
                                    echo '<input type="hidden" value="'.$carros["id"].'" />';
                                    echo '</td><td>';
                                    echo '<input type="hidden" id="idbloqueio" value="'.$idbloqueio.'" />';
                                    echo '<input type="hidden" id="idaulapratica" value="'.$idaulapratica.'" />';
                                    echo '<textarea id="txtAPComentario"';
                                    if ($idaulapratica == 0 && $bloqueio == 'N') {
                                        echo ' disabled ';
                                    }
                                    echo '>'.$comentario.'</textarea></td>';
                                    echo '<td><input type="checkbox" id="chckAPFalta" value="'.$idaulapratica.'" ';
                                    if ($idaulapratica == 0) {
                                        echo ' disabled ';
                                    }
                                    if ($falta == "S") {
                                        echo ' checked ';
                                    }
                                    echo '/></td>';
                                    echo '<td><input type="checkbox" id="chckAPBloqueio" ';
                                    if ($idaulapratica > 0) {
                                        echo ' disabled ';
                                    }
                                    if ($bloqueio == "S") {
                                        echo ' checked ';
                                    }
                                    echo '/>';                                    
                                    echo '<input type="hidden" value="'.$datacount->format('d/m/Y').'" />';
                                    echo '<input type="hidden" value="'.date('H:i', $horacount).'" />';
                                    echo '<input type="hidden" value="'.$carros["id"].'" />';
                                    echo '</td>';                                    
                                    echo '<td><input type="checkbox" id="chckAPValidado" value="'.$idaulapratica.'" ';
                                    if ($hasValidacaoAccess == false || $idaulapratica == 0) {
                                        echo ' disabled ';
                                    }
                                    if ($validado == "S") {
                                        echo ' checked ';
                                    }
                                    echo '/></td>'; 
                                }

                                echo '</tr>';
                            }

                            $horacount = $horacount + (60 * $duracaoaula);
                        }
                        if ($cor == '') {
                            $cor = 'style="background: #D4D4D4"';
                        } else {
                            $cor = '';
                        }
                    }
                }
                
                $datacount->add(new DateInterval('P1D'));
            }
        ?>
    </tbody>
</table>
<script type="text/javascript">
    var objetoLink = null;
    var idLastAultaPratica = 0;
    function linkAlunoAulaPratica() {
        var idAultaPratica = objetoLink.next().val();
        $.post('modulos/aulaspraticas/delete.php', {
            idaulapratica: idAultaPratica
        }, function(data){
            if (data.status == "ok") {
                objetoLink.next().val('0');
                objetoLink.html("Adicionar Aluno");
                objetoLink.removeClass("aulapraticaexc");
                objetoLink.addClass("aulapraticaadd");
                objetoLink.parent().next().children('input').val(0);
                objetoLink.parent().next().children('textarea').val('');
                objetoLink.parent().next().children('textarea').attr('disabled','true');
                objetoLink.parent().next().next().children('input').val('0');
                objetoLink.parent().next().next().children('input').removeAttr("checked");
                objetoLink.parent().next().next().children('input').attr('disabled','true');
                objetoLink.parent().next().next().next().children('#chckAPBloqueio').removeAttr("disabled");
                atualizarPlacar();
            } else {
                divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
            }
        }, "json");
    }    
    $(document).ready(function(){
        $("table#users").delegate("#chckAPFalta", 'click', function(){
            var objetoCheck = $(this);
            var checkSelecionado = 'N';
            if (objetoCheck.attr('checked')) {
                checkSelecionado = 'S';
            }
            $.post('modulos/aulaspraticas/falta.php',
                {
                    idaulapratica: objetoCheck.val(),
                    falta: checkSelecionado
                });
        });
        $("table#users").delegate("#chckAPValidado", 'click', function(){
            var objetoCheck = $(this);
            var checkSelecionado = 'N';
            if (objetoCheck.attr('checked')) {
                checkSelecionado = 'S';
            }
            $.post('modulos/aulaspraticas/validado.php',
                {
                    idaulapratica: objetoCheck.val(),
                    validado: checkSelecionado
                });
        });
        $("table#users").delegate("#chckAPBloqueio", 'click', function(){
            var objetoCheck = $(this);
            if (objetoCheck.attr('checked')) {
                $.post(
                    'modulos/aulaspraticas/addbloqueio.php',
                    {
                        data: objetoCheck.next().val(),
                        hora: objetoCheck.next().next().val(),
                        idcarro: objetoCheck.next().next().next().val()
                    },
                    function(data) {
                        objetoCheck.parent().prev().prev().children('#idbloqueio').val(data.idbloqueio);
                        objetoCheck.parent().prev().prev().children('#idaulapratica').val('0');
                        objetoCheck.parent().prev().prev().children('textarea').removeAttr("disabled");
                        objetoCheck.parent().prev().prev().prev().children('#bAlunoToAulasPraticas').html('BLOQUEADO');
                        objetoCheck.parent().prev().prev().prev().children('#bAlunoToAulasPraticas').removeClass('aulapraticaadd');
                        objetoCheck.parent().prev().prev().prev().children('#bAlunoToAulasPraticas').addClass('aulapraticabloq');
                    },
                    "json");
            } else {
                $.post(
                    'modulos/aulaspraticas/excbloqueio.php',
                    {
                        data: objetoCheck.next().val(),
                        hora: objetoCheck.next().next().val(),
                        idcarro: objetoCheck.next().next().next().val()
                    },
                    function(data) {
                        objetoCheck.parent().prev().prev().children('#idbloqueio').val('0');
                        objetoCheck.parent().prev().prev().children('textarea').val('');
                        objetoCheck.parent().prev().prev().children('textarea').attr("disabled", "true");
                        objetoCheck.parent().prev().prev().prev().children('#bAlunoToAulasPraticas').html('Adicionar Aluno');
                        objetoCheck.parent().prev().prev().prev().children('#bAlunoToAulasPraticas').removeClass('aulapraticabloq');
                        objetoCheck.parent().prev().prev().prev().children('#bAlunoToAulasPraticas').addClass('aulapraticaadd');
                    });
            }
        });
        $("table#users").delegate("a#bAlunoToAulasPraticas", "click", function(event){
            objetoLink = $(this);
            var idAultaPratica = objetoLink.next().val();
            if (objetoLink.hasClass('aulapraticabloq')) {

            } else if (idAultaPratica == 0) {
                $.post('modulos/aulaspraticas/addaluno.php', {
                    data: objetoLink.next().next().val(),
                    hora: objetoLink.next().next().next().val(),
                    idcarro: objetoLink.next().next().next().next().val(),
                    idaluno: $("#fIdAluno").val()
                }, function(data){
                    if (data.status == "ok") {
                        objetoLink.html(data.msg);
                        objetoLink.next().val(data.idaulapratica);
                        objetoLink.removeClass("aulapraticaadd");
                        objetoLink.addClass("aulapraticaexc");
                        objetoLink.parent().next().children('#idaulapratica').val(data.idaulapratica);
                        objetoLink.parent().next().children('#idbloqueio').val('0');
                        objetoLink.parent().next().children('textarea').removeAttr("disabled");
                        objetoLink.parent().next().next().children('input').val(data.idaulapratica);
                        objetoLink.parent().next().next().children('input').removeAttr("disabled");
                        objetoLink.parent().next().next().next().children('input').attr("disabled", "true");
                        atualizarPlacar();
                    } else {
                        divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', data.msg);
                    }
                }, "json");
            } else {
                if (idLastAultaPratica != idAultaPratica) {
                    idLastAultaPratica = idAultaPratica;
                    dialogModalMsg("<?php echo $form->getdivDialogNameTitle($excDialogId); ?>", "<?php echo $form->getdivDialogNameMsg($excDialogId) ?>", "Aviso", "Deseja realmente remover o aluno da aula?");
                }
            }
            event.preventDefault();
        });
        $("table#users").delegate("#txtAPComentario", 'change', function(event){
            if ($(this).prev().val() > 0) {
                $.post('modulos/aulaspraticas/addcomentario.php', 
                    {
                        idaulapratica: $(this).prev().val(),
                        comentario: $(this).val()
                    }, function(data) {

                    });
            } else if ($(this).prev().prev().val() > 0) {
                $.post('modulos/aulaspraticas/addmotivobloq.php',
                    {
                        idbloqueio : $(this).prev().prev().val(),
                        motivo: $(this).val()
                    }, function(data) {

                    });
            }
            event.preventDefault();
        });
        $("table#users").delegate("#instrutorNome", 'click', function(event){
            var divName = $(this).next().val();
            $.post("modulos/aulaspraticas/trocarinst.php", {
                divName : divName,
                idturno : '<?php echo $idturno; ?>',
                idcarro : $(this).next().next().val(),
                data : $(this).next().next().next().val(),
                hora : $(this).next().next().next().next().val()
            }, function(data){
                $("#"+divName).html(data);
            });
            event.preventDefault();
        });
    });
</script>