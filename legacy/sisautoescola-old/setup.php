<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Lethus GED</title>
<link type="text/css" rel="stylesheet" href="css/humanity/ui.all.css" />
<link type="text/css" rel="stylesheet" href="css/humanity/jquery-ui-1.7.2.custom.css" />
<link type="text/css" rel="stylesheet" href="css/default.css" />
<link type="text/css" rel="stylesheet" href="css/global.css" />

<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="js/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui.accordion.js"></script>
<script type="text/javascript" src="js/ui/ui.tabs.js"></script>

<style type="text/css">
html, body {
	width: 100%;
	height: 100%;
	border: 0;
	padding: 0;
	margin: 0;
	font-size:12px;
}
h2 { 
	text-align: center; 
	margin: 0; 
}
a:active {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
.principal {
	width:600px;
	padding: 0.5em;
}
.toolbar {
	margin-top:10px;
	margin-bottom:10px;
	margin-left:2px;
	text-align: left;
}
a:link {
	text-decoration: none;
}
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $("#bChave").click(function(){
            $.post("setup.chave.php", { chave: $("#fChave").val() }, function(data) {
                $("#dSetup").html(data);
            });
        });
        $.post("setup.wizard.php", null, function(data) {
                $("#dSetup").html(data);
        });
    });
	
    function carregarBody(pPagina) {
            $.get(pPagina, null, function(data) {
                    $("#dSetup").html(data);
            });
    }
</script>
</head>

<body>
<table width="100%" height="100%">
    <tr>
        <td align="center" valign="middle">
            <div id="dSetup" class="ui-widget-content principal"></div>
        </td>
    </tr>
</table>

</body>
</html>