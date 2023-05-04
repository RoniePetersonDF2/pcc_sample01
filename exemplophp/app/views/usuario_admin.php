<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header
    require_once 'layouts/site/header.php';
    
    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }
?>
<body>
    <?php require_once 'layouts/admin/menu.php';?>
    
    <main>
        <div class="main_opc">

            <section class="main_course" id="escola">
                <header class="main_course_header">

                </header>
                <div class="main_course_content">
                    <article>
                        <h2 align="center">Cadastrar dados</h2>
                        <header>
                            
                            <p align="center">
                                <a href="usuario_admin_add.php"><img src="assets/img/cadastro.png" width="200"></a></p>
                               
                        </header>
                    </article>
                    <article>
                        <h2 align="center">Alterar dados</h2>
                        <header>
                            
                            <p align="center"><a href="usuario_admin_list.php"><img src="assets/img/listar.png" width="350"></a></p>
                            
                        </header>
                    </article>

                </div>
                </article>
            </section>
            </div>

    </main>
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>