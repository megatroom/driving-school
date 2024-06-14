<?php
ob_start(); session_start(); ob_end_clean();
include_once("configuracao.php");

$mysql = new modulos_global_mysql();

if (!isset($_SESSION["AUTH"])) {
    $_SESSION["AUTH"] = false;
}

if (!($_SESSION["AUTH"] === true)) {
    if ($mysql->dbConfigIsOk()) {
        $_SESSION["AUTH"] = true;
    } else {
        $_SESSION["AUTH"] = false;
        include_once 'setup.php';
        exit;
    }
}

$sistema = null;
$fieldList = $mysql->select('*', 'sistema');
if (is_array($fieldList)) {
    foreach ($fieldList as $fieldArray) {
        $sistema[$fieldArray["campo"]] = $fieldArray["valor"];
    }
}

if (isset ($sistema["tema"]) and strlen($sistema["tema"]) > 0) {
    $theme = $sistema["tema"];
} else {
    $theme = 'cupertino';
}
if (isset ($sistema["janela"]) and strlen($sistema["janela"]) > 0) {
    $window = $sistema["janela"];
} else {
    $window = 2;
}

$login = "";
$authenticated = false;
if (isset ($_SESSION["LOGIN"]) and strlen($_SESSION["LOGIN"]) > 0) {
    $login = $_SESSION["LOGIN"];
    $authenticated = true;
} else {
    include_once('script.php');
    //include_once('backup.php');
}

$lstTelas = null;
if (isset ($login) and $login != "") {
    if (strtolower($login) == 'admin') {
        $lstTelas = $mysql->select(
                'a.id, b.descricao as modulo, a.descricao as tela, a.endereco, a.icone',
                'telas a, modulos b',
                'a.idmodulo = b.id',
                null,
                'b.ordem, a.ordem');
    } else {
        $lstTelas = $mysql->select(
                'distinct id, modulo, tela, icone, endereco',
                'vacessos',
                "idusuario = '".$_SESSION["IDUSUARIO"]."' or padrao = 1",
                null,
                'ordemmodulos, ordemtelas');
    }
}

//if ($lstTelas == null) {
//    echo 'Listagem de telas não encontrada! <br />';
//    echo $mysql->getMsgErro().'<br />';
//}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" /> 
<title>Sistema de Auto-Escola - 4 Rodas</title>
<link type="text/css" rel="stylesheet" href="css/<?php echo $theme ?>/jquery-ui-1.7.2.custom.css" />
<link type="text/css" rel="stylesheet" href="css/default.css" />
<link type="text/css" rel="stylesheet" href="css/global.css" /> 
<link type="text/css" rel="stylesheet" href="css/ui.jqgrid.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/ui.multiselect.css" media="screen"  />
<link type="text/css" rel="stylesheet" href="css/fullcalendar.css" />
<style type="text/css">
html, body {
    width: 100%; height: 100%;
    border: 0; padding: 0; margin: 0;
 }
</style>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui.accordion.js"></script>
<script type="text/javascript" src="js/ui/ui.tabs.js"></script>
<script type="text/javascript" src="js/jquery.layout.js"></script>
<script type="text/javascript" src="js/grid.locale-pt-br.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="js/jquery.tablednd.js"></script>
<script type="text/javascript" src="js/jquery.contextmenu.js"></script>
<script type="text/javascript" src="js/fullcalendar.js"></script>
<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<script type="text/javascript" src="js/gcal.js"></script>
<script type="text/javascript" src="js/jquery.meio.mask.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery-fonteffect-1.0.0.min.js"></script>
<script type="text/javascript" src="modulos/global/global.php"></script>
<script type="text/javascript" src="modulos/global/funcoes.js"></script>
<script type="text/javascript">

    $(function() {
        $("#accordion").accordion({
            icons: {
                    header: "ui-icon-circle-arrow-e",
                    headerSelected: "ui-icon-circle-arrow-s"
            }
        });

<?php
    if ($window == 1) {
?>
        $("#tabs").tabs({ cache: true });
<?php
    } else {

        if ($authenticated) {
?>
        $("#windowTitle").html('Bem Vindo');
        $.post('modulos/index.php', {}, function(data) {
            $("#windowContent").html(data);
        });
<?php
        } else {
?>
        $("#windowTitle").html('Login');
        $.post('modulos/usuarios/login.php', {}, function(data) {
            $("#windowContent").html(data);
        });
<?php
        }
    }
?>

    });

    $(document).ready(function(){

        $("#linkLogo").click(function(event){
            novaAbaMenuPrincipal("0", "modulos/index.php", "Bemvindo");
            event.preventDefault();
        });

        setWidowType("<?php echo $window; ?>");

        <?php

        if ($authenticated) {
            $lastModulo = "";
            foreach($lstTelas as $tela) {
                if ($lastModulo != $tela["modulo"]) {
                    echo '$("#menulink'.$tela["id"].'").click(function(event){ ';
                    echo 'if ($("#menu'.$tela["id"].'").css("display") == "none") {';
                        echo '$(".subMenuTopToolbar").hide(); ';
                        echo '$("#menu'.$tela["id"].'").show(); ';
                    echo '} else {';
                        echo '$(".subMenuTopToolbar").hide(); ';
                    echo '}';
                    echo 'event.preventDefault(); });';
                    $lastModulo = $tela["modulo"];
                }
                echo '$("#submenu'.$tela["id"].'").click(function(event){ ';
                    echo 'novaAbaMenuPrincipal('.$tela["id"].', "'.$tela["endereco"].'", "'.$tela["tela"].'"); ';
                    echo '$(".subMenuTopToolbar").hide(); ';
                echo 'event.preventDefault(); });';

                echo '$("#btnIconTopToolbar'.$tela["id"].'").click(function(event){ ';
                    echo 'novaAbaMenuPrincipal('.$tela["id"].', "'.$tela["endereco"].'", "'.$tela["tela"].'"); ';
                echo 'event.preventDefault(); });';
            }
        }

        ?>

    });    

</script>

</head>
<body>

<?php
if ($authenticated) {
?>

<div id="indexTopo" style="position:absolute; left: 10px; top: 10px; right: 10px; width:auto; height: 90px; padding: 5px;" class="ui-widget-content" >
    <a href="#" id="linkLogo">
        <img src="images/logo.png" width="200px" height="70px" style="border: 0;" />
    </a>
    <div style="position:absolute; left: 220px; top: 10px; right: 10px; width:auto; font-size: 14px;">
        <div class="topToolBar">
            <?php

            $lastModulo = "";
            foreach($lstTelas as $tela) {
                if ($lastModulo != $tela["modulo"]) {
                    if ($lastModulo != "") {
                        echo '</div></div>';
                    }
                    echo '<td><div class="menuTopToolbar">';
                    echo '<a href="#" id="menulink'.$tela["id"].'">'.$tela["modulo"].'</a>';
                    echo '<div id="menu'.$tela["id"].'" class="subMenuTopToolbar ui-widget-content ui-corner-all">';
                    $lastModulo = $tela["modulo"];
                }
                echo '<div class="subMenuTopToolbarLink"><a href="#" id="submenu'.$tela["id"].'">'.$tela["tela"].'</a></div>';
            }
            echo '</div></div>';

            ?>
        </div>
        <div style="clear: both"></div>
        <div>
            <?php
            if (is_array($lstTelas)) {
                foreach ($lstTelas as $tela) {
                    if (isset ($tela["icone"]) and $tela["icone"] != '') {
                        echo '<a class="btnIconTopToolbar" id="btnIconTopToolbar'.$tela["id"].'" href="#">';
                        echo '<img src="icones/'.$tela["icone"].'" alt="'.$tela["tela"].'" title="'.$tela["tela"].'" width="50px" height="50px" style="border: 0;" />';
                        echo '</a>';
                    }
                }
            }
            ?>
        </div>
    </div>
    <div style="text-align: right; font-size: 10pt; margin: 5px;">
        <?php echo $_SESSION["USUARIO_NOME"]; ?>
    </div>
</div>

<?php
    if ($window == 1) {
?>
<div id="tabs" style="position:absolute; left: 238px; right: 20px; min-height: 507px; ">
	<ul>
		<li><a href="modulos/global/index.php">Página Inicial</a></li>
	</ul>
</div>
<?php
    } else {
?>
<div id="windowBox" style="position:absolute; left: 10px; right: 10px; top: 120px; min-height: 507px; width:auto;" class="ui-dialog ui-widget ui-widget-content ui-corner-all">
   <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
      <span id="windowTitle" class="ui-dialog-title">Bem Vindo</span>
   </div>
   <div class="ui-dialog-content ui-widget-content" id="windowContent">
      <p>Carregando...</p>
   </div>
</div>
<?php
    }

} else { // if (isset ($login) and strlen($login) > 0) {

?>
<table width="100%" border="0"><tr><td align="center" valign="middle">
<div id="windowBox" style="width:350px;margin-top: 80px;" class="ui-dialog ui-widget ui-widget-content ui-corner-all">
   <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
      <span id="windowTitle" class="ui-dialog-title">Bem Vindo</span>
   </div>
   <div class="ui-dialog-content ui-widget-content" id="windowContent">
      <p>Carregando...</p>
   </div>
</div>
</td></tr></table>
<?php

}

?>
</body>
</html>