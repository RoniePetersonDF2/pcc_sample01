<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/admin/header.php';
    require_once "../database/conexao.php";

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # verifica se uma variavel id foi passada via GET 
    $id = isset($_GET['id']) ? $_GET['id'] : 0;

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();
    
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (nome, status, tipo) para armazenar os dados passados via método POST.
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'ART';
    
        # cria um comando SQL para alterar ou modificar valores na tabela  
        $query = "UPDATE `pccsampledb`.`categorias` SET 
                    `nome` = :nome,
                    `status` = :status, 
                    `tipo` = :tipo
                    WHERE id = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':id', $id);

        # executa o comando SQL.
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

    # cria uma consulta banco de dados buscando todos os dados  
    # filtrando pelo id do usuário.
    $query = "SELECT * FROM `pccsampledb`.`categorias` WHERE id=:id LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $id);

    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    
    # se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
    # se não, cria a variavel row com dados do usuario selecionado.
    if(!$row){
        header('location: categoria_index.php?error=Categoria inválida.');
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
                    <label for="tipo">Tipo</label><br>
                    <select name="tipo"><br><br>
                        <option value="ART" <?=isset($row) && $row['tipo'] == '1'? 'selected' : ''?>>Artigos</option>
                        <option value="CUR" <?=isset($row) && $row['tipo'] == '1'? 'selected' : ''?>>Cursos</option>
                    </select>

                    <input type="submit" value="Salvar" name="salvar">
               </form>
            </section>
            </div>
    </main>    
</body>
</html>
