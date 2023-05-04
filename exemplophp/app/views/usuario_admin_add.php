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

    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : 'USU';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $password = md5('123');
        
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
        $stmt->bindParam(':password', $password);

        # executa a consulta banco de dados para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: usuario_admin.php?msg=Usuário inserido com sucesso!');
        } else {
            header('location: usuario_admin_add.php?error=Erro ao inserir usuário!');
        }

        # destroi a conexao com o banco de dados.
        $dbh = null;
    }
?>
<body>
    <?php require_once 'layouts/admin/menu.php';?>
    
    <main>
        <div class="main_opc">

            <section >
               <form action="" method="post" class="box">
                    <label for="email">E-mail</label><br>
                    <input type="email" name="email" placeholder="Informe seu e-mail." autofocus ><br><br>
                    <label for="nome">Nome</label><br>
                    <input type="text" name="nome" placeholder="Informe seu nome."><br><br>
                    <label for="perfil">Perfil</label><br>
                    <select name="pefil"><br><br>
                        <option value="USU">Usuário</option>
                        <option value="EDI">Editor</option>
                        <option value="GER">Gerente</option>
                        <option value="ADM">Administrador</option>
                    </select><br><br>
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
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>