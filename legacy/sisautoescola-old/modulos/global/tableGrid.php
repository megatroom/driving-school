<?php
class modulos_global_tableGrid {

    private $begin = null;
    private $head = null;
    private $body = null;
    private $foot = null;

    private $tableGridName  = null;
    private $title = null;

    private $columnTotal;
    private $headOpened;
    private $headClosed;

    private $pageNumber;
    private $pageTotal;

    function __construct(
            $pName,
            $pTitle = null,
            $pPagNum = 1,
            $pPagTot = 1) {

        $this->tableGridName = "tblgrd" . $pName;
        $this->title = $pTitle;

        $this->begin = null;
        $this->head = null;
        $this->body = null;
        $this->foot = null;

        $this->columnTotal = 0;
        $this->headOpened = false;
        $this->headClosed = false;

        $this->pageNumber = $pPagNum;
        $this->pageTotal = $pPagTot;

        $this->buttonCount = 0;

        $this->begin[] = '<table id="'.
                $this->tableGridName.
                '" class="ui-widget ui-widget-content" cellpadding="5">';
    }

    function openHead() {
        $this->headOpened = true;
        $this->begin[] = '<thead class="ui-widget-header ">';
    }

    function closeHead() {
        $this->head[] = '</thead>';
        $this->headClosed = true;

        $this->begin[] = '<tr>';
        $this->begin[] = '<td colspan="'.$this->columnTotal.'" align="center">'.
                $this->title.
                '</td>';
        $this->begin[] = '</tr>';
    }

    function openLine() {
        if (($this->headOpened == true) && ($this->headClosed == false)) {
            $this->head[] = '<tr>';
        } else {
            $this->body[] = '<tr>';
        }
    }

    function closeLine() {
        if (($this->headOpened == true) && ($this->headClosed == false)) {
            $this->head[] = '</tr>';
        } else {
            $this->body[] = '</tr>';
        }
    }

    function newCel($pText, $pAttr = null) {
        $txt = '<td ';
        if ($pAttr != null) {
            foreach ($pAttr as $key => $value) {
                $txt .= $key . '="' . $value .'"';
            }
        }
        $txt .= '>';
        $txt .= $pText;
        $txt .= '</td>';
        
        if (($this->headOpened == true) && ($this->headClosed == false)) {
            $this->columnTotal++;
            $this->head[] = $txt;
        } else {
            $this->body[] = $txt;
        }
    }

    private function newButton($pIcon, $pId, $pKey, $pCaption = null, $pClass = null, $pHidden = false) {
        $txt = '<td><a id="'. $pId .'" ';

        $txt .= 'class="fg-button ui-state-default ui-corner-all';
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
        $txt .= '<input type="hidden" value="'.$pKey.'" />';
        $this->body[] = $txt;
	$this->body[] = '<span id="iconBtn'.
                $this->tableGridName .
                $this->buttonCount .
                '" class="ui-icon '. $pIcon .'"/>';

        if (isset ($pCaption) and strlen($pCaption) > 0) {
            $this->body[] = $pCaption;
        }

        $this->body[] = '</a></td>';
    }

    function getBtnEditName() {
        return "btnAlt".$this->tableGridName;
    }
    function newCelEdit($pId) {
        $this->newButton("ui-icon-pencil", "btnAlt".$this->tableGridName, $pId);
    }

    function getBtnExcName() {
        return "btnExc".$this->tableGridName;
    }
    function newCelExc($pId) {
        $this->newButton("ui-icon-trash", "btnExc".$this->tableGridName, $pId);
    }

    private function newFootButton($pIcon, $pId, $pCaption = null, $pClass = null, $pHidden = false) {
        $txt = '<a id="'.$pId.'" ';

        $txt .= 'class="fg-button ui-state-default ui-corner-all';
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
        $this->foot[] = $txt;
	$this->foot[] = '<span id="iconBtn'.
                $this->tableGridName .
                $this->buttonCount .
                '" class="ui-icon '. $pIcon .'"/>';

        if (isset ($pCaption) and strlen($pCaption) > 0) {
            $this->foot[] = $pCaption;
        }

        $this->foot[] = '</a>';
    }

    private function newFootButtonFirst() {
        $this->newFootButton("ui-icon-seek-first", 'btnFirst'. $this->tableGridName);
    }
    function getFootBtnFirstName() {
        return 'btnFirst'. $this->tableGridName;
    }

    private function newFootButtonBack() {
        $this->newFootButton("ui-icon-seek-prev", 'btnBack'. $this->tableGridName);
    }
    function getFootBtnBackName() {
        return 'btnBack'. $this->tableGridName;
    }

    private function newCelExcNext() {
        $this->newFootButton("ui-icon-seek-next", 'btnNext'. $this->tableGridName);
    }
    function getFootBtnNextkName() {
        return 'btnNext'. $this->tableGridName;
    }

    private function newCelExcLast() {
        $this->newFootButton("ui-icon-seek-end", 'btnLast'. $this->tableGridName);
    }
    function getFootBtnLstName() {
        return 'btnLast'. $this->tableGridName;
    }

    function close() {
        $this->foot[] = '<tfoot><tr>';
        $this->foot[] = '<td colspan="'.$this->columnTotal.'" align="center">';
        $this->foot[] = '<table><tr><td>';
        $this->newFootButtonFirst();
        $this->newFootButtonBack();
        $this->foot[] = '</td><td><b>';
        $this->foot[] = ' Página '.$this->pageNumber.' de '.$this->pageTotal.' ';
        $this->foot[] = '</b></td><td>';
        $this->newCelExcNext();
        $this->newCelExcLast();
        $this->foot[] = '</td></tr></table>';
        $this->foot[] = '</td></tr></tfoot>';
        $this->foot[] = '</table>';
        if ($this->begin != null) {
            foreach ($this->begin as $value) {
                echo $value;
            }
        }
        if ($this->head != null) {
            foreach ($this->head as $value) {
                echo $value;
            }
        }
        if ($this->body != null) {
            foreach ($this->body as $value) {
                echo $value;
            }
        }
        if ($this->foot != null) {
            foreach ($this->foot as $value) {
                echo $value;
            }
        }
    }
}
?>