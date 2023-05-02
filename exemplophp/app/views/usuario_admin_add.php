<?php 
    require_once 'layouts/site/header.php';
    session_start();

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
               <form action="" method="post">
                    
               </form>
            </section>
            </div>

    </main>
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>