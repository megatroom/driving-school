<?php
include_once("../../configuracao.php");

$idtela = $_POST["idtela"];

$diretorio = str_replace("modulos\\icones", "", getcwd()) ."icones";
$pDir = opendir($diretorio);
$lstIconesDir = null;
while ($nome_itens = readdir($pDir)) {
	if (strrpos(strtolower($nome_itens), ".jpg") > 0 or
		strrpos(strtolower($nome_itens), ".gif") > 0 or
		strrpos(strtolower($nome_itens), ".png") > 0) {
		$lstIconesDir[] = $nome_itens;
	}
}

$mysql = new modulos_global_mysql();

$iconeAtual = $mysql->getValue('icone', null, 'telas', "id = '".$idtela."'");

if (isset($iconeAtual) and strlen($iconeAtual) > 0) {
	$iconeAtual = "icones/" . $iconeAtual;
} else {
	$iconeAtual = "images/none.png";
}

$form = new modulos_global_form('frmChangeIcones');

$form->divAlert();

$form->buttonSave("saveIcone");
$form->buttonSave("saveNoIcone", "Deixar sem ícone");
$form->buttonCancel("closeIcone",null,"modulos/icones/form.php");

$form->divClear(1);

$form->startFieldSet('fdChangeIcone');
$form->null('<table border="0" cellpadding="3"><tr><td align="center">');
$form->null('<img width="50px" height="50px" src="'.$iconeAtual.'" />');
$form->null('</td><td>&nbsp;</td><td align="center">');
$form->null('<img width="50px" height="50px" id="iconeNovo" src="'.$iconeAtual.'" />');
$form->null('</td></tr>');
$form->null('<tr><td>Ícone Atual</td><td>&nbsp;</td><td>Ícone Novo</td></tr></table>');
$form->endFieldSet();

$form->startFieldSet('fdIconeDisponivel', 'Escolha o novo ícone: ');
$form->buttonCustom('btnAddIcone', 'Importar ícone', 'ui-icon-image');
$form->buttonCustom('btnExcIcone', 'Excluir ícone', 'ui-icon-trash');
$form->divClear(1);
$iContIcon = 1;
foreach ($lstIconesDir as $iconesDir) {
	$txt  = '<img width="50px" height="50px" style="padding:10px;cursor:pointer;" ';
	$txt .= 'id="iconeDir" src="icones/'.$iconesDir.'" />';
	$form->null($txt);
	if ($iContIcon == 5) {
		$form->null('<br />');
		$iContIcon = 1;
	} else {
		$iContIcon++;
	}
}
$form->endFieldSet();

$form->close();

?>
<script type="text/javascript">
    $(document).ready(function(){

		$("#saveIcone").click(function(event){
			if ($("#iconeNovo").attr("src") == "images/none.png") {
				divAlertCustomBasic('<?php echo $form->getdivAlertName(); ?>', 'Escolha um ícone!');
			} else {
				$.post(
					"modulos/icones/save.php", 
					{ 
						idtela : '<?php echo $idtela; ?>',
						icone : $("#iconeNovo").attr("src")
					},
					function (data) {
						postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/icones/form.php','<?php echo $form->getdivAlertName(); ?>');
					}, "json");
			}
			event.preventDefault;
		});
                $("#saveNoIcone").click(function(event){
                    $.post(
                        "modulos/icones/save.php",
                        {
                                idtela : '<?php echo $idtela; ?>',
                                icone : ''
                        },
                        function (data) {
                                postAlert(data.retornoStatus, data.titulo, data.msg, 'modulos/icones/form.php','<?php echo $form->getdivAlertName(); ?>');
                        }, "json");
                    event.preventDefault;
                });
		$("#iconeDir").live('click', function(){
			$("#iconeNovo").attr("src", $(this).attr("src"));
		});
		$("#btnAddIcone").click(function(){
			novaAbaMenuPrincipalComParametro("modulos/icones/import.php", { idtela : "<?php echo $idtela; ?>" }, "Importar ícone");
			event.preventDefault;
		});
		$("#btnExcIcone").click(function(){			
			novaAbaMenuPrincipalComParametro("modulos/icones/excluir.php", { idtela : "<?php echo $idtela; ?>" }, "Importar ícone");
			event.preventDefault;
		});
	});
</script>