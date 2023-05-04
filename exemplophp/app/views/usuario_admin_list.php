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

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
    # ordenando pelo campo perfil e nome.
    $query = "SELECT * FROM `pccsampledb`.`usuarios` ORDER BY perfil, nome";
    $stmt = $dbh->prepare($query);
    
    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    # destroi a conexao com o banco de dados.
    $dbh = null;
?>
<body>
    <?php require_once 'layouts/admin/menu.php'; ?>

    <main>
        <div class="main_opc">

            <div class="main_stage">
                <div class="main_stage_content">

                    <article>
                        <header>

                            <table border="0" width="1300px" class="table">

                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfil</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                                
                                <?php
                                    # verifica se os dados existem na variavel $row.
                                    # se existir faz um loop nos dados usando foreach.
                                    # cria uma variavel $count para contar os registros da tabela.
                                    # se não existir vai para o else e imprime uma mensagem.
                                    if($rows) {
                                        $count = 1;
                                        foreach ($rows as $row) {?>
                                        <tr>
                                            <td><?=$count?></td>
                                            <td><?=$row['nome']?></td>
                                            <td><?=$row['email']?></td>
                                            <td><?=$row['perfil']?></td>
                                            <td><?=($row['status'] == '1' ? 'Ativo': 'Inativo')?></td>
                                            <td><button class="btn">Editar</button>&nbsp;<button class="btn">Apagar</button></td>
                                        </tr>    
                                        <?php $count++;} } else {?>
                                    <tr><td colspan="6"><strong>Não existem usuários cadastrados.</strong></td></tr>
                                <?php }?>
                            </table>

                        </header>
                    </article>

                </div>
            </div>

    </main>
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>