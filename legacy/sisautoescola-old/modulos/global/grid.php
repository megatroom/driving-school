<?php

class modulos_global_grid {

private $resultado = null;
private $gridName  = null;
private $panelName = null;

/**
 *
 * @param <type> $pName
 * @param <type> $pCaption
 * @param <type> $pURL
 * @param <type> $pColNames
 * @param <type> $pColModel
 * @param <type> $pSortName
 * @param <type> $pEventDblClick
 * @param <type> $pHide
 * @param <type> $Type - 0/Null - Normal; 1 - Consulta; 2 - MasterGrid; 3 - Grid Grande
 */
function __construct($pName, $pCaption, $pURL, $pColNames, $pColModel, $pSortName, $pEventDblClick = false, $pHide = false, $Type = null) {

    $this->gridName  = 'tblgrd'. $pName;
    $this->panelName = 'pnlgrd'. $pName;

    $this->resultado[] = '<table id="'.$this->gridName .'"></table>';
    $this->resultado[] = '<div id="'.$this->panelName.'"></div>';

    $this->resultado[] = '<div id="filter" style="margin-left:30%;display:none">Search Invoices</div>';

    $this->resultado[] = '<script type="text/javascript">';
    $this->resultado[] = 'jQuery().ready(function (){ ';
    
    $this->resultado[] = 'var jsGrid'.$this->gridName.' = jQuery("#'.$this->gridName.'").jqGrid({ ';

    $this->resultado[] = '  url:"'.trim($pURL).'", ';
    $this->resultado[] = '  datatype: "xml", ';

    $this->resultado[] = '  colNames:["'. join('","', $pColNames) .'"], ';
    $this->resultado[] = '  colModel:['. join(',', $pColModel) .'], ';

    if (isset ($pHide) and $pHide == true) {
        $this->resultado[] = '  hidegrid: true, ';
    } else {
        $this->resultado[] = '  hidegrid: false, ';
    }

    if (isset ($Type) and $Type == 1) {
        $this->resultado[] = '  height:210, ';
        $this->resultado[] = '  width: 560, ';
        $this->resultado[] = '  rowNum:10, ';
        $this->resultado[] = '  rowList:[10,20,30], ';
    } else if (isset ($Type) and $Type == 3) {
        $this->resultado[] = '  height:300, ';
        $this->resultado[] = '  width: 800, ';
        $this->resultado[] = '  rowNum:13, ';
        $this->resultado[] = '  rowList:[13,20,30], ';
    } else {
        $this->resultado[] = '  height:240, ';
        $this->resultado[] = '  width: 600, ';
        $this->resultado[] = '  rowNum:10, ';
        $this->resultado[] = '  rowList:[10,20,30], ';
    }
    
    $this->resultado[] = '  pager: jQuery(\'#'.$this->panelName.'\'), ';
    $this->resultado[] = '  sortname: "'.$pSortName.'", ';
    $this->resultado[] = '  multiselect: false, ';
    $this->resultado[] = '  viewrecords: true, ';
    $this->resultado[] = '  rownumbers: true, ';
    $this->resultado[] = '  gridview: true, ';
    $this->resultado[] = '  sortorder: "asc", ';    

    if (isset ($pEventDblClick) and $pEventDblClick == true) {
        $this->resultado[] = '  ondblClickRow: function(id){ onDblClick'.$this->gridName.'(id); }, ';
    }

    if (isset ($Type) and $Type == 2) {
        $this->resultado[] = '  onSelectRow: function(ids) { eventOnSelectRowOf'.$this->gridName.'(ids); }, ';
    }

    $this->resultado[] = '  caption:"'.$pCaption.'" ';

    $this->resultado[] = '}).navGrid( ';
    $this->resultado[] = '      \'#'.$this->panelName.'\',{ ';
    $this->resultado[] = '          edit:false, ';
    $this->resultado[] = '          add:false, ';
    $this->resultado[] = '          del:false, ';
    $this->resultado[] = '          search:false, ';
    $this->resultado[] = '          refresh:false ';
    $this->resultado[] = '      }); ';

    if (!isset ($Type) or $Type != 1) { // Consulta
        $this->resultado[] = 'jQuery("#'.$this->gridName .'").jqGrid("navButtonAdd","#'.$this->panelName.'",{';
        $this->resultado[] = '      caption:"Pesquisa",';
        $this->resultado[] = '      title:"Exibe/Esconde o painel de pesquisa",';
        $this->resultado[] = '      buttonicon :"ui-icon-search",';
        $this->resultado[] = '      onClickButton:function(){';
        $this->resultado[] = '          jsGrid'.$this->gridName.'[0].toggleToolbar()';
        $this->resultado[] = '      }';
        $this->resultado[] = '});';
    }

    $this->resultado[] = 'jQuery("#'.$this->gridName .'").jqGrid("navButtonAdd","#'.$this->panelName.'",{';
    $this->resultado[] = '      caption:"Limpar",';
    $this->resultado[] = '      title:"Limpa os campos da pesquisa",';
    $this->resultado[] = '      buttonicon :"ui-icon-arrowreturn-1-w",';
    $this->resultado[] = '      onClickButton:function(){';
    $this->resultado[] = '          jsGrid'.$this->gridName.'[0].clearToolbar()';
    $this->resultado[] = '      }';
    $this->resultado[] = '});';

    $this->resultado[] = 'jQuery("#'.$this->gridName .'").jqGrid("filterToolbar");';

    /*
    if (!isset ($Type) or $Type != 1) { // Consulta
        $this->resultado[] = 'jsGrid'.$this->gridName.'[0].toggleToolbar()';
    }
     */

    $this->resultado[] = '});';
    $this->resultado[] = '</script>';

}

function eventOnDblClickRowAlterRow($pIdField, $pUrl) {
    $this->resultado[] = '<script type="text/javascript">';
    $this->resultado[] = 'function onDblClick'.$this->gridName.'(pId) {';
    $this->resultado[] = '    if (pId) { ';
    $this->resultado[] = '        var ret = jQuery("#'.$this->gridName.'").jqGrid(\'getRowData\', pId); ';
    $this->resultado[] = '        openAjax(\''.$pUrl.'?pId=\'+ret.'.$pIdField.');';
    $this->resultado[] = '    } else {';
    $this->resultado[] = '        divAlertCustomBasic("Selecione uma linha.");';
    $this->resultado[] = '    }';
    $this->resultado[] = '}';
    $this->resultado[] = '</script>';
}
function eventOnDblClickRowConsLookUp($pDivName, $pIdInput, $pDescField, $pDescInput) {
    $this->resultado[] = '<script type="text/javascript">';
    $this->resultado[] = 'function onDblClick'.$this->gridName.'(pId) {';
    $this->resultado[] = '    if (pId) { ';
    $this->resultado[] = '        var ret = jQuery("#'.$this->gridName.'").jqGrid(\'getRowData\', pId); ';
    $this->resultado[] = '        $("#'.$pIdInput.'").val(ret.id);';
    $this->resultado[] = '        $("#'.$pDescInput.'").val(ret.'.$pDescField.');';
    $this->resultado[] = '        $("#'.$pDescInput.'").trigger("change");';
    $this->resultado[] = '        $("#'.$pDivName.'").hide();';
    $this->resultado[] = '    } else {';
    $this->resultado[] = '        divAlertCustomBasic("Selecione uma linha.");';
    $this->resultado[] = '    }';
    $this->resultado[] = '}';
    $this->resultado[] = '</script>';
}

function eventSelectRowMasterGrid($pIdField, $pUrl, $pNameSubGrid, $pTitleSubGrid, $pFieldTitle, $callBack = null) {
    $this->resultado[] = '<script type="text/javascript">';
    $this->resultado[] = 'function eventOnSelectRowOf'.$this->getGridName().'(pIds) {';
    $this->resultado[] = '    if (pIds) { ';
    $this->resultado[] = '       var ret = jQuery("#'.$this->gridName.'").jqGrid(\'getRowData\', pIds); ';
    $this->resultado[] = '       jQuery("#'.$pNameSubGrid.'").jqGrid(\'setGridParam\',{url:"'.$pUrl.'?ids="+ret.'.$pIdField.',page:1});';
    $this->resultado[] = '       jQuery("#'.$pNameSubGrid.'").jqGrid().trigger(\'reloadGrid\');';
    //$this->resultado[] = '       jQuery("#'.$pNameSubGrid.'").jqGrid(\'setCaption\',"'.$pTitleSubGrid.': "+ret.'.$pFieldTitle.').trigger(\'reloadGrid\');';
    if ($callBack != null) {
        $this->resultado[] = $callBack;
        // callBack(pIds); callBack(ret);
    }
    $this->resultado[] = '    } else {';
    $this->resultado[] = '       jQuery("#'.$pNameSubGrid.'").jqGrid(\'setGridParam\',{url:"'.$pUrl.'?ids=0",page:1});';
    $this->resultado[] = '       jQuery("#'.$pNameSubGrid.'").jqGrid(\'setCaption\',"Nenhuma linha selecionada.").trigger(\'reloadGrid\');';
    $this->resultado[] = '    }';
    $this->resultado[] = '}';
    $this->resultado[] = '</script>';   
}

function getGridName() {
    return $this->gridName;
}

function drawGrid() {
    foreach($this->resultado as $drawGrid) {
        echo $drawGrid . "\n";
    }    
}

function resultGrid() {
    return $this->resultado;
}

}

?>