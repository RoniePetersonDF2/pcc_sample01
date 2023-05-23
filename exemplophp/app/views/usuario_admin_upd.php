<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/site/header.php';
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
        # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : 'USU';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        
        # cria uma consulta banco de dados atualizando um usuario existente. 
        # usando como parametros os campos nome e password.
        $query = "UPDATE `pccsampledb`.`usuarios` SET `EMAIL` = :email,
                    `nome` = :nome, `perfil` = :perfil, `status` = :status 
                    WHERE id = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':perfil', $perfil);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        # executa a consulta banco de dados para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: usuario_admin_list.php?success=Usuário atualizado com sucesso!');
        } else {
            $error = $dbh->errorInfo();
            var_dump($error);
            header('location: usuario_admin_new.php?error=Erro ao atualizar o usuário!');
        }

        # destroi a conexao com o banco de dados.
        $dbh = null;
    }
    
    # cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
    # filtrando pelo id do usuário.
    $query = "SELECT * FROM `pccsampledb`.`usuarios` WHERE id=:id LIMIT 1";
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
        header('location: usuario_admin_list.php?error=Usuário inválido.');
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
                        title: 'Usuários',
                        text: '<?=$_GET['error'] ?>',
                        })
                    </script>
            <?php } ?>
            <section class="novo__form__section">
                <div class="novo__form__titulo">
                    <h2>Atualizar Usuários</h2>
                </div>
                <form action="" method="post" class="novo__form">
                    <label for="email">E-mail</label><br>
                    <input type="email" 
                        name="email" 
                        placeholder="Informe seu e-mail."
                        required autofocus
                        value="<?=isset($row)? $row['email'] : ''?>"
                        ><br><br>
                    <label for="nome">Nome</label><br>
                    <input type="text" 
                        name="nome" 
                        placeholder="Informe seu nome."  
                        value="<?=isset($row)? $row['nome'] : ''?>"
                        required><br><br>
                    <label for="perfil">Perfil</label><br>
                    <select name="perfil"><br><br>
                        <option value="USU" <?=isset($row) && $row['perfil'] == 'USU'? 'selected' : ''?>>Usuário</option>
                        <option value="EDI" <?=isset($row) && $row['perfil'] == 'EDI'? 'selected' : ''?>>Editor</option>
                        <option value="GER" <?=isset($row) && $row['perfil'] == 'GER'? 'selected' : ''?>>Gerente</option>
                        <option value="ADM" <?=isset($row) && $row['perfil'] == 'ADM'? 'selected' : ''?>>Administrador</option>
                    </select><br><br>
                    <label for="status">Status</label><br>
                    <select name="status"><br><br>
                        <option value="1" <?=isset($row) && $row['status'] == '1'? 'selected' : ''?>>Ativo</option>
                        <option value="0" <?=isset($row) && $row['status'] == '0'? 'selected' : ''?>>Inativo</option>
                    </select>
                    <input type="submit" value="Salvar" name="salvar">
               </form>
            </section>
            </div>

    </main>
    
</body>


</html>
