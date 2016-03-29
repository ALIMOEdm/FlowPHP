<?php
/**
 * Description of DataBase
 *
 * @author alimoedm
 */
class DataBase {
    private $dsn = 'mysql:dbname=testdb;host=127.0.0.1';
    private $user = 'dbuser';
    private $password = 'dbpass';
    private $dbh = null;
    //put your code here
    private function __construct() {
        try {
            $this->dbh = new PDO($this->dsn, $this->user, $this->password);
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }
}
