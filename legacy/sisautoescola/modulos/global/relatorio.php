<?php
class modulos_global_relatorio {

    private $resultado = null;

    /*
     * 1 = HTML
     */
    private $tipoRelatorio = null;
    private $isClose = false;

    /*
     * Tipo do relatório:
     *  1 - HTML
     *  2 - PRINT
     *  3 - EXCEL
     */
    function __construct($pTipoRelatorio = 1, $pTitle = "", $pURLPrint = "") {
        $this->tipoRelatorio = $pTipoRelatorio;

        if ($pTipoRelatorio == 1) {
            $this->resultado[] = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            $this->resultado[] = '<html>';
            $this->resultado[] = '<head>';
            $this->resultado[] = '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
            $this->resultado[] = '<title>'.$pTitle.'</title>';

            $this->resultado[] = '<link type="text/css" rel="stylesheet" href="../../css/relatorio.css" />';
            $this->resultado[] = '<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>';
            $this->resultado[] = '<script type="text/javascript" src="../../js/jquery-ui-1.7.2.custom.min.js"></script>';

            $this->resultado[] = '<script type="text/javascript"> ';
            $this->resultado[] = 'function imprimir() { ';
            $this->resultado[] = 'location.href =  "'.$pURLPrint.'"; ';
            $this->resultado[] = '}';
            $this->resultado[] = '</script> ';

            $this->resultado[] = '</head>';
            $this->resultado[] = '<body style="margin-left: 0; margin-top: 0;">';
            $this->resultado[] = '<div style="background-color: gainsboro;border: 1px black solid;">';
            $this->resultado[] = '<button onclick="javascript:imprimir();">Imprimir</button>';
            $this->resultado[] = '</div>';            

        } elseif ($pTipoRelatorio == 2) {
            $this->resultado[] = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            $this->resultado[] = '<html>';
            $this->resultado[] = '<head>';
            $this->resultado[] = '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
            $this->resultado[] = '<title>'.$pTitle.'</title>';
            $this->resultado[] = '<link type="text/css" rel="stylesheet" href="../../css/relatorioimp.css" />';
            $this->resultado[] = '<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>';
            $this->printOnLoad();
            $this->resultado[] = '</head>';
            $this->resultado[] = '<body>';            
        } elseif ($pTipoRelatorio == 3) {
            header("Content-type: application/vnd.ms-excel");
            header("Content-type: application/force-download");
            header("Content-Disposition: attachment; filename=relatorio4rodas.xls");
            header("Pragma: no-cache");
        } elseif ($pTipoRelatorio == 4) {
            header("Content-type: application/vnd.ms-word");
            header("Content-type: application/force-download");
            header("Content-Disposition: attachment; filename=relatorio4rodas.doc");
            header("Pragma: no-cache");
        }

        if ($pTipoRelatorio == 3 or $pTipoRelatorio == 4) {
            $this->resultado[] = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            $this->resultado[] = '<html>';
            $this->resultado[] = '<head>';
            $this->resultado[] = '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
            $this->resultado[] = '<title>'.$pTitle.'</title>';
            $this->resultado[] = '<style type="text/css">';
            $lines = file("../../css/relatorio.css");
            foreach ($lines as $line) {
                $this->resultado[] = $line;
            }
            $this->resultado[] = '</style>';            
            $this->resultado[] = '</head>';
            $this->resultado[] = '<body>';
        }

    }

    public function h1($pTxt) {
        $this->resultado[] = "<h1>".$pTxt."</h1>";
    }

    public function h2($pTxt) {
        $this->resultado[] = '<div style="margin:5px;">'.$pTxt."</div>";
    }

    public function alertMessage($pTxt) {
        $this->resultado[] = '<script type="text/javascript">';
        $this->resultado[] = 'opener.mensagemAlert("'.$pTxt.'");';
        $this->resultado[] = '</script>';
    }

    public function alertAndClose($pTxt) {
        $this->resultado[] = '<script type="text/javascript"> ';
        $this->resultado[] = 'opener.mensagemAlert("'.$pTxt.'");';
        $this->resultado[] = 'window.close(); ';
        $this->resultado[] = '</script>';
        $this->isClose = true;
    }

    public function attrDadosLabel() {
        $retorno = null;
        $retorno["style"] = "font-weight: bold;";
        return $retorno;
    }

    public function attrListaTitulo() {
        $retorno = null;
        $retorno["align"] = "center";
        $retorno["style"] = "font-weight: bold;";
        return $retorno;
    }

    public function attrCabTable() {
        $retorno = null;
        $retorno["style"] = "width:100%;text-align:center;";
        return $retorno;
    }
    public function attrCabTitulo() {
        $retorno = null;
        $retorno["style"] = "font-size:12pt;letter-spacing:2px;";
        return $retorno;
    }
    public function attrCabDesc() {
        $retorno = null;
        $retorno["style"] = "font-size:10pt;";
        return $retorno;
    }

    public function openTable($pId, $atributos = null) {
        $txt = '<table id="'.$pId.'" ';
        $attr = $atributos;
        if (isset ($attr) and is_array($attr)) {
            foreach ($attr as $key => $value) {
                $txt .= $key.'="'.$value.'" ';
            }            
        }
        $txt .= '>';
        $this->resultado[] = $txt;
    }

    public function newLine($atributos = null) {
        $txt = '<tr ';
        if (isset ($atributos) and is_array($atributos)) {
            foreach ($atributos as $key => $value) {
                $txt .= $key.'="'.$value.'" ';
            }
        }
        $txt .= '>';
        $this->resultado[] = $txt;
    }

    public function newFoot($atributos = null) {
        $txt = '<tfoot ';
        if (isset ($atributos) and is_array($atributos)) {
            foreach ($atributos as $key => $value) {
                $txt .= $key.'="'.$value.'" ';
            }
        }
        $txt .= '>';
        $this->resultado[] = $txt;
    }

    public function newCel($valorCelula, $atributos = null) {
        $txt = '<td ';
        if (isset ($atributos) and is_array($atributos)) {
            foreach ($atributos as $key => $value) {
                $txt .= $key.'="'.$value.'" ';
            }
        }
        $txt .= '>';
        $this->resultado[] = $txt;
        $this->resultado[] = $valorCelula;
        $this->resultado[] = '</td>';
    }

    public function titulo($txt) {
        $attr = null;
        $attr["width"] = "100%";
        $attr["style"] = "text-align:center;font-size:12pt;font-weight:bold;margin-bottom:5px;";
        $this->openTable('tblRelTitulo', $attr);
        $this->newLine();
        $this->newCel($txt);
        $this->closeLine();
        $this->closeTable();
    }
    
    public function subTitulo($txt) {
        $attr = null;
        $attr["width"] = "100%";
        $attr["style"] = "text-align:center;font-size:10pt;font-weight:bold;margin-bottom:20px;";
        $this->openTable('tblRelTitulo', $attr);
        $this->newLine();
        $this->newCel($txt);
        $this->closeLine();
        $this->closeTable();
    }

    public function newCelHeader($valorCelula, $atributos = null) {
        $txt = '<th ';
        if (isset ($atributos) and is_array($atributos)) {
            foreach ($atributos as $key => $value) {
                $txt .= $key.'="'.$value.'" ';
            }
        }
        $txt .= '>';
        $this->resultado[] = $txt;
        $this->resultado[] = $valorCelula;
        $this->resultado[] = '</th>';
    }

    public function closeLine() {
        $this->resultado[] = '</tr>';
    }

    public function closeFoot() {
        $this->resultado[] = '</tfoot>';
    }

    public function closeTable() {
        $this->resultado[] = '</table>';
    }

    public function drawAndClean() {
        foreach($this->resultado as $drawGrid) {
            echo $drawGrid . "\n";
        }
        $this->resultado = null;
    }

    public function hr() {
        $this->resultado[] = "<hr />";
    }

    public function divClear($pMarginBottom = null) {
        $txt = '<div style="clear:both;';
        if (isset($pMarginBottom)) {
            $txt .= 'margin-bottom:'.$pMarginBottom.';';
        }
        $txt .= '"></div>';
        $this->resultado[] = $txt;
    }

    public function pageBreak() {
        $this->resultado[] = '<div style="page-break-after: always;"></div>';
    }

    public function printOnLoad() {
        if ($this->isClose == false) {
            $this->resultado[] = '<script type="text/javascript"> ';
            $this->resultado[] = '$(document).ready(function(){ ';
            $this->resultado[] = 'window.print(); ';
            $this->resultado[] = 'window.close(); ';
            $this->resultado[] = '}); ';
            $this->resultado[] = '</script>';
        }
    }

    public function null($html) {
        $this->resultado[] = $html;
    }

    public function close() {
        $this->resultado[] = '</body>';
        $this->resultado[] = '</html>';
        $this->drawAndClean();
    }
}
?>