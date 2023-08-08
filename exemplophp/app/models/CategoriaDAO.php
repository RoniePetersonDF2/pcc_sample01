<?php
    require_once 'database/conexao.php';

    class CategoriaDAO 
    {
        private $dbh;

        public function __construct() {
            # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
            $this->dbh = Conexao::getInstance();
        }
        
        public function listarTodas() {
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

        public function findById($id)
        {
            # cria uma consulta banco de dados buscando todos os dados  
            # filtrando pelo id do usuário.
            $query = "SELECT * FROM `pccsampledb`.`categorias` WHERE id=:id LIMIT 1";
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':id', $id);

            # executa a consulta banco de dados e aguarda o resultado.
            $stmt->execute();
            
            # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
            # se não existir retorna null
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function adicionar($categoria)
        {
            # cria um comando SQL para adicionar valores na tabela categorias 
            $query = "INSERT INTO `pccsampledb`.`categorias` (`nome`,`status`)
            VALUES (:nome, :status)";
            $stmt = $this->dbh->prepare($query);
            
            # executa o comando SQL para inserir o resultado.
            $stmt->execute($categoria);
            // var_dump($this->dbh->errorInfo()); exit;
            return $stmt->rowCount();
        }

        public function atualizar($categoria)
        {
            $query = "UPDATE `pccsampledb`.`categorias` SET 
                `nome` = :nome,
                `status` = :status 
                WHERE id = :id";
            $stmt = $this->dbh->prepare($query);
            $stmt->execute($categoria);

            return $stmt->rowCount();
        }

        public function apagar($id)
        {
            # cria uma query no banco de dados para excluir o usuario com id informado 
            $query = "DELETE FROM `pccsampledb`.`categorias` WHERE id = :id";
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':id', $id);
            
            # executa a consulta banco de dados para excluir o registro.
            $stmt->execute();

            # verifica se a quantiade de registros excluido é maior que zero.
            # se sim, redireciona para a pagina de admin com mensagem de sucesso.
            # se não, redireciona para a pagina de admin com mensagem de erro.
            return $stmt->rowCount();            
        }

        public function __destruct()
        {
            $this->dbh = null;
        }
    }