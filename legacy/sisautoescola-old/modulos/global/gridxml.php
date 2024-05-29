<?php

class modulos_global_gridxml {

    private $page   = null; 
    private $limit  = null;
    private $start  = null;

    private $resultado = null;

    function __construct($pPage, $pLimit, $pCountTable) {

        if ($pPage > 0) {
            $this->page = $pPage;
        } else {
            $this->page = 1;
        }

        $this->limit    = $pLimit;      

        $count = $pCountTable;
        if( $count > 0 ) {
            $total_pages = ceil($count/$this->limit);
        } else {
            $total_pages = 0;
        }
        if ($this->page > $total_pages) $this->page = $total_pages;
        $this->start = $this->limit * $this->page - $this->limit;

        if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
            header("Content-type: application/xhtml+xml;charset=utf-8");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }

        $et = ">";

        $this->resultado[] = "<?xml version='1.0' encoding='utf-8'?$et\n";

        $this->resultado[] = "<rows>";
        $this->resultado[] = "<page>".$this->page."</page>";
        $this->resultado[] = "<total>".$total_pages."</total>";
        $this->resultado[] = "<records>".$count."</records>";

    }

    function getStart() {
        return $this->start;
    }

    function getLimit() {
        return $this->limit;
    }

    function startRow($pId) {
        $this->resultado[] = "<row id='". $pId ."'>";
    }
    function addCell($pText, $pCData = false) {
        $this->resultado[] = "<cell><![CDATA[".$pText."]]></cell>";
    }

    function endRow() {
        $this->resultado[] = "</row>";
    }

    function close() {
        $this->resultado[] = "</rows>";

        foreach($this->resultado as $drawGrid) {
            echo $drawGrid;
        }
    }

}

?>
