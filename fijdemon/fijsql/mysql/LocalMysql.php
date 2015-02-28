<?php
namespace fijdemon\fijsql\mysql;

class LocalMysql extends mysql{
    public function connect($host, $port, $user, $password, $db, $charset='utf8'){
        parent::connect($host, $port, $user, $password, $db, $charset);
    }
    
    public function __construct($db){
        $this->connect('localhost', '3306', 'root', '', $db, 'utf8');
    }
}

?>