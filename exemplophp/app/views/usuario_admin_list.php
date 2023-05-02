<?php
    session_start();
    require_once 'layouts/site/header.php';
    require_once "../models/database/conexao.php";

    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    $dbh = Conexao::getInstance();

    $query = "SELECT * FROM `pccsampledb`.`usuarios` ORDER BY nome";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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