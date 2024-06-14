<?php
include_once("../../configuracao.php");

function getImg($pId, $pTela, $pImg) {
	$txt = '<a id="imgIcones" href="#">';
	$imgStyle = 'style="border:0;"';
	if (isset($pImg) and $pImg != "") {
		$txt .= '<img width="50px" height="50px" '.$imgStyle.' src="icones/'.$pImg.'" /><br />';
	} else {
		$txt .= '<img width="50px" height="50px" '.$imgStyle.' src="images/none.png" /><br />';
	}
	$txt .= $pTela;
	$txt .= '</a>';
	$txt .= '<input type="hidden" value="'.$pId.'" />';
	return $txt;
}

$type_msg = null;
if (isset($_GET["type_msg"])) {
    $type_msg = $_GET["type_msg"];
}

$mysql = new modulos_global_mysql();

$form = new modulos_global_form('frmIcones');

$form->divAlert();

$form->buttonCancel('bCacenlarIcones', 'Sair', 'modulos/index.php');

$form->divClear(1);

$rows = $mysql->select('distinct id, modulo, tela, icone', 'vacessos', null, null, 'ordemmodulos, ordemtelas');

$styleDivConer = "width:100px;margin:10px;text-align:center;";
$styleDiv = $styleDivConer. "float:left;";

$arrSave = null;
$lstModulo = '';
$countLoop = 1;
if (is_array($rows)) {
    foreach ($rows as $row) {
        if ($lstModulo != $row["modulo"]) {
            if ($lstModulo != '') {
                $form->endFieldSet();
            }
            $form->startFieldSet('fModulo'.$row["modulo"], $row["modulo"]);
            $lstModulo = $row["modulo"];
			$countLoop = 1;
        }
		$txtImg = "<div ";
		$txtImg .= 'id = "dvImg'.$row["id"].'" ';
		$txtImg .= 'style="'.$styleDiv.'" ';
		
		$txtImg .= '>'.getImg($row["id"], $row["tela"], $row["icone"]).'</div>';
		$form->null($txtImg);
        //$form->inputText('fTela'.$row["id"], $row["tela"], $row["icone"], "100");
        $arrSave[] = ' icon'.$row["id"] .': $("#fTela'. $row["id"] .'").val()';
		
		if ($countLoop == 4) {
			$form->null('<div style="clear:both"></div>');
			$countLoop = 1;
		} else {
			$countLoop++;
		}		
    }
    $form->endFieldSet();
}

$form->close();

?>
<script type="text/javascript">
<?php
if (isset ($type_msg) and strlen($type_msg) > 0) {
    if ($type_msg == "save" or $type_msg == "delete") {
        echo "postSucess('".$form->getdivAlertName()."','".$type_msg."');";
    }
}
?>
$(document).ready(function(){
    $("#imgIcones").live('click', function(event){
		var pId = $(this).next().val();
		novaAbaMenuPrincipalComParametro("modulos/icones/change.php", { idtela: pId }, "Alteração do ícone");
        event.preventDefault();
    });
});
</script>