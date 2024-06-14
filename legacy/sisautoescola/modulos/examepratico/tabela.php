<?php
include_once("../../configuracao.php");

$idexamepratico = $_POST["idexamepratico"];
$idcarro = $_POST["idcarro"];

$iCount = 0;
$color = 'black';

$mysql = new modulos_global_mysql();

$pIdExamePraticoCarro = $mysql->getValue(
        "id",
        "id",
        "examepraticocarro",
        "idexamepratico = '".$idexamepratico."' and idcarro = '".$idcarro."'");

$queryHorario = $mysql->select("id, hora", 'examepraticohorario', null, null, 'hora');
$horarioOptions = array();
$horarioOptions[] = array(
    'horario' => '',
    'html1' => '<option value="" ',
    'html2' => '></option>'
);
if (is_array($queryHorario)) {
    foreach ($queryHorario as $row) {
        $hora = substr($row['hora'], 0, 5);
        $horarioOptions[] = array(
            'horario' => $row['hora'],
            'html1' => '<option value="'. $hora.'" ',
            'html2' => '>'. $hora.'</option>'
        );
    }
}

//echo $mysql->getMsgErro();

?>
<table>
    <tr>
        <td valign="top">
            <table id="users" class="ui-widget ui-widget-content" cellpadding="5">
                <thead>
                    <tr class="ui-widget-header ">
                        <th>Matrícula</th>
                        <th>Matrícula CFC</th>
                        <th>Nome</th>
                        <th>Horário</th>
                        <th>Resultados</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $mysql = new modulos_global_mysql();

                    $lstAlunos = $mysql->select(
                            "a.id, a.resultado, a.horario, c.nome, b.matricula, b.matriculacfc, c.cpf",
                            "examepraticoalunos a, alunos b, pessoas c",
                            "a.idaluno = b.id and b.idpessoa = c.id and a.idexamepraticocarro = '".$pIdExamePraticoCarro."'",
                            null,
                            "a.id");

                    //echo $mysql->getMsgErro();

                    $iCount = 0;
                    if (is_array($lstAlunos)) {
                        foreach ($lstAlunos as $aluno) {
                            echo '<tr>';
                            echo '<td>'.
                                    '<input type="hidden" value="'.$aluno["id"].'" />'.
                                    '<a href="#" id="selRowAulaTeorica">'.$aluno["matricula"].'</a>'.
                                 '</td>';
                            echo '<td><a href="#" id="selRowAulaTeorica">'.$aluno["matriculacfc"].'</a></td>';
                            echo '<td><a href="#" id="selRowAulaTeorica">'.$aluno["nome"].'</a></td>';
                            echo '<td>';
                            echo addHorario($horarioOptions, $aluno["horario"], $aluno["id"]);
                            echo '</td>';
                            echo '<td>';                          
                            echo addResultado($aluno["resultado"], $aluno["id"]);
                            echo '</td>';
                            echo '</tr>';
                            $iCount++;
                        }
                    }

                    /*
                    if ($iCount == $qtdalunos) {
                        $color = 'red';
                    }
                     */

                ?>
                </tbody>
            </table>
        </td>
        <td valign="top">
            <div class="dContadorAulaTeorica ui-widget ui-widget-content ui-corner-all">
                <div class="dContadorATCabecalho ui-widget-header">Qtd. Alunos</div>
                <div class="dContadorATValor" style="color: <?php echo $color; ?>;"><?php echo $iCount; ?></div>
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        $("select#fResultado").live("click", function() {
            $.post("modulos/examepratico/resultado.php",
                {
                    idepaluno : $(this).attr("idAluno"),
                    resultado : $(this).val()
                },
                function(data) {
                    if (data.retornoStatus == "erro") { alert(data.msg); }
                }, "json");
        });
        $("select#fHorario").live("click", function() {
            $.post("modulos/examepratico/horario.php",
                {
                    idepaluno : $(this).attr("idAluno"),
                    horario : $(this).val()
                },
                function(data) {
                    if (data.retornoStatus == "erro") { alert(data.msg); }
                }, "json");
        });
    });
</script>
<?php
function addHorario($pHorarioOptions, $pHorario, $pIdAluno)
{
    $txt = '<select id="fHorario" idAluno="'.$pIdAluno.'" >';    
    foreach ($pHorarioOptions as $option) {
        $txt .= $option['html1'];
        if ($option['horario'] == $pHorario) {
            $txt .= 'selected';
        }
        $txt .= $option['html2'];
    }
    $txt .= '</select>';
    return $txt;
}
function addResultado($pResultado, $pIdAluno)
{
    $aprovada = "";
    $reprovada = "";
    $naoMarcada = "";
    $naoDefinida = "";
    $falta = "";
    $retirado = "";
    $canceladoAluno = "";
    if ($pResultado == "A")
    {
        $aprovada = "selected";
    }
    elseif ($pResultado == "R")
    {
        $reprovada = "selected";
    }
    elseif ($pResultado == "M")
    {
        $naoMarcada = "selected";
    }
    elseif ($pResultado == "F")
    {
        $falta = "selected";
    }
    elseif ($pResultado == "T")
    {
        $retirado = "selected";
    }
    elseif ($pResultado == "C")
    {
        $canceladoAluno = "selected";
    }
    else
    {
        $naoDefinida = "selected";
    }
    $txt = '<select id="fResultado" idAluno="'.$pIdAluno.'" >';
    $txt .= '<option value="N" '.$naoDefinida.' ></option>';
    $txt .= '<option value="A" '.$aprovada.' >Aprovado</option>';
    $txt .= '<option value="R" '.$reprovada.' >Reprovado</option>';
    $txt .= '<option value="M" '.$naoMarcada.' >Não Marcado</option>';
    $txt .= '<option value="F" '.$falta.' >Falta</option>';
    $txt .= '<option value="T" '.$retirado.' >Retirado</option>';
    $txt .= '<option value="C" '.$canceladoAluno.' >Cancelado Aluno</option>';
    $txt .= '</select>';
    return $txt;
}
?>