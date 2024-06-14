<?php

class modulos_global_childgrid {

private $resultado = null;
private $gridName  = null;
private $panelName = null;


function __construct($pName, $pCaption, $pURL, $pColNames, $pColModel, $pSortName, $pEventDblClick = false, $pHide = false, $SearchType = false) {

    $this->gridName  = 'tblGrid'. $pName;
    $this->panelName = 'dvGrid'. $pName;

    $this->resultado[] = '<table id="'.$this->gridName .'"></table>';
    $this->resultado[] = '<div id="'.$this->panelName.'"></div>';

    $this->resultado[] = '<div id="filter" style="margin-left:30%;display:none">Search Invoices</div>';

    $this->resultado[] = '<script type="text/javascript">';
    $this->resultado[] = 'jQuery().ready(function (){ ';

    $this->resultado[] = 'var jsGrid'.$this->gridName.' = jQuery("#'.$this->gridName.'").jqGrid({ ';

    $this->resultado[] = '  url:"'.$pURL.'", ';
    $this->resultado[] = '  datatype: "xml", ';

    $this->resultado[] = '  colNames:["'. join('","', $pColNames) .'"], ';
    $this->resultado[] = '  colModel:['. join(',', $pColModel) .'], ';

    if (isset ($pHide) and $pHide == true) {
        $this->resultado[] = '  hidegrid: true, ';
    } else {
        $this->resultado[] = '  hidegrid: false, ';
    }

    if (isset ($SearchType) and $SearchType == true) {
        $this->resultado[] = '  height:210, ';
        $this->resultado[] = '  width: 560, ';
    } else {
        $this->resultado[] = '  height:240, ';
        $this->resultado[] = '  autowidth: true, ';
    }

    $this->resultado[] = '  rowNum:10, ';
    $this->resultado[] = '  rowList:[10,20,30], ';
    $this->resultado[] = '  pager: jQuery(\'#'.$this->panelName.'\'), ';
    $this->resultado[] = '  sortname: "'.$pSortName.'", ';
    $this->resultado[] = '  multiselect: true, ';
    $this->resultado[] = '  viewrecords: true, ';
    $this->resultado[] = '  rownumbers: true, ';
    $this->resultado[] = '  gridview: true, ';
    $this->resultado[] = '  sortorder: "asc", ';
    $this->resultado[] = '  caption:"'.$pCaption.'", ';

    if (isset ($pEventDblClick) and $pEventDblClick == true) {
        $this->resultado[] = '  ondblClickRow: function(id){ eventOnDblClickRow(id); } ';
    }

    $this->resultado[] = '}).navGrid( ';
    $this->resultado[] = '      \'#'.$this->panelName.'\',{ ';
    $this->resultado[] = '          edit:false, ';
    $this->resultado[] = '          add:false, ';
    $this->resultado[] = '          del:false, ';
    $this->resultado[] = '          search:true, ';
    $this->resultado[] = '          refresh:true ';
    $this->resultado[] = '      }); ';

    $this->resultado[] = 'jQuery("#'.$this->gridName .'").jqGrid("filterToolbar");';

    if (!isset ($SearchType) or $SearchType == false) {
        $this->resultado[] = 'jsGrid'.$this->gridName.'[0].toggleToolbar()';
    }

    $this->resultado[] = '});';
    $this->resultado[] = '</script>';

}

function eventOnDblClickRowAlterRow($pIdField, $pUrl) {
    $this->resultado[] = '<script type="text/javascript">';
    $this->resultado[] = 'function eventOnDblClickRow(pId) {';
    $this->resultado[] = '    if (pId) { ';
    $this->resultado[] = '        var ret = jQuery("#'.$this->gridName.'").jqGrid(\'getRowData\', pId); ';
    $this->resultado[] = '        var $tabs = $(\'#tabs\').tabs();';
    $this->resultado[] = '        var selected = $tabs.tabs(\'option\', \'selected\');';
    $this->resultado[] = '        $tabs.tabs(\'url\', selected, \''.$pUrl.'?pId=\'+ret.'.$pIdField.');';
    $this->resultado[] = '        $tabs.tabs(\'load\', selected);';
    $this->resultado[] = '    } else {';
    $this->resultado[] = '        divAlertCustomBasic("Selecione uma linha.");';
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