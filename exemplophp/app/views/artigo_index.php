<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/site/header.php';
    require_once "../database/conexao.php";

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] == 'USU' && $_SESSION['usuario']['perfil'] != 'GER' )) {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
    # ordenando pelo campo perfil e nome.
    $query = "SELECT * FROM `pccsampledb`.`artigos` ORDER BY status, data_publicacao desc, status";
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
    <?php
        # verifca se existe uma mensagem de erro enviada via GET.
        # se sim, exibe a mensagem enviada no cabeçalho.
        if(isset($_GET['error']) || isset($_GET['success']) ) { ?>
            <script>
                Swal.fire({
                icon: '<?php echo (isset($_GET['error']) ? 'error' : 'success');?>',
                title: 'Artigos',
                text: '<?php echo (isset($_GET['error']) ? $_GET['error']: $_GET['success']); ?>',
                })
            </script>
        <?php } ?>
        <div class="main_opc">

            <div class="main_stage">
                <div class="main_stage_content">
                    <div>
                        <button class="btn"
                            style="min-height: 40px; margin-bottom: 10px;"
                            onclick="javascript:window.location='artigo_create.php'"
                            >Novo Artigo</button>
                    </div>
                    <article>
                        <header>

                            <table border="0" width="1300px" class="table">

                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Data Publicação</th>
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
                                            <td><?=$row['titulo']?></td>
                                            <td><?=date_format(date_create($row['data_publicacao']),'d/m/Y H:i')?></td>
                                            <td><?=($row['status'] == '1' ? 'Publicado': 'Em Edição')?></td>
                                            <td>
                                                <div style="display: flex;">
                                                    <a href="artigo_edit.php?id=<?=$row['id']?>" class="btn">Editar</a>&nbsp;
                                                    <form action="" method="post">
                                                        <input type="hidden" name="id" value="<?=$row['id']?>"/>
                                                        <button class="btn" 
                                                                name="botao" 
                                                                value="deletar"
                                                                onclick="return confirm('Deseja excluir o artigo?');"
                                                                >Apagar</button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>    
                                        <?php $count++;} } else {?>
                                    <tr><td colspan="4"><strong>Não existem artigos cadastrados.</strong></td></tr>
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