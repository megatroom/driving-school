<?php
include_once("../../configuracao.php");

$page  = null;
$sidx  = null;
$sord  = null;

if (isset($_POST['page'])) {
    $page  = $_POST['page'];
}
if (isset($_POST['sidx'])) {
    $sidx  = $_POST['sidx'];
}
if (isset($_POST['sord'])) {
    $sord  = $_POST['sord'];
}

$exibeButtonAlterar = $_POST['btnalterar'];
$exibeButtonExc     = $_POST['btnexcluir'];

$aluno = $_POST['aluno'];
$matricula = $_POST['matricula'];
$matriculacfc = $_POST['matriculacfc'];

if (!isset ($sidx) or $sidx == "") {
    $sidx = '1,2';
}
if (!isset ($sord) or $sord == "") {
    $sord = 'asc';
}

$mysql = new modulos_global_mysql();

$wh = null;
if (isset ($aluno) && $aluno != "") {
    $wh[] = "upper(v.aluno) like '".strtoupper($aluno)."%'";
}
if (isset ($matricula) && $matricula != "") {
    $wh[] = "v.matricula like '".$matricula."%'";
}
if (isset ($matriculacfc) && $matriculacfc != "") {
    $wh[] = "v.matriculacfc like '".$matriculacfc."%'";
}
$where = null;
if (is_array($wh)) {
    $where = join(" and ", $wh);
}

$countTable = $mysql->getValue('count(v.id) as total','total','valunoservico v',$where);
//echo $mysql->getMsgErro();

$limit = 10;
$total_pages = ceil($countTable/$limit);
if (!isset ($page) || $page == "") {
    $page = "1";
} else {
    if ($page > $total_pages) {
        $page = $total_pages;
    }
}
$start = $limit * $page - $limit;

$rows = $mysql->select(
        'v.aluno, v.data, v.id, v.valor, v.valorpago, '.
            'v.valorapagar, v.tiposervico, v.matricula, v.matriculacfc ',
        'valunoservico v',
        $where,
        "order by $sidx $sord LIMIT $start, $limit ");
//echo $mysql->getMsgErro();

$pAttr = null;
$pAttr["align"] = "center";

$tblIndex = new modulos_global_tableGrid('ContasAReceber', 'Contas a Receber', $page, $total_pages);
$tblIndex->openHead();
$tblIndex->openLine();
if ($exibeButtonAlterar == "S") { $tblIndex->newCel('Alt.', $pAttr); }
if ($exibeButtonExc == "S") { $tblIndex->newCel('Exc.', $pAttr); }
$tblIndex->newCel('Data', $pAttr);
$tblIndex->newCel('Matrícula', $pAttr);
$tblIndex->newCel('Matr. CFC', $pAttr);
$tblIndex->newCel('Aluno', $pAttr);
$tblIndex->newCel('Serviço', $pAttr);
$tblIndex->newCel('Valor', $pAttr);
$tblIndex->newCel('Valor Pago', $pAttr);
$tblIndex->newCel('Valor a Pagar', $pAttr);
$tblIndex->closeLine();
$tblIndex->closeHead();

if (is_array($rows)) {
    foreach ($rows as $row) {
        $tblIndex->openLine();
        if ($exibeButtonAlterar == "S") { $tblIndex->newCelEdit($row["id"]); }
        if ($exibeButtonExc == "S") { $tblIndex->newCelExc($row["id"]); }
        $tblIndex->newCel(db_to_date($row["data"]));
        $tblIndex->newCel($row["matricula"]);
        $tblIndex->newCel($row["matriculacfc"]);
        $tblIndex->newCel($row["aluno"]);
        $tblIndex->newCel($row["tiposervico"]);
        $tblIndex->newCel(db_to_float($row["valor"]), array("align" => "right"));
        $tblIndex->newCel(db_to_float($row["valorpago"]), array("align" => "right"));
        $tblIndex->newCel(db_to_float($row["valorapagar"]), array("align" => "right"));
        $tblIndex->closeLine();
    }
}

$tblIndex->close();
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#<?php echo $tblIndex->getFootBtnFirstName(); ?>").click(function(event){
            vPage = 1;
            carregarGrid();
            event.preventDefault();
        });
        $("#<?php echo $tblIndex->getFootBtnBackName(); ?>").click(function(event){
            if (vPage > 1) {
                vPage = vPage - 1;
            }
            carregarGrid();
            event.preventDefault();
        });
        $("#<?php echo $tblIndex->getFootBtnNextkName(); ?>").click(function(event){
            if (vPage < <?php echo $total_pages; ?>) {
                vPage = vPage + 1;
            }
            carregarGrid();
            event.preventDefault();
        });        
        $("#<?php echo $tblIndex->getFootBtnLstName(); ?>").click(function(event){
            vPage = <?php echo $total_pages; ?>;
            carregarGrid();
            event.preventDefault();
        });
        <?php if ($exibeButtonAlterar == "S") { ?>
        $("#<?php echo $tblIndex->getBtnEditName(); ?>").live('click', function(event){
            var pId = $(this).children().val();
            openAjax("modulos/contasareceber/form.php?pId="+pId);
            event.preventDefault();
        });
        <?php } if ($exibeButtonExc == "S") { ?>
        $("#<?php echo $tblIndex->getBtnExcName(); ?>").live('click', function(event){
            var pId = $(this).children().val();
            $.post("modulos/contasareceber/delete.php", { id : pId },
                function(data){
                    postAlert(data.retornoStatus, data.titulo, data.msg, "modulos/contasareceber/index.php","");
                }, "json");
            event.preventDefault();
        });
        <?php } ?>
    });
</script>