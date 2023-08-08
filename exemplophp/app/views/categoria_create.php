<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'ADM' && $_SESSION['usuario']['perfil'] != 'GER' )) {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    require_once "../controllers/CategoriaController.php";
    # cria/instancia um objeto da classe categoria controller
    $categoriaController = new CategoriaController();

    
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $categoria = array(
            'nome' => isset($_POST['nome']) ? $_POST['nome'] : '',
            'status' => isset($_POST['status']) ? $_POST['status'] : 0,
        );
        $rowCount = $categoriaController->salvar($categoria);

        if($rowCount) {
            header('location: categoria_index.php?success=Categoria inserido com sucesso!');
        } else {
            header('location: categoria_create.php?error=Erro ao inserir categoria!');
        }
    }
    require_once 'layouts/admin/header.php';
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
                    <h2>Cadastro de Categorias</h2>
                </div>
                <form action="" method="post" class="novo__form">
                    <div>
                        <label for="nome">Nome</label><br>
                        <input type="text" name="nome" placeholder="Informe seu nome."  required><br><br>
                    </div>
                    <label for="status">Status</label><br>
                    <select name="status"><br><br>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select><br><br>

                    <input type="submit" value="Salvar" name="salvar">
               </form>
            </section>
            </div>
    </main>    
</body>
</html>
