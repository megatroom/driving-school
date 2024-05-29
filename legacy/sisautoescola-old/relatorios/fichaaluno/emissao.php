<?php
include_once("../../configuracao.php");

$idaluno = $_GET["idaluno"];
$tipoRel = $_GET["tiporel"];
$opcoes = explode(",", $_GET["opcoes"]);

$args = "";
foreach ($_GET as $key => $value) {
    $val = $value;
    if ($key == "tiporel") {
        $val = "2";
    }
    if ($args == "") {
        $args = "?".$key."=".$val;
    } else {
        $args .= "&".$key."=".$val;
    }
}

$rel = new modulos_global_relatorio($tipoRel, "Ficha do Aluno", $_SERVER["PHP_SELF"] . $args);

$mysql = new modulos_global_mysql();

/*
 * Cabeçalho - Início
 */
$cbcRel = $mysql->select(
        "a.campo, a.valor",
        "sistema a",
        "a.campo = 'reltitulo' or a.campo = 'reldesc'");

$titulo = "";
$cabecalho = "";
if (is_array($cbcRel)) {
    foreach ($cbcRel as $cabecalhoRel) {
        if ($cabecalhoRel["campo"] == "reltitulo") {
            $titulo = $cabecalhoRel["valor"];
        }
        if ($cabecalhoRel["campo"] == "reldesc") {
            $cabecalho = $cabecalhoRel["valor"];
            $cabecalho = str_replace("\n",'<br />', $cabecalho);
        }
    }
}

$rel->openTable('tblCabecalho', $rel->attrCabTable());
$rel->newLine();
$rel->newCel($titulo, $rel->attrCabTitulo());
$rel->closeLine();
$rel->newLine();
$rel->newCel($cabecalho, $rel->attrCabDesc());
$rel->closeLine();
$rel->closeTable();

$rel->hr();
/*
 * Cabeçalho - Fim
 */

$attrTable = null;
$attrTable["border"] = "0";
$attrTable["cellpadding"] = "5px";
$attrTable["style"] = "text-align:left;";

$attrTable2 = null;
$attrTable2["border"] = "1";
$attrTable2["cellpadding"] = "5px";

$rows = $mysql->select(
        'a.id as idaluno, a.*, b.*, c.descricao as origem',
        'alunos a, pessoas b, origens c',
        "a.idpessoa = b.id ".
        "and a.idorigem = c.id ".
        "and a.id = '".$idaluno."'");

if (is_array($rows)) {
    foreach ($rows as $row) {
        $rel->h1($row["nome"]);

        $rel->openTable('tblAluno', $attrTable);
        $rel->newLine();
        $rel->null('<td>');

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Matrícula:', $rel->attrListaTitulo());
            $rel->newCel($row["matricula"]);
            $rel->newCel('Matrícula CFC:', $rel->attrListaTitulo());
            $rel->newCel($row["matriculacfc"]);
            $rel->newCel('Origem:', $rel->attrListaTitulo());
            $rel->newCel($row["origem"]);
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Data de Nascimento:', $rel->attrListaTitulo());
            $rel->newCel(db_to_date($row["dtnascimento"]));
            $rel->newCel('Sexo:', $rel->attrListaTitulo());
            $rel->newCel(aluno_sexo_to_str($row["sexo"]));
            $rel->newCel('CPF:', $rel->attrListaTitulo());
            $rel->newCel($row["cpf"]);
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('RG:', $rel->attrListaTitulo());
            $rel->newCel($row["rg"]);
            $rel->newCel('Orgão Emissor:', $rel->attrListaTitulo());
            $rel->newCel($row["orgaoemissor"]);
            $rel->newCel('Data Emissão:', $rel->attrListaTitulo());
            $rel->newCel(db_to_date($row["rgdataemissao"]));
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Cateira de Trabalho:', $rel->attrListaTitulo());
            $rel->newCel($row["carteiradetrabalho"]);
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Nome do pai:', $rel->attrListaTitulo());
            $rel->newCel($row["pai"]);
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Nome da mãe:', $rel->attrListaTitulo());
            $rel->newCel($row["mae"]);
            $rel->closeLine();
            $rel->closeTable();

        $rel->null("</td><tr><td><hr></td></tr><tr><td>");

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Renach:', $rel->attrListaTitulo());
            $rel->newCel($row["renach"]);
            $rel->newCel('Nº Registro CNH:', $rel->attrListaTitulo());
            $rel->newCel($row["regcnh"]);
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Categoria Atual:', $rel->attrListaTitulo());
            $rel->newCel($row["categoriaatual"]);
            $rel->newCel('Validade do Processo:', $rel->attrListaTitulo());
            $rel->newCel(db_to_date($row["validadeprocesso"]));
            $rel->closeLine();
            $rel->closeTable();

            $dudas = $mysql->select('data, duda', 'alunosdudas', "idaluno = '".$row["idaluno"]."'", null, "data");
            if (is_array($dudas)) {
                $rel->null('</td><tr><td><hr></td></tr><tr><td align="center">');
                $rel->openTable('tblAluno', $attrTable2);
                $rel->newLine();
                $rel->newCelHeader('Data');
                $rel->newCelHeader('Duda');
                $rel->closeLine();
                foreach ($dudas as $duda) {
                    $rel->newLine();
                    $rel->newCel(db_to_date($duda["data"]));
                    $rel->newCel($duda["duda"]);
                    $rel->closeLine();
                }
                $rel->closeTable();
                
            }

        $rel->null("</td><tr><td><hr></td></tr><tr><td>");

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Endereço:', $rel->attrListaTitulo());
            $rel->newCel($row["endereco"]);
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('CEP:', $rel->attrListaTitulo());
            $rel->newCel($row["cep"]);
            $rel->newCel('Bairro:', $rel->attrListaTitulo());
            $rel->newCel($row["bairro"]);
            $rel->newCel('Cidade:', $rel->attrListaTitulo());
            $rel->newCel($row["cidade"]);
            $rel->newCel('UF:', $rel->attrListaTitulo());
            $rel->newCel($row["estado"]);
            $rel->closeLine();
            $rel->closeTable();

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Telefone:', $rel->attrListaTitulo());
            $rel->newCel($row["telefone"]);
            $rel->newCel('Celular:', $rel->attrListaTitulo());
            $rel->newCel($row["celular"]);
            $rel->newCel('E-mail:', $rel->attrListaTitulo());
            $rel->newCel($row["email"]);
            $rel->closeLine();
            $rel->closeTable();

        $rel->null("</td><tr><td><hr></td></tr><tr><td>");

            $rel->openTable('tblAluno', $attrTable);
            $rel->newLine();
            $rel->newCel('Observações', $rel->attrListaTitulo());
            $rel->closeLine();
            $rel->newLine();
            $rel->newCel($row["observacoes"]);
            $rel->closeLine();
            $rel->closeTable();

        $rel->null('</td>');
        $rel->closeTable();
    }
} else {
    echo $mysql->getMsgErro();
}

$rel->close();

?>