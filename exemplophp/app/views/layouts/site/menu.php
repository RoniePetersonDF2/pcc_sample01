<header class="main_header">
    <div class="main_header_content">
        <a href="#" class="logo">
            <img src="assets/img/logo.png" alt="Bem vindo ao projeto prático Html5 e Css3 Essentials" title="Bem vindo ao projeto prático Html5 e Css3 Essentials"></a>

        <nav class="main_header_content_menu">
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="">Blog</a></li>
                <li><a href="#escola">Escola</a></li>
                <li><a href="#contato">Contato</a></li>
                <?php 
                    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['perfil'] == 'ADM') {
                        echo "<li><a href='admin_usuario.php'>Admin</a></li>";
                        echo "<li><a href='logout.php'>Sair</a></li>";
                    } else {
                        echo "<li><a href='#' class='modal-link'>Login</a>";                
                    }
                ?>
            </ul>
        </nav>
    </div>
</header>