<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    # destroi todas sessões, se existirem.
    session_destroy();

    # redireciona para a pagina inicial.
    header('location: index.php');