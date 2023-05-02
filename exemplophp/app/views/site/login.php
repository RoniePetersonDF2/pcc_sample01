<?php
    require_once "../models/database/conexao.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $error = [];
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $password = isset($_POST['password']) ? md5($_POST['password']) : '';

        $dbh = Conexao::getInstance();

        $query = "SELECT * FROM `pccsampledb`.`usuarios` WHERE nome = :nome AND `password` = :password";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
        if($row) {
            $_SESSION['usuario'] = [
                'nome' => $row['nome'],
                'perfil' => $row['perfil'],
            ];
            header('location: admin_usuario.php');
        } else {
            session_destroy();
            header('location: index.php?error=Usuário ou senha inválidos.');
        }
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
    </div>

</div>
<!--FIM POP LOGIN-->