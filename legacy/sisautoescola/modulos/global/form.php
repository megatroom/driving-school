<?php
include_once("../../configuracao.php");

define("CAMINHO", "modulos/");

class modulos_global_form {

    private $resultado;
    private $numColunas;
    private $numCel;
    private $nameDefault;

    // Alert
    private $dvAlertName;

    // divDialog
    private $dvDialogNameTitle;
    private $dvDialogNameMsg;
    private $countDialog;
    private $divDialogButtonIsFirst;

    // Primeiro campo adicinado no form
    private $firstField = null;

    function __construct($pName = null) {
        if (isset ($pName) and strlen($pName) > 0) {
            $this->nameDefault = $pName;
        } else {
            $this->nameDefault = 'Default';
        }
        $this->countDialog = 0;

        $this->resultado = null;

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '(function($){ ';
        $this->resultado[] = '    $(function(){ ';
        $this->resultado[] = '        $(\'input:text\').setMask(); ';
        $this->resultado[] = '    } ';
        $this->resultado[] = '); ';
        $this->resultado[] = '})(jQuery); ';
        $this->resultado[] = '</script>';
    }

    public function radiobutton($pName, $pKeyLabels, $pChecked = null) {
        $txt = '';
        $iCont = 1;
        if (is_array($pKeyLabels)) {
            foreach ($pKeyLabels as $key => $value) {
                $txt = '';
                $txt .= '<label>';
                $txt .= '<input type="radio" name="'.$pName.'" id="'.$pName.'" ';
                $txt .= 'value="'.$key.'" ';
                if (isset ($pChecked) and strlen($pChecked) > 0) {
                    if ($pChecked == $key) {
                        $txt .= 'checked ';
                    }
                }
                $txt .= '>';
                $txt .= $value .'</label>';
                $this->resultado[] = $txt;
                $iCont++;
            }
        }
    }

    public function checkbox($pName, $pLabel, $pValue = null, $checked = false, $pFloatLeft = false) {

        $txt = '';

        $txt .= '<table border="0"';

        if ($pFloatLeft == true) {
            $txt .= ' style="float:left;">';
        } else {
            $txt .= '>';
        }

        $txt .= '<tr><td>';

        $txt .=  '<label class="globalLabel">';

        $txt .= '<input type="checkbox" ';
        $txt .= 'id="'.$pName.'" ';
        $txt .= 'name="'.$pName.'" ';
        $txt .= 'value="'.$pValue.'" ';

        if ($checked) {
            $txt .= 'checked ';
        }

        $txt .= 'style="float:left;" />';

        $txt .= "&nbsp;". $pLabel .'</label>';

        $txt .= '</td></tr></table>';

        $this->resultado[] = $txt; 
    }

    private function input($pType, $pName, $pLabel, $pValue = null, $pMaxLength = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {

        $txt = '';

        $txt .= '<div id="dv'. $pName .'" style="padding-right:10px;';
        if (isset($pFloatLeft) and $pFloatLeft == true) {
            $txt .= 'float:left;';
        }
        $txt .= '" >';

        $txt .=  '<label class="globalLabel" for="'. $pName .'">'. $pLabel .'</label>';
        
        $txt .= '<input class="globalInput" ';

        if ($pType == "password") {
            $txt .= 'type = "password" ';
        } else {
            $txt .= 'type = "text" ';
        }

        $txt .= 'name = "'. $pName .'" ';
        $txt .= 'id = "'. $pName .'" ';

        if ($pType != 'password' and $pType != 'text') {
            $txt .= 'alt="'.$pType.'" ';
        }

        if (isset($pValue)) {
            $txt .= 'value = "'. $pValue .'" ';
        }
        
        if (isset($pMaxLength)) {
            $txt .= 'maxlength = "'. $pMaxLength .'" ';
        }

        $txt .= 'style = "';
        if (isset ($pWidth[0])) {
            $txt .= 'width:'.$pWidth.';';
        } else {
            $txt .= 'width:140px;';
        }
        if (isset($pStyle)) {
            $txt .= $pStyle;
        }
        $txt .= '" ';

        if (isset($pOther)) {
            $txt .= $pOther;
        }

        $txt .= '> ';

        $txt .= '</div>';
        
        $this->resultado[] = $txt;        

        if ($this->firstField == null and 
                strripos($pOther, "disabled") === false and
                strripos($pOther, "readonly") === false and
                $pType != "date") {
            $this->firstField = $pName;
        }
    }

    public function buttonAdicionar($pName, $pCaption = null, $pHidden = null) {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Adicionar';
        }

        $this->button($pName, $pCaption, 'ui-icon-plus', $pHidden, 'fg-button-red');
    }

    public function buttonCustom($pName, $pCaption, $pIcon, $pHidden = null, $pClass = null) {
        $this->button($pName, $pCaption, $pIcon, $pHidden, $pClass);
    }

    private function button($pName, $pCaption, $pIcon, $pHidden = null, $pClass = null) {
        $txt = '<a id="'. $pName .'" class="fg-button ui-state-default ui-corner-all';
        if (isset ($pCaption) and strlen($pCaption) > 0) {
            $txt .= ' fg-button-icon-left';
        } else {
            $txt .= ' fg-button-icon-solo';
        }
        if (isset ($pClass) and strlen($pClass) > 0) {
            $txt .= ' '.$pClass;
        }
        $txt .= '" href="#" style="';
        if (isset ($pHidden) and $pHidden == true) {
            $txt .= 'display: none;';
        }
        $txt .= '" >';
        $this->resultado[] = $txt;
	$this->resultado[] = '<span id="icon'. $pName .'" class="ui-icon '. $pIcon .'"/>';

        if (isset ($pCaption) and strlen($pCaption) > 0) {
            $this->resultado[] = $pCaption;
        }
        
        $this->resultado[] = '</a>';
    }

    private function scriptHiddenFieldSet($pName) {
       
        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$(document).ready(function(){';
        $this->resultado[] = '    $("#aLegendFieldSet_'. $pName .'").click(function(event){';        
        $this->resultado[] = '        $("#dvFieldSet_'. $pName .'").toggle("slow");';
        $this->resultado[] = '        event.preventDefault();';
        $this->resultado[] = '    });';
        $this->resultado[] = '});';
        $this->resultado[] = '</script>';
        
    }

    public function startFieldSet($pName, $pLegend = null, $pIsHidden = null, $pHidden = null) {

        if (isset($pIsHidden)) {
            if (!isset($pHidden)) {
                $pHidden = false;
            }
            $this->scriptHiddenFieldSet($pName, $pHidden);
        }

        //$this->numColunas = $pColunas;
        $this->numCel = 0;

        $this->resultado[] = '<fieldset id="'. $pName .'" class="ui-state-default ui-corner-all">';

        if (isset($pLegend)) {
            $this->resultado[] = '<legend>';

            if (isset($pIsHidden)) {
                $this->resultado[] = '<a id="aLegendFieldSet_'. $pName .'" href="#">';
            }

            $this->resultado[] = $pLegend;

            if (isset($pIsHidden)) {
                $this->resultado[] = '</a>';
            }

            $this->resultado[] = '</legend>';
        }

        
        $this->resultado[] = '<div id="dvFieldSet_'. $pName .'" ';
        if ($pHidden) {
           $this->resultado[] = ' style="display:none" ';
        }
        $this->resultado[] = '>';
        

        //$this->resultado[] = '<table id="tblFieldSet_'. $pName .'" width="100%" >';
    }

    public function endFieldSet() {
        //$this->resultado[] = '</table>';
        $this->resultado[] = '</div>';
        $this->resultado[] = '</fieldset>';
        $this->resultado[] = '<br clear="all">';
    }

    public function getdivAlertName() {
        return $this->dvAlertName;
    }

    public function divClear($pAddBr = 0) {
        $this->resultado[] = '<div style="clear:both;"></div>';
        if ($pAddBr > 0) {
            $txtBr = "";
            for ($i = 0; $i < $pAddBr; $i++) {
                $txtBr .= '<br />';
            }
            $this->resultado[] = $txtBr;
        }
    }

    public function divAlert() {

        $this->dvAlertName = 'dAlert'. $this->nameDefault;

        $this->resultado[] = '<div id="'.$this->dvAlertName.'" class="ui-state-highlight ui-corner-all" style="display:none;padding: 7px; margin-top: 7px;margin-bottom: 7px;">';
        $this->resultado[] = '</div>';

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$icon_info = \'<span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"/>\'';
        $this->resultado[] = '$icon_alert = \'<span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"/>\'';
        $this->resultado[] = '</script>';

    }

    public function divAlertFlying() {

        $this->dvAlertName = 'dAlert'. $this->nameDefault;

        $this->resultado[] = '<div id="'.$this->dvAlertName.'" class="ui-state-highlight ui-corner-all" style="display:none;padding: 10px; position: fixed;top: 40%;left: 40%;">';
        $this->resultado[] = '</div>';

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$icon_info = \'<span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;margin-right:10px;"/>\'';
        $this->resultado[] = '$icon_alert = \'<span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;margin-right:10px;"/>\'';
        $this->resultado[] = '</script>';

    }

    public function getdivDialogNameTitle($pId) {
        return $this->dvDialogNameTitle[$pId];
    }

    public function getdivDialogNameMsg($pId) {
        return $this->dvDialogNameMsg[$pId];
    }

    public function divDialogOpen() {

        $Id = $this->countDialog;
        $this->countDialog++;

        $this->dvDialogNameTitle[$Id] = 'dDialogTitle'. $this->nameDefault;
        $this->dvDialogNameMsg[$Id]  = 'dDialogMsg'. $this->nameDefault;

        $this->resultado[] = '<div id="'.$this->dvDialogNameTitle[$Id].'" title="">';
	$this->resultado[] = '<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><div id="'.$this->dvDialogNameMsg[$Id].'">&nbsp;</div></p>';
        $this->resultado[] = '</div>';

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = "$(function() { ";
        $this->resultado[] = "    $(\"#".$this->dvDialogNameTitle[$Id]."\").dialog({ ";
        $this->resultado[] = "            bgiframe: true, ";
        $this->resultado[] = "            autoOpen: false, ";
        $this->resultado[] = "            resizable: false, ";
        $this->resultado[] = "            height:140, ";
        $this->resultado[] = "            modal: true, ";
        $this->resultado[] = "            overlay: { ";
        $this->resultado[] = "                    backgroundColor: '#000', ";
        $this->resultado[] = "                    opacity: 0.5 ";
        $this->resultado[] = "            }, ";
        $this->resultado[] = "            buttons: { ";

        $this->divDialogButtonIsFirst = true;
        return $Id;
    }
    public function divDialogAddButton($pCaption, $pFunction) {
        if (!$this->divDialogButtonIsFirst){
            $this->resultado[] = "                    , ";
        }
        $this->resultado[] = "                    '".$pCaption."': function() { ";
        foreach ($pFunction as $value) {
            $this->resultado[] = "                            ".$value." ";
        }
        $this->resultado[] = "                    } ";

        $this->divDialogButtonIsFirst = false;
    }
    public function divDialogClose() {
        $this->resultado[] = "            } ";
        $this->resultado[] = "    }); ";
        $this->resultado[] = "}); ";
        $this->resultado[] = '</script>';
    }

    private function select($pName, $pLabel, $pFloatLeft = false, $pList = null, $pSelected = null, $pWidth = null, $pMultiple = false, $pRows = null, $pOther = null) {

        $txt = '';

        $txt .= '<div id="dv'. $pName .'" style="padding-right:10px;';
        if (isset($pFloatLeft) and $pFloatLeft == true) {
            $txt .= 'float:left;';
        }
        $txt .= '" >';

        if (isset ($pLabel)) {
            $txt .=  '<label class="globalLabel" for="'. $pName .'">'. $pLabel .'</label>';
        }
        
        $txt .= '<select id="'. $pName .'" name="'. $pName .'" ';

        if (isset ($pOther)) {
            $txt .= ' '.$pOther.' ';
        }

        if (isset ($pMultiple) and $pMultiple == true) {
            $txt .= 'multiple ';
            if (isset ($pRows) and is_numeric($pRows)) {
                $txt .= 'size="'.$pRows.'" ';
            } else {
                $txt .= 'size="5" ';
            }
        }

        $txt .= 'style="';
        if (isset ($pWidth)) {
            $txt .= 'width:'.$pWidth.';';
        }
        $txt .= '">';

        if (isset($pList) and is_array($pList)) {
            foreach ($pList as $key => $value) {
                $txt .= '<option value="'.$key.'" ';
                if (isset ($pSelected)) {
                    if (is_array($pSelected)) {
                        foreach ($pSelected as $vSelected) {
                            if ($vSelected == $key) {
                                $txt .= ' selected ';
                            }
                        }
                    } else {
                        if ($pSelected == $key) {
                            $txt .= ' selected ';
                        }
                    }
                }
                $txt .= '>'.$value.'</option>';
            }
        }

        $txt .= '</select>';

        $txt .= '</div>';

        $this->resultado[] = $txt;
    }

    public function selectFixed($pName, $pLabel, $pFloatLeft = false, $pList = null, $pSelected = null, $pWidth = null, $pOther = null) {
        $this->select($pName, $pLabel, $pFloatLeft, $pList, $pSelected, $pWidth, false, null, $pOther);
    }

    public function selectMultiple($pName, $pLabel, $pFloatLeft = false, $pList = null, $pSelected = null, $pWidth = null, $pRows = null, $pOther = null) {
        $this->select($pName, $pLabel, $pFloatLeft, $pList, $pSelected, $pWidth, true, $pRows, $pOther);
    }
    public function inputHidden($pName, $value = null) {
        $this->resultado[] = '<input type="hidden" id="'.$pName.'" name="'.$pName.'" value="'.$value.'" />';
    }

    public function inputTextStaticLookUp($pTypeCns, $pNameInput, $pNameIdInput, $pLabel, $pNameButton, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null, $pId = null) {

        $this->inputHidden($pNameIdInput);

        if (!isset ($pWidth)) {
            $pWidth = "420px";
        }

        $this->null('<table cellpadding="0" cellspacing="0" border="0" ');
        if (isset ($pFloatLeft) and $pFloatLeft == true) {
            $this->null('style="float:left"');
        }
        $this->null('><tr><td>');
        $this->inputTextStatic($pNameInput, $pLabel, $pValue, true, $pWidth, $pStyle, $pOther);
        $this->null('</td><td valign="bottom">');
        $this->button($pNameButton, '', 'ui-icon-search');
        $this->null('</td><td valign="bottom">');
        $this->button($pNameButton.'Exc', '', 'ui-icon-trash');
        $this->null('</td><tr></table>');
       
        if (strtolower($pTypeCns) == "clientes") {
            $objeto = new modulos_clientes_cliente();
        } else if (strtolower($pTypeCns) == "funcionarios") {
            $objeto = new modulos_funcionarios_funcionario();
        } else if (strtolower($pTypeCns) == "servicos") {
            $objeto = new modulos_servicos_servico();
        } else if (strtolower($pTypeCns) == "funcionariosservicos") {
            $objeto = new modulos_funcionariosservicos_funcionarioservico();
        } else if (strtolower($pTypeCns) == "atendimentos") {
            $objeto = new modulos_atendimentos_atendimento();
        } else if (strtolower($pTypeCns) == "carrosfun") {
            $objeto = new modulos_carrosfun_carrofun();
        } else if (strtolower($pTypeCns) == "alunos") {
            $objeto = new modulos_alunos_aluno();
        } else if (strtolower($pTypeCns) == "carros") {
            $objeto = new modulos_carros_carro();
        }

        if (isset ($pId) and is_numeric($pId) and $pId > 0) {
            $objeto->findById($pId);
            $vId = $objeto->getId();
            if (strtolower($pTypeCns) == "funcionarios") {
                $pDesc = $objeto->getNome();
            } else if (strtolower($pTypeCns) == "alunos") {
                $pDesc = $objeto->getNome();
            }
        } else {
            $vId = '';
            $pDesc = '';
        }

        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#<?php echo $pNameButton.'Exc'; ?>").click(function(event){
                    $("#<?php echo $pNameIdInput; ?>").val('');
                    $("#<?php echo $pNameInput; ?>").val('');
                    event.preventDefault();
                });
                $("#<?php echo $pNameIdInput; ?>").val('<?php echo $vId; ?>');
                $("#<?php echo $pNameInput; ?>").val('<?php echo $pDesc; ?>');
            });
        </script>
        <?php

        $this->resultado = array_merge($this->resultado, $objeto->formConsulta('cns'.$pNameInput, $pNameButton, $pNameIdInput, $pNameInput));        

    }

    public function inputTextStatic($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('text', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, 'border-style:groove;background-color:transparent;'.$pStyle, 'readonly '.$pOther);
    }

    public function inputText($pName, $pLabel, $pValue = null, $pMaxLength = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('text', $pName, $pLabel, $pValue, $pMaxLength, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputRenach($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('renach', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputCPF($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('cpf', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputPlaca($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('placa', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputAno($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('ano', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputPassword($pName, $pLabel, $pValue = null, $pMaxLength = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('password', $pName, $pLabel, $pValue, $pMaxLength, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputDecimal($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('signed-decimal', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputRefer($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('refer', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }
    
    public function inputInteiro($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $this->input('integer', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputTime($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null, $pYearRange = null) {
        $this->input('time', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputPhone($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null, $pYearRange = null) {
        $this->input('phone', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }
    
    public function inputCelPhone($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null, $pYearRange = null) {
        $this->input('celphone', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);
    }

    public function inputDate($pName, $pLabel, $pValue = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null, $pYearRange = null) {
        $this->input('date', $pName, $pLabel, $pValue, null, $pFloatLeft, $pWidth, $pStyle, $pOther);

        if (!isset ($pYearRange) or strlen($pYearRange) == 0) {
            $vYearRange = '2000:2050';
        } else {
            $vYearRange = $pYearRange;
        }

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$(function() {';
        $this->resultado[] = '	$("#'. $pName .'").datepicker({';
        $this->resultado[] = "                          dateFormat: 'dd/mm/yy',";
        $this->resultado[] = '                          showButtonPanel: true,';
        $this->resultado[] = '                          changeMonth: true,';
        $this->resultado[] = '                          changeYear: true,';
        $this->resultado[] = "                          yearRange: '".$vYearRange."',";
        $this->resultado[] = "                          monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],";
        $this->resultado[] = "                          monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],";
        $this->resultado[] = "                          dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],";
        $this->resultado[] = "                          dayNamesMin: ['Do', 'Se', 'Te', 'Qua', 'Qui', 'Se', 'Sa'],";
        $this->resultado[] = "                          currentText: 'Hoje'";
        $this->resultado[] = '                          });';
        $this->resultado[] = '});';
        $this->resultado[] = '</script>';

    }

    public function textAreaEditor($pName, $pValue = null) {
        $txt = '';

        $txt .= '<div id="dvTxtArea'. $pName .'" style="padding-right:10px;';
        $txt .= 'float:left;';        
        $txt .= '" >';

        $txt .= '<textarea ';

        $txt .= 'name = "'. $pName .'" ';
        $txt .= 'id = "'. $pName .'" ';

        $txt .= '>';
        $txt .= $pValue;
        $txt .= '</textarea>';

        $txt .= '</div>';

        $this->resultado[] = $txt;
    }

    public function textArea($pName, $pLabel, $pValue = null, $pMaxLength = null, $pFloatLeft = false, $pWidth = null, $pStyle = null, $pOther = null) {
        $txt = '';

        $txt .= '<div id="dvTxtArea'. $pName .'" style="padding-right:10px;';
        if (isset($pFloatLeft) and $pFloatLeft == true) {
            $txt .= 'float:left;';
        }
        $txt .= '" >';

        $txt .=  '<label class="globalLabel" for="'. $pName .'">'. $pLabel .'</label>';

        $txt .= '<textarea class="globalTextArea" ';

        $txt .= 'name = "'. $pName .'" ';
        $txt .= 'id = "'. $pName .'" ';

//        if (isset($pValue)) {
//            $txt .= 'value = "'. $pValue .'" ';
//        }

        $txt .= 'style = "';
        if (isset ($pWidth)) {
            $txt .= 'width:'.$pWidth.';';
        }
        if (isset($pStyle)) {
            $txt .= $pStyle;
        }
        $txt .= '" ';

        if (isset($pOther)) {
            $txt .= $pOther;
        }

        $txt .= '>';
        $txt .= $pValue;
        $txt .= '</textarea>';

        $txt .= '</div>';

        $this->resultado[] = $txt;
    }

    public function buttonUploadImage($pName = 'bUploadImage', $pCaption = 'Carregar Imagem', $pUrl = null) {

//        $this->resultado[] = '<script type="text/javascript">';
//        $this->resultado[] = '$(document).ready(function(){';
//        $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
//        $this->resultado[] = '        var $tabs = $(\'#tabs\').tabs();';
//        $this->resultado[] = '        var selected = $tabs.tabs(\'option\', \'selected\');';
//        $this->resultado[] = '        $tabs.tabs(\'url\', selected, \''.$pUrl.'\');';
//        $this->resultado[] = '        $tabs.tabs(\'load\', selected);';
//        $this->resultado[] = '        event.preventDefault();';
//        $this->resultado[] = '    });';
//        $this->resultado[] = '});';
//        $this->resultado[] = '</script>';

        $this->button($pName, $pCaption, "ui-icon-image");
    }

    public function buttonVisualizar($pName = 'bVisualizar', $pCaption = 'Visualizar') {
        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Visualizar';
        }

        $this->button($pName, $pCaption, "ui-icon-search");
    }

    public function buttonImprimir($pName = 'bImprimir', $pCaption = 'Imprimir') {
        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Imprimir';
        }

        $this->button($pName, $pCaption, "ui-icon-print");
    }

    public function buttonExcel($pName = 'bImprimir', $pCaption = 'Gerar Excel') {
        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Gerar Excel';
        }

        $this->button($pName, $pCaption, "ui-icon-note");
    }

    public function buttonWord($pName = 'bImprimir', $pCaption = 'Gerar Word') {
        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Gerar Word';
        }

        $this->button($pName, $pCaption, "ui-icon-note");
    }

    public function buttonSave($pName = 'bSave', $pCaption = 'Salvar') {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Salvar';
        }

        $this->button($pName, $pCaption, "ui-icon-disk", null, 'fg-button-red');
    }

    public function buttonCancel($pName = 'bCancel', $pCaption = 'Cancelar', $pUrl = null) {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Cancelar';
        }

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$(document).ready(function(){';
        $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
        $this->resultado[] = '        openAjax("'.$pUrl.'");';
        $this->resultado[] = '        event.preventDefault();';
        $this->resultado[] = '    });';
        $this->resultado[] = '});';
        $this->resultado[] = '</script>';

        $this->button($pName, $pCaption, "ui-icon-close");
    }

    public function buttonExc($pName = 'bExc', $pCaption = 'Excluir', $pGridName = null, $pIdField = null, $pUrlRetorno = null, $pUrlDelete = null) {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Excluir';
        }

        $excDialogId = $this->divDialogOpen();

        $pDialogNoFunction = null;
        $pDialogNoFunction[] = '$(this).dialog("close");';

        $pDialogYesFunction = null;
        $pDialogYesFunction[] = '$(this).dialog("close");';
        $pDialogYesFunction[] = 'var idSelRow = jQuery("#'.$pGridName.'").jqGrid(\'getGridParam\',\'selrow\'); ';
        $pDialogYesFunction[] = 'if (idSelRow) { ';
        $pDialogYesFunction[] = '    var ret = jQuery("#'.$pGridName.'").jqGrid(\'getRowData\', idSelRow); ';
        $pDialogYesFunction[] = '    $.post("'.$pUrlDelete.'", { id : ret.'.$pIdField.' },';
        $pDialogYesFunction[] = '          function(data){';
        $pDialogYesFunction[] = '              postAlert(data.retornoStatus, data.titulo, data.msg, \''.$pUrlRetorno.'\',\''.$this->getdivAlertName().'\');';
        $pDialogYesFunction[] = '          }, "json");';
        $pDialogYesFunction[] = '} else {';
        $pDialogYesFunction[] = '    divAlertCustomBasic("'.$this->dvAlertName.'","Selecione uma linha.");';
        $pDialogYesFunction[] = '}';

        $this->divDialogAddButton('Não', $pDialogNoFunction);
        $this->divDialogAddButton('Sim', $pDialogYesFunction);
        $this->divDialogClose();

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$(document).ready(function(){';
        $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
        $this->resultado[] = '        var idSelRow = jQuery("#'.$pGridName.'").jqGrid(\'getGridParam\',\'selrow\'); ';
        $this->resultado[] = '        if (idSelRow) { ';
        $this->resultado[] = '            dialogModalMsg("'.$this->dvDialogNameTitle[$excDialogId].'", "'.$this->dvDialogNameMsg[$excDialogId].'", "Aviso", "Deseja realmente excluir o registro?"); ';
        $this->resultado[] = '        } else {';
        $this->resultado[] = '            divAlertCustomBasic("'.$this->dvAlertName.'","Selecione uma linha.");';
        $this->resultado[] = '        }';
        $this->resultado[] = '        event.preventDefault();';
        $this->resultado[] = '    });';
        $this->resultado[] = '});';
        $this->resultado[] = '</script>';
        
        $this->button($pName, $pCaption, "ui-icon-trash");
                
    }

    public function buttonPesquisar($pName = 'bPesq', $pCaption = 'Pesquisar') {
        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Pesquisar';
        }

        $this->button($pName, $pCaption, "ui-icon-search");
    }

    public function buttonAlt($pName = 'bAlt', $pCaption = 'Alterar', $pGridName = null, $pIdField = null, $pUrl = null) {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Alterar';
        }

        if (isset ($pGridName)) {
            $this->resultado[] = '<script type="text/javascript">';
            $this->resultado[] = '$(document).ready(function(){';
            $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
            $this->resultado[] = '        var idSelRow = jQuery("#'.$pGridName.'").jqGrid(\'getGridParam\',\'selrow\'); ';
            $this->resultado[] = '        if (idSelRow) { ';
            $this->resultado[] = '            var ret = jQuery("#'.$pGridName.'").jqGrid(\'getRowData\', idSelRow); ';
            $this->resultado[] = '            openAjax("'.$pUrl.'?pId="+ret.'.$pIdField.');';
            $this->resultado[] = '        } else {';
            $this->resultado[] = '            divAlertCustomBasic("'.$this->dvAlertName.'","Selecione uma linha.");';
            $this->resultado[] = '        }';
            $this->resultado[] = '        event.preventDefault();';
            $this->resultado[] = '    });';
            $this->resultado[] = '});';
            $this->resultado[] = '</script>';
        }

        $this->button($pName, $pCaption, "ui-icon-pencil");
    }

    public function buttonNewChild($pName = 'bNewChild', $pCaption = 'Novo', $pGridName = null, $pIdFieldMaster = null, $pUrl = null, $pMsgNonSelected = null) {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Novo';
        }

        $vMsgNonSelected = "Selecione uma linha.";
        if (isset ($pMsgNonSelected) and strlen($pMsgNonSelected) > 0) {
            $vMsgNonSelected = $pMsgNonSelected;
        }

        if (isset ($pGridName)) {
            $this->resultado[] = '<script type="text/javascript">';
            $this->resultado[] = '$(document).ready(function(){';
            $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
            $this->resultado[] = '        var idSelRow = jQuery("#'.$pGridName.'").jqGrid(\'getGridParam\',\'selrow\'); ';
            $this->resultado[] = '        if (idSelRow) { ';
            $this->resultado[] = '            var ret = jQuery("#'.$pGridName.'").jqGrid(\'getRowData\', idSelRow); ';
            $this->resultado[] = '            openAjax("'.$pUrl.'?pIdMaster="+ret.'.$pIdFieldMaster.');';
            $this->resultado[] = '        } else {';
            $this->resultado[] = '            divAlertCustomBasic("'.$this->dvAlertName.'","'.$vMsgNonSelected.'");';
            $this->resultado[] = '        }';
            $this->resultado[] = '        event.preventDefault();';
            $this->resultado[] = '    });';
            $this->resultado[] = '});';
            $this->resultado[] = '</script>';
        }

        $this->button($pName, $pCaption, "ui-icon-pencil");
    }

    public function buttonNew($pName = 'bNew', $pCaption = 'Novo', $pUrl = null) {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Novo';
        }

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$(document).ready(function(){';
        $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
        $this->resultado[] = '        openAjax("'.$pUrl.'");';
        $this->resultado[] = '        event.preventDefault();';
        $this->resultado[] = '    });';
        $this->resultado[] = '});';
        $this->resultado[] = '</script>';

        $this->button($pName, $pCaption, "ui-icon-plus");
    }

    public function buttonClose($pIdTab = null, $pName = 'bClose', $pCaption = 'Sair') {

        if (!isset ($pCaption) or strlen($pCaption) == 0) {
            $pCaption = 'Sair';
        }

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$(document).ready(function(){';
        $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
        $this->resultado[] = '        fecharAbaMenuPrincipal("'.$pIdTab.'");';
        $this->resultado[] = '        event.preventDefault();';
        $this->resultado[] = '    });';
        $this->resultado[] = '});';
        $this->resultado[] = '</script>';

        $this->button($pName, $pCaption, "ui-icon-close");
    }

    public function buttonToggleMenuTopo($pName = 'bExibirEsconderMenus', $pNameButtonClose = null) {

        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = '$(document).ready(function(){';
        $this->resultado[] = '    $("#'.$pName.'").click(function(event){';
        $this->resultado[] = '        toggleMenuTopo();';
        $this->resultado[] = '        $("#labelButtonMenuTopo1").toggle();';
        $this->resultado[] = '        $("#labelButtonMenuTopo2").toggle();';
        if (isset ($pNameButtonClose[0])) {
            $this->resultado[] = '        $("#'.$pNameButtonClose.'").toggle();';
        }
        $this->resultado[] = '        event.preventDefault();';
        $this->resultado[] = '    });';
        $this->resultado[] = '});';
        $this->resultado[] = '</script>';

        $caption = '<span id="labelButtonMenuTopo1">Esconder Menus</span>';
        $caption .= '<span id="labelButtonMenuTopo2" style="display: none">Exibir Menus</span>';

        $this->button($pName, $caption, "ui-icon-arrow-4-diag");
    }

    function null($pHtml) {
        $this->resultado[] = $pHtml;
    }

    function nullArray($pHtmlArray) {
        foreach ($pHtmlArray as $value) {
            $this->resultado[] = $value;
        }
    }

    function close() {
        if ($this->firstField != null) {
            $this->resultado[] = '<script type="text/javascript"> ';
            $this->resultado[] = '$(document).ready(function(){ ';
            $this->resultado[] = '$("#'.$this->firstField.'").focus(); ';
            $this->resultado[] = '}); ';
            $this->resultado[] = '</script>';
        }
        foreach ($this->resultado as $feResultado) {
            echo $feResultado . "\n";
        }
    }

    function resultForm() {
        return $this->resultado;
    }
   
}
?>