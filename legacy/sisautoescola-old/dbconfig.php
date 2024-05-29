<?php
class dbconfig {
    private $dbhost = '';
    private $dbuser = '';
    private $dbpassword = '';
    private $database = '';
    private $ok = false;
	
    function __construct() {
        $this->load();
    }

    public function reLoad() {
        $this->load();
        return $this->isOk();
    }

    private function load() {
        $this->ok = false;
        $filename = "config.ini";
        $path_parts = pathinfo(__FILE__);
        $path = str_replace($path_parts["basename"], '', __FILE__);
        if (file_exists($path.$filename)) {
            $ini_array = parse_ini_file($filename);
            if (is_array($ini_array)) {
                $this->dbhost = $ini_array["host"];
                $this->dbuser = $ini_array["user"];
                $this->dbpassword = base64_decode($ini_array["pwd"]);
                $this->database = $ini_array["database"];
                $this->ok = true;
            }
        }
    }

    public function isOk() {
        return $this->ok;
    }

    public function getHost() {
        return $this->dbhost;
    }

    public function getUser() {
        return $this->dbuser;
    }

    public function getPassword() {
        return $this->dbpassword;
    }

    public function getDatabase() {
        return $this->database;
    }
}

?>