<?php
namespace Classes\Model;
use Classes\DataBase\DataBase;

class Model {
    protected $connection;
    public function __construct() {
        $this->connection = DataBase::getInstance();
    }
}
