<?php
namespace fijdemon\fijsql\mysql;

class SaeMysql extends mysql{
    public function connect($host, $port, $user, $password, $db, $charset='utf8'){
        parent::connect($host, $port, $user, $password, $db, $charset);
    }
    
    public function __construct($db=SAE_MYSQL_DB){
        $this->connect(SAE_MYSQL_HOST_M, SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS, $db, 'utf8');
    }
}

?>