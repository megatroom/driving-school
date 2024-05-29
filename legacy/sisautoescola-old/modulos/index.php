<?php
include_once("../configuracao.php");

$mysql = new modulos_global_mysql();

$aulaPraticaDuplicada = $mysql->select(
        'count(a.id) as total', 
        'aulaspraticas a', 
        "data != '0000-00-00' and hora != '00:00:00'", 
        'GROUP BY a.idcarro, a.data, a.hora HAVING count(a.id) > 1');
$apdCount = 0;
if (is_array($aulaPraticaDuplicada)) {
    foreach ($aulaPraticaDuplicada as $apd) {
        $apdCount++;
    }
}

?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#chckConcluirAviso").live('click', function(){
            vId = $(this).val();
            $.post('modulos/avisos/concluir.php', { id: vId }, function (data) {
                showAvisos();
            });
        });
        showAvisos();
        showAgendamentos();
    });
    function showAvisos() {
        $.post('modulos/avisos.php', null, function(data){
            $("#dShowingAvisos").html(data);
        });
        $.post('modulos/avisotot.php', null, function(data){
            $("#topDireitaInfoAviso").html(data);
        });
    }
    function showAgendamentos() {
        $.post('modulos/agendamentos.php', null, function(data){
            $("#dShowingAgendamentos").html(data);
        });
    }
</script>
<?php
if ($apdCount == 1) {
    echo '<h3 style="color:red;">Existe 1 registro duplicado na aula prática!</h3>';
} else if ($apdCount > 1) {
    echo '<h3 style="color:red;">Existem '.$apdCount.' registros duplicados na aula prática!</h3>';
}
?>
<br />
<div id="dShowingAvisos"></div>
<div id="dShowingAgendamentos"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#homeUpload").click(function (event) {
            novaAbaMenuPrincipal(1, 'modulos/aulaspraticas/index.php', 'Aulas Práticas');
            event.preventDefault();
        });
        $("#homeArquivos").click(function (event) {
            novaAbaMenuPrincipal(2, 'modulos/aulasteoricas/index.php', 'Aulas Teóricas');
            event.preventDefault();
        });
        $("#homeCli").click(function (event) {
            novaAbaMenuPrincipal(3, 'modulos/alunos/index.php', 'Alunos');
            event.preventDefault();
        });
    });
</script>