<?php
include_once("../../configuracao.php");

$idtela = $_POST["idtela"];

$form = new modulos_global_form('frmAddIcones');

$form->divAlert();

$form->buttonCustom('btnVoltar', 'Voltar', 'ui-icon-close');

$form->divClear(1);

$form->startFieldSet('fdIconeUpload', 'Carregar novo ícone do computador: ');
$form->null('
<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
	<p>Escolha os arquivos para fazer upload:</p>	
	<div class="fieldset flash ui-state-default ui-corner-all" id="fsUploadProgress">
	</div>
	<div id="divStatus">0 Arquivos Carregados</div>
	<br />
	<div>
		<span id="spanButtonPlaceHolder"></span>
		<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
	</div>
</form>
');
$form->endFieldSet();

$form->close();

?>
<link href="css/upload.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/upload/swfupload.js"></script>
<script type="text/javascript" src="js/upload/swfupload.queue.js"></script>
<script type="text/javascript" src="js/upload/fileprogress.js"></script>
<script type="text/javascript" src="js/upload/handlers.js"></script>
<script type="text/javascript">
	var swfu;

    $(document).ready(function(){
	
        var settings = {
            flash_url : "flash/swfupload.swf",
            upload_url: "modulos/icones/upload.php",
            post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
            file_size_limit : "100 MB",
            file_types : "*.jpg;*.gif;*.png",
            file_types_description : "Imagens",
            file_upload_limit : 100,
            file_queue_limit : 0,
            custom_settings : {
                progressTarget : "fsUploadProgress",
                cancelButtonId : "btnCancel"
            },
            debug: false,

            // Button settings
            button_width: "120",
            button_height: "29",
            button_placeholder_id: "spanButtonPlaceHolder",
            button_text: '<span class="">Selecionar Arquivo</span>',
            button_text_style: ".theFont { font-size: 16; }",
            button_text_left_padding: 12,
            button_text_top_padding: 3,
            button_cursor: SWFUpload.CURSOR.HAND,

            // The event handler functions are defined in handlers.js
            file_queued_handler : fileQueued,
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_start_handler : uploadStart,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,
            queue_complete_handler : queueComplete	// Queue plugin event
        };

        swfu = new SWFUpload(settings);
		
		$("#btnVoltar").click(function(){
			novaAbaMenuPrincipalComParametro("modulos/icones/change.php", { idtela : "<?php echo $idtela; ?>" }, "Alteração do ícone");
			event.preventDefault;
		});
	});
</script>