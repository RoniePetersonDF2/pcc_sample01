<?php
    require_once "database/conexao.php";

    class ArtigoDAO {
        private $dbh;

        public function __construct() {
            # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
            $this->dbh = Conexao::getInstance();
        }
        
        public function listarArtigosPorCategoriaOuTitulo($filtroCategoria, $filtroTitulo) {
            # cria uma consulta banco de dados buscando todos os dados da tabela  
            # ordenando pelo campo data e limita o resultado a 10 registros.
            $query = "SELECT art.*, cat.nome as categoria 
                FROM `pccsampledb`.`artigos` AS art 
                INNER JOIN `pccsampledb`.`categorias` AS cat ON cat.id = art.categoria_id
                WHERE art.status = 1";
            # verifica se existe filtro para categoria.
            # se sim adiciona condição ao select.
            if($filtroCategoria != null && $filtroCategoria != "0") {
                $query .= " AND cat.id = '" .$filtroCategoria . "' ";    
            }
            if($filtroTitulo != null && $filtroTitulo != "0") {
                $query .= " AND art.titulo LIKE '%" .$filtroTitulo . "%' ";    
            }

            $query .= " ORDER BY art.data_publicacao DESC limit 10";

            $stmt = $this->dbh->prepare($query);

            # executa a consulta banco de dados e aguarda o resultado.
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);    
        }

        public function __destruct()
        {
            $this->dbh = null;
        }
    }