<?php
namespace Classes\DataBase;

class DataBase {
    private $dsn = 'mysql:dbname=;host=127.0.0.1';
    private $user = '';
    private $password = '';
    private $dbh = null;
    private static $instance = null;
    private function __construct() {
        if(!file_exists(ROOT_PATH.'/config/db_config.php')){
            throw new \Exception('Database configuration file does not exists');
        }
        require_once ROOT_PATH.'/config/db_config.php';
        if(isset($db_host) && isset($db_name)){
            $this->dsn = 'mysql:dbname='.$db_name.';host='.$db_host;
        }
        if(isset($db_user)){
            $this->user = $db_user;
        }
        if(isset($db_password)){
            $this->password = $db_password;
        }
        try {
            $this->dbh = new \PDO($this->dsn, $this->user, $this->password);
        } catch (\PDOException $e) {
            throw new \Exception('Connection failed: ' . $e->getMessage());
        }
    }
    
    static public function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    protected function __clone() {
    }
    
    public function executeQuery($query, array $params = array()){
        $sth = $this->dbh->prepare($query);
        try {
            $sth->execute($params);
        } catch (\PDOException $e){
           echo $e->getMessage();
        }
    }
    
    public function executeSelect($sql, $params = array()){
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);
        $red = $sth->fetchAll(\PDO::FETCH_COLUMN);
        return $red;
    }
}
