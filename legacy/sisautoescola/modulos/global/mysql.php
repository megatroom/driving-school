<?php
ob_start(); session_start(); ob_end_clean();
class modulos_global_mysql {

    private static $connection = null;
    private static $last_sql = "";
    private static $dbconfig;
    
    /**
     * Conecta ao banco de dados
     *
     * @return bool
     */
    private static function _CONNECT() {
        if (!isset(self::$connection)) {
            if (self::$dbconfig->isOk() == false) {
                self::$dbconfig->reLoad();
            }
            self::$connection = mysql_connect(self::$dbconfig->getHost(), self::$dbconfig->getUser(), self::$dbconfig->getPassword());
            mysql_select_db(self::$dbconfig->getDatabase(), self::$connection);
        }
	
	return self::$connection;
    }

    /*
     * Como mudei a conexao para singleton, nao podemos mais fechar conexao
    public function _DESCONNECT() {
        $resultado = false;
	if (is_resource($this->connection)) {
	    $resultado = mysql_close($this->connection);
	}
        $this->connection = false;
	return $resultado;
    }
     */

    /**
     * Abre conexao com banco de dados
     *
     * @return bool
     */
    private static function _OPEN($open = true) {
        if ($open) {
            if (self::_CONNECT()) {
                return true;
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * Fecha conexão com banco de dados
     *
     * @return bool
    private function _CLOSE() {
        $resultado = false;
	if (is_resource($this->connection)) {
	    $resultado = mysql_close($this->connection);
	}
        $this->connection = false;
	return $resultado;
    }
    */

    public static function dbOptimize($pTable) {

	$sql = "optimize table " . $pTable;

        $fifo = self::getQuery($sql);
	if ($fifo) {
	    return true;
	}

	return false;
    }

    /**
     * Executa rotina em sql
     *
     * @param string $query
     * @return mixed
     */
    public static function getQuery($query, $open = true) {
	if (self::_OPEN($open)) {
            $qry_exec = str_replace("#", "", $query);
            $qry_exec = str_replace("--", "", $qry_exec);
	    $query_aux = explode(" ", $query);
            self::$last_sql = $query;
            $result = mysql_query($qry_exec);
	    if ($result) {
		if (is_array($query_aux)) {
		    if ($query_aux[0] == "insert") {
			$id_return = mysql_insert_id();
			if ($id_return) {
			    return $id_return;
			}
		    }
		    return $result;
		}
	    }
	}
	return false;
    }

    function __construct() {
        if (!isset(self::$dbconfig)) {
            self::$dbconfig = new dbconfig;
        }
    }

    function  __destruct() {
        
    }

    public static function dbConfigIsOk() {
        return self::$dbconfig->isOk();
    }

    public function showTables() {
        $sql = "SHOW FULL TABLES FROM ".self::$dbconfig->getDatabase();
        $fifo = self::getQuery($sql);
        return $fifo;
    }

    public function showCreateTable($table) {
        $sql = "show create table ".$table;
        $fifo = self::getQuery($sql);
        return $fifo;
    }

    public function describe($table) {
        $sql = "describe ".$table;
        $fifo = self::getQuery($sql);
        return $fifo;
    }

    public function save($pId,$pTable,$pFields,$pWhere = null) {
        $sql = '';
        if ($pId == 0 or !isset ($pId)){
            $sql .= 'insert into '. $pTable .' ';

            $fields = null;
            $values = null;
            foreach ($pFields as $key => $value) {
                $fields[] = $key;
                $values[] = $value;
            }

            $sql .= '('. join(',', $fields) .') ';
            $sql .= 'values ';
            $sql .= '('. join(',', $values) .') ';
        } else {
            $sql .= "update ".$pTable." set ";

            $values = null;
            foreach ($pFields as $key => $value) {
                $values[] = $key .' = '. $value .' ';
            }

            $sql .= join(', ', $values);

            if (strlen($pWhere) > 0) {
                $sql .= " where " . $pWhere;
            }
        }

        $fifo = self::getQuery($sql);
        if ($fifo) {
            if ($pId==0) {
                return $fifo;
            } else {
                return $pId;
            }
	}

	return false;
    }

    public function delete($pTable, $pWhere = null) {

        $sql = "delete from " . $pTable;

	if (isset ($pWhere) and strlen($pWhere) > 0) {
	    $sql .= " where " . $pWhere;
	}	

	if (self::getQuery($sql)) {
            $fifo = self::dbOptimize($pTable);
	    if ($fifo) {
		return $fifo;
	    }
	}

	return false;
    }

    public function select($pFields,$pTable=null,$pWhere=null,$pOther=null,$pOrderBy=null) {
        $sql = '';
        $sql .= 'select '. $pFields .' ';
        if (isset ($pTable)) {
            $sql .= 'from '. $pTable .' ';
        }
        if (isset ($pWhere)) {
            $sql .= 'where '. $pWhere .' ';
        }
        if (isset ($pOther)) {
            $sql .= $pOther .' ';
        }
        if (isset ($pOrderBy)) {
            $sql .= 'order by '. $pOrderBy .' ';
        }        
        $fifo = self::getQuery($sql);
        if ($fifo) {
	    while ($result = mysql_fetch_array($fifo, MYSQL_ASSOC)) {
		$array[] = $result;
	    }
	    mysql_free_result($fifo);
	    if (isset($array)) {
		return $array;
	    }
	}

	return false;
    }

    public function getCurrentDate() {

        $fifo = $this->select('CURDATE() as datacorrente');
        if ($fifo) {
	    if (is_array($fifo)) {
		// $pFieldAlias = str_replace("`", "", (string)$pFieldAlias);
		foreach ($fifo as $result) {
		    $value = $result["datacorrente"];
		    return $value;
		}
	    }
	    return $fifo;
	}
	return false;
        
    }

    public function alterColumnType($tableName, $column, $type, $NOTNULL=true) {
        $alterSQL = "ALTER TABLE `".$tableName."` MODIFY COLUMN `".$column."` ".$type;
        if ($NOTNULL) {
            $alterSQL .= " NOT NULL";
        }
        self::$last_sql = $alterSQL;
        
        if (self::_OPEN(true)) {
            if (mysql_query($alterSQL)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function addColumnToTable($tableName, $column, $type, $NOTNULL=false) {
        $alterSQL = "ALTER TABLE `".$tableName."` ADD COLUMN `".$column."` ".$type;
        if ($NOTNULL) {
            $alterSQL .= " NOT NULL";
        }
        self::$last_sql = $alterSQL;

        if (self::_OPEN(true)) {
            if (mysql_query($alterSQL)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getValue($pFields,$pFieldAlias,$pTable,$pWhere=null,$pOther=null,$pOrderBy=null,$non_quotes = false) {

	if (!$non_quotes) {
            if (isset ($pFieldAlias) and strlen($pFieldAlias) > 0) {
                $pFieldAlias = '`'.$pFieldAlias.'`';
            } else {
                $pFieldAlias = '`'.$pFields.'`';
            }
	}

        $fifo = $this->select($pFields, $pTable, $pWhere, $pOther, $pOrderBy);
	if ($fifo) {
	    if (is_array($fifo)) {
		$pFieldAlias = str_replace("`", "", (string)$pFieldAlias);
		foreach ($fifo as $result) {
		    $value = $result["$pFieldAlias"];
		    return $value;
		}
	    }
	    return $fifo;
	}
	return false;
    }

    function getMsgErro() {
        return mysql_error() ." - SQL: ". self::$last_sql ."<br>";
    }

    /**
     *
     * @param <String> $tableName
     * @param <array> $fields -> nome, tipo, notnull
     */
    function createTable($tableName, $fields, $other) {
        $sqlCreateTable = "CREATE TABLE IF NOT EXISTS $tableName (";

        $sqlCreateTable .= "id int(10) not null auto_increment";

        foreach ($fields as $field) {
            $sqlCreateTable .= ", ". $field[0] ." ". $field[1];
            if ($field[2] && substr_count($tableName, "log_") == 0) {
                $sqlCreateTable .= " NOT NULL";
            }
        }

        $sqlCreateTable .= ", primary key (id)";

        if (isset ($other) and strlen($other) > 0) {
            $sqlCreateTable .= $other;
        }

        $sqlCreateTable .= ")";

        self::$last_sql = $sqlCreateTable;

        //echo $sqlCreateTable ."<br>";

        if (self::_OPEN(true)) {
            if (mysql_query($sqlCreateTable)) {
                //echo "ok <br>";
                return true;
            } else {
                //echo "no <br>";
                return false;
            }
        }
        //echo "no <br>";
        return false;
    }

    function createView($viewName, $select) {

        $sqlCreateView = "CREATE OR REPLACE VIEW $viewName AS $select ";

        self::$last_sql = $sqlCreateView;

        if (self::_OPEN(true)) {
            if (mysql_query($sqlCreateView)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function createDatabase() {
        $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS ". self::$dbconfig->getDatabase() ." ";

        self::$last_sql = $sqlCreateDatabase;

        self::_CONNECT();

        if (self::$connection) {
            if (mysql_query($sqlCreateDatabase)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}

?>