<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/admin/header.php';
    require_once "../database/conexao.php";

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'ADM' && $_SESSION['usuario']['perfil'] != 'GER' )) {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();
    
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (nome, status, tipo) para armazenar os dados passados via método POST.
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'ART';
        

        # cria um comando SQL para adicionar valores na tabela categorias 
        $query = "INSERT INTO `pccsampledb`.`categorias` (`nome`,`status`, `tipo`)
                    VALUES (:nome, :status, :tipo)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':tipo', $tipo);

        # executa o comando SQL para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: categoria_index.php?success=Categoria inserido com sucesso!');
        } else {
            header('location: categoria_add.php?error=Erro ao inserir categoria!');
        }
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
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
                    <label for="tipo">Tipo</label><br>
                    <select name="tipo"><br><br>
                        <option value="ART">Artigos</option>
                        <option value="CUR">Cursos</option>
                    </select>

                    <input type="submit" value="Salvar" name="salvar">
               </form>
            </section>
            </div>
    </main>    
</body>
</html>
