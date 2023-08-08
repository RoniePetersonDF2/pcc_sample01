<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();

    # inclui os arquivos header, menu e login.
    require_once 'layouts/site/header.php';
    require_once 'layouts/site/menu.php';
    require_once 'login.php';
    require_once '../models/ArtigoDAO.php';
    // require_once '../models/dao/CategoriaDAO.php';
    require_once '../controllers/CategoriaController.php';
    
    $categoriaController = new CategoriaController();
    $categorias = $categoriaController->listarTodas();
    # cria variavel que recebe parametro da categoria
    # se foi passado via get quando o campo select do
    # formulario é modificado.    
    $filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
    $filtroTitulo = isset($_GET['filtro']) ? $_GET['filtro'] : null;
    
    // $categoriaDAO = new CategoriaDAO();
    // $categorias = $categoriaDAO->listarTodasCategorias();
    
    $artigoDAO = new ArtigoDAO();
    $artigos = $artigoDAO->listarArtigosPorCategoriaOuTitulo($filtroCategoria, $filtroTitulo);
    // echo '<pre>'; var_dump($artigos); exit;
    ?>

<!--DOBRA PALCO PRINCIPAL-->

<!--1ª DOBRA-->

<main>
    
    <?php
        # verifca se existe uma mensagem de erro enviada via GET.
        # se sim, exibe a mensagem enviada no cabeçalho.
        if(isset($_GET['error']) || isset($_GET['success']) ) { ?>
            <script>
                Swal.fire({
                icon: '<?php echo (isset($_GET['error']) ? 'error' : 'success');?>',
                title: 'Pcc Sample',
                text: '<?php echo (isset($_GET['error']) ? $_GET['error']: $_GET['success']); ?>',
                })
            </script>
    <?php } ?>
    <div class="main_cta">
        <article class="main_cta_content">
            <div class="main_cta_content_spacer">
                <header>
                    <h1>Aqui você aprende tudo o que é necessário<br> para trabalhar como um Webmaster FullStack
                    </h1>
                </header>
                <p>Domine o HTML e o Css3 de uma vez por todas</p>
                <p><a href="#" class="btn">Saiba Mais</a></p>
            </div>
        </article>
    </div>
    <!--FIM 1ª DOBRA-->

    <!--INICIO SESSÃO SESSÃO DE ARTIGOS-->
    <section class="main_blog">
        <header class="main_blog_header">
            <h1 class="icon-blog">Nosso Últimos Artigos</h1>
            <p>Aqui você encontra os artigos necessários para auxiliar na sua caminhada web.</p>
            <!-- cria o campo select com os dados da consulta 
                realizada na tabela de categorias -->
            <select name="categorias" onchange="filtroPorCategoria(this.value);">
                <option value="0">Todas as categorias</option>
                <?php 
                    foreach($categorias as $categoria) {
                        echo "<option value='" 
                        . $categoria['id'] ."'"
                        .($filtroCategoria == $categoria['id'] ? ' selected': '') . ">" 
                        . $categoria['nome'] 
                        . "</option>";
                    }
                ?>
            </select>

            <section class="novo__form__filtar">
                <form action="" method="get">
                    <input 
                        type="text" 
                        name="filtro" 
                        placeholder="Informe o nome do artigo a ser buscado." 
                        class="novo__form__input__filtar"
                        value="<?= isset($_GET['filtro'])?$_GET['filtro']:'';?>" 
                        autofocus>
                    <button type="submit" class="btn novo__form__btn__cadastrar">Buscar</button>
                </form>
            </section>
        
        </header>
        
        <?php if($artigos) { foreach ($artigos as $row){ ?>
            <article>
                <a href="artigo_show.php?id=<?=$row['id'];?>">
                     <?php
                        $imagem = $row['imagem'] == '' ? "semimagem.jpg" : $row['imagem'];
                        if($row['imagem_externa'] == '0') {
                            $imagem = "assets/img/artigos/" . $imagem;
                        }                          
                     ?>   
                    <img src="<?=$imagem?>" width="250px" height="250px" alt="<?=$row['titulo']?>" title="<?=$row['titulo']?>">
                </a>
                <p><a href="#" class="category"><?=$row['titulo']?></a> || <a href="#" class="category"><?=$row['categoria']?></a></p>
                <h2 class="title" title="<?=$row['texto']?>">
                    <?php 
                        $max = 350;
                        echo substr($row['texto'], 1, (strlen($row['texto']) <= $max) ? strlen($row['texto']): $max) . '(...)'; 
                    ?>
                </h2>
            </article>
        <?php } } else { echo "<p>Não existem artigos cadastrados</p>"; } ?>
    </section>

    <!--FIM SESSÃO SESSÃO DE ARTIGOS-->

    <?php 
        // require_once 'layouts/site/secao_optin.php';
        // require_once 'layouts/site/secao_cursos.php';
        // require_once 'layouts/site/secao_review.php';
        // require_once 'layouts/site/secao_dobra_escola.php';
        // require_once 'layouts/site/secao_tutor.php';
        // require_once 'layouts/site/secao_conteudo_exclusivo.php';
    ?>
   
    
    
</main>
<script>
    function filtroPorCategoria(valorId) {
        window.location.href="index.php?categoria=" + valorId;
    }
</script>

<!-- inclui o arquivo de rodape do site -->
<?php require_once 'layouts/site/footer.php'; ?>