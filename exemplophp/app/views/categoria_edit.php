<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/admin/header.php';
    require_once "../controllers/CategoriaController.php";
    
    
    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'ADM' && $_SESSION['usuario']['perfil'] != 'GER' )) {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }
    
    # cria/instancia um objeto da classe categoria controller
    $categoriaController = new CategoriaController();
    # verifica se uma variavel id foi passada via GET 
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $row = $categoriaController->findById($id);
    # se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
    # se não, cria a variavel row com dados do usuario selecionado.
    if(!$row){
        header('location: categoria_index.php?error=Categoria inválida.');
    }

    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $categoria = array(
            'nome' => isset($_POST['nome']) ? $_POST['nome'] : '',
            'status' => isset($_POST['status']) ? $_POST['status'] : 0,
            'id' => $id,
        );
             
        $rowCount = $categoriaController->salvar($categoria);
        
       if($rowCount) {
            header('location: categoria_index.php?success=Categoria atualizada com sucesso!');
        } else {
            header('location: categoria_index.php?error=Erro ao inserir categoria!');
        }
    }
?>
<body>
    <?php require_once 'layouts/admin/menu.php';?>
    <main>
        <div class="main_opc">
            <?php
                # verifca se existe uma mensagem de erro enviada via GET.
                # se sim, exibe a mensagem enviada no cabeçalho.
                if(isset($_GET['error'])) { ?>
                    <script>
                        Swal.fire({
                        icon: 'error',
                        title: 'Categorias',
                        text: '<?=$_GET['error'] ?>',
                        })
                    </script>
            <?php } ?>
            <section class="novo__form__section">
                <div class="novo__form__titulo">
                    <h2>Atualizar Categorias</h2>
                </div>
                <form action="" method="post" class="novo__form">
                    <input type="hidden" name="id" value="<?=isset($row)? $row['id'] : ''?>">
                    <div>
                        <label for="nome">Nome</label><br>
                        <input type="text" name="nome" 
                                value="<?=isset($row)? $row['nome'] : ''?>"
                                placeholder="Informe seu nome."  
                                required><br><br>
                    </div>
                    <label for="status">Status</label><br>
                    <select name="status"><br><br>
                        <option value="1" <?=isset($row) && $row['status'] == '1'? 'selected' : ''?>>Ativo</option>
                        <option value="0" <?=isset($row) && $row['status'] == '0'? 'selected' : ''?>>Inativo</option>
                    </select><br><br>

                    <input type="submit" value="Salvar" name="salvar">
               </form>
            </section>
            </div>
    </main>    
</body>
</html>
