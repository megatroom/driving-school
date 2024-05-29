<?php
include_once("../../configuracao.php");

$pIdTurma = $_POST["idturma"];
$qtdalunos = $_POST["qtdaluno"];

$countAlunos = 0;
$color = 'black';

?>
<table>
    <tr>
        <td>
            <table id="users" class="ui-widget ui-widget-content" cellpadding="5">
                <thead>
                    <tr class="ui-widget-header ">
                        <th>Matrícula</th>
                        <th>Matrícula CFC</th>
                        <th>Nome</th>
                        <th>CPF</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $mysql = new modulos_global_mysql();

                    $lstAlunos = $mysql->select(
                            "a.id, c.nome, b.matricula, b.matriculacfc, c.cpf",
                            "aulasteoricas a, alunos b, pessoas c",
                            "a.idaluno = b.id and b.idpessoa = c.id and a.idturma = '".$pIdTurma."'",
                            null,
                            "a.id");

                    if (is_array($lstAlunos)) {
                        foreach ($lstAlunos as $aluno) {
                            echo '<tr>';
                            echo '<td>'.
                                    '<input type="hidden" value="'.$aluno["id"].'" />'.
                                    '<a href="#" id="selRowAulaTeorica">'.$aluno["matricula"].'</a>'.
                                 '</td>';
                            echo '<td><a href="#" id="selRowAulaTeorica">'.$aluno["matriculacfc"].'</a></td>';
                            echo '<td><a href="#" id="selRowAulaTeorica">'.$aluno["nome"].'</a></td>';
                            echo '<td><a href="#" id="selRowAulaTeorica">'.$aluno["cpf"].'</a></td>';
                            echo '</tr>';
                            $countAlunos++;
                        }
                    }

                    if ($countAlunos == $qtdalunos) {
                        $color = 'red';
                    }

                ?>
                </tbody>
            </table>
        </td>
        <td valign="top">
            <div class="dContadorAulaTeorica ui-widget ui-widget-content ui-corner-all">
                <div class="dContadorATCabecalho ui-widget-header">Qtd. Alunos</div>
                <div class="dContadorATValor" style="color: <?php echo $color; ?>;"><?php echo $countAlunos; ?></div>
            </div>
        </td>
    </tr>
</table>