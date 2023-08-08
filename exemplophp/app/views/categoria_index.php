<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'ADM' && $_SESSION['usuario']['perfil'] != 'GER' )) {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }
 
    require_once "../controllers/CategoriaController.php";
    $categoriaController = new CategoriaController();
  
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # recupera o id do enviado por post para delete ou update.
        $id = (isset($_POST['id']) ? $_POST['id'] : 0);
        $categoriaController->apagar($id);
    }
    # chama controller para listar todas as categorias 
    $rows = $categoriaController->listarTodas();

    require_once 'layouts/site/header.php';
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
                title: 'Categorias',
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
                            onclick="javascript:window.location='categoria_create.php'"
                            >Nova categoria</button>
                    </div>
                    <article>
                        <header>

                            <table border="0" width="1300px" class="table">

                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
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
                                            <td><?=($row['status'] == '1' ? 'Ativo': 'Inativo')?></td>
                                            <td>
                                                <div style="display: flex;">
                                                    <a href="categoria_edit.php?id=<?=$row['id']?>" class="btn">Editar</a>&nbsp;
                                                    <form action="" method="post">
                                                        <input type="hidden" name="id" value="<?=$row['id']?>"/>
                                                        <button class="btn" 
                                                                name="botao" 
                                                                value="deletar"
                                                                onclick="return confirm('Deseja excluir o categoria?');"
                                                                >Apagar</button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>    
                                        <?php $count++;} } else {?>
                                    <tr><td colspan="3"><strong>Não existem categorias cadastradas.</strong></td></tr>
                                <?php }?>
                            </table>
                        </header>
                    </article>
                </div>
            </div>
    </main>    <!--FIM DOBRA PALCO PRINCIPAL-->
</body>
</html>