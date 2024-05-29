<?php
include_once("../../configuracao.php");

$tipo = $_GET["pTipo"];

$mysql = new modulos_global_mysql();

$rows = $mysql->select(
        'a.texto',
        'relalunos a',
        "a.tipo = '".$tipo."'",
        null,
        'a.id');

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Sistema de Auto-Escola - 4 Rodas</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<script type="text/javascript" src="../../js/ckeditor/jquery15.js"></script>
<script type="text/javascript" src="../../js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../js/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="../../js/ckeditor/sample.js"></script>
<link href="../../js/ckeditor/sample.css" rel="stylesheet" type="text/css" />
<style id="styles" type="text/css">
    .cke_button_myDataAtual .cke_icon
    {
        display: none !important;
    }
    .cke_button_myDataAtual .cke_label
    {
        display: inline !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
	var config = {
            toolbar:
            [
                ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
                ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],                
                ['Bold', 'Italic', '-', 'Underline','Strike','-','Subscript','Superscript', '-', 'NumberedList', 'BulletedList'],
                '/',
                ['UIColor'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],                
                '/',
                ['Styles','Format','Font','FontSize'],
                ['TextColor','BGColor'],
                '/',
                ['btnDataAtual'],
            ],
            skin : 'office2003'
	};

	$('.jquery_ckeditor').ckeditor(config);
        
        var editor = $('.jquery_ckeditor').ckeditorGet();

        editor.on( 'pluginsLoaded', function( ev )
        {
            editor.addCommand( 'myDataAtual', {
                exec : function( editor )
                {
                    editor.insertText("{dataAtual}");
                },
                editorFocus : true
            });
            editor.ui.addButton( 'btnDataAtual',
            {
                label : 'Data Atual',
                command : 'myDataAtual'
            } );
        });
    });
</script>
</head>
<body>
    <form action="save.php" method="post">
    <input type="submit" value="Salvar" />
    <input type="hidden" value="<?php echo $tipo; ?>" name="tipo" />
    <textarea class="jquery_ckeditor" cols="80" id="txtEditor" name="txtEditor" rows="10">
        <?php
        if (is_array($rows)) {
            foreach ($rows as $row) {
                echo $row["texto"];
            }
        }
        ?>
    </textarea>
    </form>
</body>
</html>