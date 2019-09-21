<?php

namespace core\database;

use \PDO as PDO;
use \PDOException as PDOEXception;

class Database
{

    public $pdo;
    public $error;
    private $stmt;

    public function __construct($options=[], $type='', $host='', $user='', $pass='', $name='')
    {
        $config = require(BASE_PATH .'/config/config.php');
        // Override constants if provided connection values
        $type = (empty($type))? $config['database']['type'] : $type;
        $host = (empty($host))? $config['database']['host'] : $host;
        $user = (empty($user))? $config['database']['user'] : $user;
        $pass = (empty($pass))? $config['database']['pass'] : $pass;
        $name = (empty($name))? $config['database']['name'] : $name;
        $dsn = "{$type}:host={$host};dbname={$name};charset=utf8";
        if(count($options) == 0) 
        {
            $options = [
                PDO::ATTR_PERSISTENT         => true,  
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_CASE               => PDO::CASE_NATURAL,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
        }  
        try {
        $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $pdoEx){
            $this->error = $pdoEx->getMessage();
        }
    }

    public function setPDOAttribute($options) {
        foreach($options as $attribute => $value) {
            $this->pdo->setAttribute($attribute, $value);
        }
    }

    public function query($query) {
        if(!empty($query)) {
            $this->stmt = $this->pdo->prepare($query);
        }
    }

    public function bind($param, $value, $type = null){
        if(empty($param)) return;
        if (is_null($type)) {  
            switch (true) {  
                case is_int($value):  
                $type = PDO::PARAM_INT;  
                break;  
                case is_bool($value):  
                $type = PDO::PARAM_BOOL;  
                break;  
                case is_null($value):  
                $type = PDO::PARAM_NULL;  
                break;  
                default:  
                $type = PDO::PARAM_STR;  
            }  
        }  
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){  
        return $this->stmt->execute();  
    }  

    public function resultAssoc(){  
        $this->execute();  
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);  
    }  

    public function resultObject(){  
        $this->execute();  
        return $this->stmt->fetchAll(PDO::FETCH_OBJECT);  
    }  

    public function resultSingle(){  
        $this->execute();  
        return $this->stmt->fetch(PDO::FETCH_ASSOC);  
    }  

    public function rowCount(){  
        return $this->stmt->rowCount();  
    }  

    public function lastInsertId(){  
        return $this->pdo->lastInsertId();  
    } 

    public function beginTransaction(){  
        return $this->pdo->beginTransaction();  
    }  

    public function endTransaction(){  
        return $this->pdo->commit();  
    } 
    
    public function cancelTransaction(){  
        return $this->pdo->rollBack();  
    }  

    public function debugDumpParams(){  
        return $this->stmt->debugDumpParams();  
    }         
    
}