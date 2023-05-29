<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/admin/header.php';
    require_once "../database/conexao.php";

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || 
        ($_SESSION['usuario']['perfil'] != 'ADM' 
            && $_SESSION['usuario']['perfil'] != 'GER' 
            && $_SESSION['usuario']['perfil'] != 'EDI'
            )) {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();
    
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (nome, status, tipo) para armazenar os dados passados via método POST.
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'ART';
        

        # cria um comando SQL para adicionar valores na tabela categorias 
        $query = "INSERT INTO `pccsampledb`.`categorias` (`nome`,`status`, `tipo`)
                    VALUES (:nome, :status, :tipo)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':tipo', $tipo);

        # executa o comando SQL para inserir o resultado.
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

    # cria uma consulta banco de dados buscando todos os dados da tabela  
    # ordenando pelo campo nome.
    $query = "SELECT * FROM `pccsampledb`.`categorias` ORDER BY nome";
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
                        title: 'Artigos',
                        text: '<?=$_GET['error'] ?>',
                        })
                    </script>
            <?php } ?>
            <section class="novo__form__section">
                <div class="novo__form__titulo">
                    <h2>Novo Artigo</h2>
                </div>
                <form action="" method="post" class="novo__form" enctype="multipart/form-data" >
                    <input type="hidden" name="usuarioId" value="<?=$_SESSION['usuario']['id'];?>">
                    <div>
                        <label for="categoria">Categoria</label><br>
                        <select name="categoria">
                            <?php
                                foreach($rows as $row) {
                                    echo "<option value='". $row['id']. "'>" . $row['nome']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <br><br>
                    <div>
                        <label for="nome">Título</label><br>
                        <input type="text" name="titulo" placeholder="Informe o título do artigo."  required>
                    </div>
                    <br><br>
                    <div>
                        <label for="nome">Texto</label><br>
                        <textarea name="texto"cols="30" rows="15" placeholder="Informe o texto do artigo" required></textarea>
                    </div>
                    <br><br>
                    <div>
                        <label for="status">Status</label><br>
                        <select name="status" <?php ($_SESSION['usuario']['perfil'] == 'EDI') ? 'disabled': '';?>>
                            <option value="0">Em edição</option>
                            <option value="1">Publicado</option>
                        </select>
                    </div>
                    <br><br>
                    <div>
                    <label for="imagem">Imagem</label><br>
                        <input type="file" name="imagem">
                    </div>
                    <br><br>
                    <input type="submit" value="Salvar" name="salvar">
               </form>
            </section>
            </div>
    </main>    
</body>
</html>
