<?php
session_start();
    
class Seguranca {

    # verifica se usuário logado tem o perfil administrador
    public static function isAdministrador()
    {
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['perfil'] == 'ADM')  {
            return true;
        } 
        return false;
    }

    # verifica se usuário logado não tem o perfil usuário
    public static function isNotUsuario()
    {
        if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] != 'USU')) {
            return true;
        } 
        return false;
    }

    # verifica se usuário está logado 
    public static function isLogado()
    {
        if(isset($_SESSION['usuario'])) {
            return true;
        } 
        return false;
    }

    public static function isAdministradorOuGerente() 
    {
        if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'ADM' && $_SESSION['usuario']['perfil'] != 'GER' )) {
            header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
            exit;
        }        
    }

    public static function isAdministradorOuGerenteOuEditor()
    {
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
    }

}
