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
        $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
        $texto = isset($_POST['texto']) ? $_POST['texto'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        
        $imagem_externa = ($_POST['tipoImagem'] == '1'? true: false);
        $imagemName  = isset($_POST['imagem_externa']) ? $_POST['imagem_externa'] : '';
        
        # verifica se a imagem a ser cadastrada é interna? Se sim, entra no if.
        if($imagem_externa == false) {
            # definie o caminho onde sera gravado o arquivo.
            $uploaddir = __DIR__ . '/assets/img/artigos/';
            $imagemName = basename($_FILES['imagem_interna']['name']);
            $uploadfile = $uploaddir . $imagemName;
            
            # verifica se o diretorio existe? Se não existir cria um novo.
            if(!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777);
            }
            # recebe o arquivo a ser gravado e inserido no diretorio criado. 
            # Se sim, gravano diretorio. Se não, limpa o nome da variavel que
            # sera usada no banco de dados.
            if(!move_uploaded_file($_FILES['imagem_interna']['tmp_name'], $uploadfile)){
                $imagemName  = '';
            }
        }
        
        $categoriaId = isset($_POST['categoria']) ? $_POST['categoria'] : '1';
        $usuarioId = $_SESSION['usuario']['id'];
        // echo '<pre>'; var_dump($usuario); exit;
        
        # cria um comando SQL para adicionar valores na tabela categorias 
        $query = "INSERT INTO `pccsampledb`.`artigos` 
                (`titulo`, `texto`, `status`, `imagem`, `imagem_externa`, `categoria_id`, `usuario_id`)
                VALUES (:titulo, :texto, :status, :imagem, :imagem_externa, :categoria_id, :usuario_id)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':texto', $texto);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':imagem', $imagemName);
        $stmt->bindParam(':imagem_externa', $imagem_externa);
        $stmt->bindParam(':categoria_id', $categoriaId);
        $stmt->bindParam(':usuario_id', $usuarioId);

        # executa o comando SQL para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: artigo_index.php?success=Artigo inserido com sucesso!');
        } else {
            echo '<pre>';var_dump($stmt->errorInfo()); exit;
            header('location: artigo_create.php?error=Erro ao inserir artigo!');
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
                        <select name="status" <?php echo ($_SESSION['usuario']['perfil'] == 'EDI') ? 'disabled': '';?>>
                            <option value="0">Em edição</option>
                            <option value="1">Publicado</option>
                        </select>
                    </div>
                    <br><br>
                    <div>
                        <label for="tipoImagem">Tipo de Imagem</label><br>
                        <select name="tipoImagem" onchange="changeImagem(this);">
                            <option value="0">Intena</option>
                            <option value="1">Externa</option>
                        </select>
                    </div>
                    <br><br>
                    <div>
                        <label for="imagem">Imagem</label><br>
                        <input type="file" name="imagem_interna" id="imagem_interna">
                        <input type="text" name="imagem_externa" id="imagem_externa" style="display:none;">
                        </div>
                    <br><br>
                    <input type="submit" value="Salvar" name="salvar">
               </form>
            </section>
            </div>
    </main>
    
    <script>        
        function changeImagem(e) {
            const listaValue = e.value;
            const imagemExterna = document.getElementById('imagem_externa');
            const imagemInterna = document.getElementById('imagem_interna');
            
            imagemExterna.style.display = "none";
            imagemInterna.style.display = "";
            if(listaValue == 1) {
                imagemExterna.style.display = "";
                imagemInterna.style.display = "none";
                imagemInterna.value = "";
            }
        }
    </script>
</body>
</html>
