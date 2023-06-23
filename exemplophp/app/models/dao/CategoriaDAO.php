<?php

    class CategoriaDAO 
    {
        private $dbh;

        public function __construct() {
            # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
            $this->dbh = Conexao::getInstance();
        }
        
        public function listarTodasCategorias() {
            # cria uma consulta banco de dados buscando todos os dados da tabela  
            # ordenando pelo campo nome da categoria.
            $query = "SELECT * 
                FROM `pccsampledb`.`categorias` 
                ORDER BY nome";
            $stmt = $this->dbh->prepare($query);

            # executa a consulta banco de dados e aguarda o resultado.
            $stmt->execute();

            # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
            # se não existir retorna null
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function __destruct()
        {
            $this->dbh = null;
        }
    }