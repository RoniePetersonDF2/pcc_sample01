<?php
    require_once "database/conexao.php";

    class LoginDAO {
        private $dbh;

        public function __construct() {
            # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
            $this->dbh = Conexao::getInstance();
        }
        
        public function login($nome, $password) {
            # cria uma consulta banco de dados verificando se o usuario existe 
            # usando como parametros os campos nome e password.
            $query = "SELECT * FROM `pccsampledb`.`usuarios` WHERE nome = :nome AND `password` = :password";
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':password', $password);

            # executa a consulta banco de dados e aguarda o resultado.
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function __destruct()
        {
            $this->dbh = null;
        }
    }

    