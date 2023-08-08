<?php
require_once '../models/CategoriaDAO.php';

class CategoriaController {
    private $categoriaDAO;

    public function __construct()
    {
        $this->categoriaDAO = new CategoriaDAO();        
    }

    public function listarTodas() 
    {
        return $this->categoriaDAO->listarTodas();
    }

    public function findById($id)
    {
        return $this->categoriaDAO->findById($id);
    }

    public function salvar($categoria)
    {
        if(isset($categoria['id'])) {
            $rowCount = $this->categoriaDAO->atualizar($categoria);
        } else {
            $rowCount = $this->categoriaDAO->adicionar($categoria);
        }        

        if($rowCount) {
            header('location: categoria_index.php?success=Operação realizada com sucesso!');
        } else {
            header('location: categoria_create.php?error=Erro ao realizar operação!');
        }
    }
        
    public function apagar($id)
    {
        $rowCount = $this->categoriaDAO->apagar($id);
        if($rowCount) {
            header('location: categoria_index.php?success=Categoria excluída com sucesso!');
        } else {
            header('location: categoria_index.php?error=Erro ao excluir categoria!');
        }
    }
}

