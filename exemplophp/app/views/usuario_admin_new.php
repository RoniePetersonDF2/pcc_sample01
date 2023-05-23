<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/site/header.php';
    require_once "../database/conexao.php";
    
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $perfil = 'USU';
        $status = 1;
        
        # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
        $dbh = Conexao::getInstance();

        # cria uma consulta banco de dados verificando se o usuario existe 
        # usando como parametros os campos nome e password.
        $query = "INSERT INTO `pccsampledb`.`usuarios` (`EMAIL`,`nome`, `perfil`, `status`, `password`)
                    VALUES (:email, :nome, :perfil, :status, :password)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':perfil', $perfil);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':password', md5($password));

        # executa a consulta banco de dados para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: index.php?success=Cadastro realizado com sucesso! Aguarde o administrador liberar seu acesso');
        } else {
            header('location: usuario_admin_new.php?error=Erro ao cadastrar nova conta!');
        }

        # destroi a conexao com o banco de dados.
        $dbh = null;
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
                        title: 'Usuários',
                        text: '<?=$_GET['error'] ?>',
                        })
                    </script>
            <?php } ?>
            <section class="novo__form__section">
                <div class="novo__form__titulo">
                    <h2>Nova Conta</h2>
                </div>
                <form action="" method="post" class="novo__form">
                    <label for="email">E-mail</label><br>
                    <input type="email" name="email" placeholder="Informe seu e-mail." required autofocus ><br><br>
                    <label for="nome">Nome</label><br>
                    <input type="text" name="nome" placeholder="Informe seu nome."  required><br><br>
                    <label for="password">Password</label><br>
                    <input type="password" name="password" placeholder="Informe seu password."  required><br><br>
                    
                    <input type="submit" value="Enviar" name="salvar">
               </form>
            </section>
            </div>

    </main>
    
</body>


</html>
