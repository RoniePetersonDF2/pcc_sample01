<?php
    # inclui a classe de conexao com o banco de dados.
    require_once "../database/conexao.php";

    # verifica se os dados do formulario foram passados via método POST.
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        # cria duas variaveis (nome, password) para armazenar os dados passados via método POST.
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $password = isset($_POST['password']) ? md5($_POST['password']) : '';

        # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
        $dbh = Conexao::getInstance();

        # cria uma consulta banco de dados verificando se o usuario existe 
        # usando como parametros os campos nome e password.
        $query = "SELECT * FROM `pccsampledb`.`usuarios` WHERE nome = :nome AND `password` = :password";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':password', $password);

        # executa a consulta banco de dados e aguarda o resultado.
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
        # se o resultado retornado for diferente de NULL, cria uma sessão com os dados do usuario.
        # e redireciona para a pagina de administracao de usuarios.
        # se não, destroi toodas as sessões existentes e redireciona para a pagina inicial.
        if($row) {
            $_SESSION['usuario'] = [
                'nome' => $row['nome'],
                'perfil' => $row['perfil'],
            ];
            if($row['perfil'] === 'ADM') {
                header('location: usuario_admin.php');
            } else {
                header('location: index.php');
            }
        } else {
            session_destroy();
            header('location: index.php?error=Usuário ou senha inválidos.');
        }

        # destroi a conexao com o banco de dados.
        $dbh = null;
    }
?>
<!--POP LOGIN-->
<div class="overlay"></div>
<div class="modal">

    <div class="div_login">
        <form action="index.php" method="post">
            <h1>Login</h1><br>
            <input type="text" name="nome" placeholder="Nome" class="input" required autofocus>
            <br><br>
            <input type="password" name="password" placeholder="Senha" class="input" required>
            <br><br>
            <button class="button">Enviar</button>
        </form>
        
        <div class="novo__form__login">
            <a href="#">Criar conta</a>
            <a href="#">Esqueceu sua senha?</a>
        </div>
    </div>

</div>
<!--FIM POP LOGIN-->