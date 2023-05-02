<?php
    class Conexao 
    {
        protected static $instance;

        protected function __construct() {}
    
        public static function getInstance()
        {
            if(empty(self::$instance))
            {
                try
                {
                    self::$instance = new PDO(
                        "mysql:host=127.0.0.1;port=3306;dbname=pccsampledb", "root", ""
                    );
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);  
                    self::$instance->query('SET NAMES utf8');
                    self::$instance->query('SET CHARACTER SET utf8');
    
                }
                catch(PDOException $error)
                {
                    echo $error->getMessage();
                }
            }
            return self::$instance;
        }
    }
   