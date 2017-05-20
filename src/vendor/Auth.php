<?php

namespace Bdr\Vendor;

class Auth
{

    CONST LOGADO = 0;
    CONST LOGIN_OK = 1;
    CONST LOGIN_FAIL = 2;
    CONST LOGIN_VAZIO = 3;

    /**
     * Valida Usuario
     * Valida um usuario e atualiza $_Session com os dados
     * Retorna true ou false dependendo de sucesso em validar
     * @return int
     */
    public static function auth()
    {
        if (!isset($_SESSION['ulogin']) || Router::getRouter()->acao == "login") {

            //Login com usuário e senha via POST.
            if (Router::getRouter()->getParametro('ulogin') && Router::getRouter()->getParametro('usenha')) {
                if (!$user = Auth::valida_user(Router::getRouter()->getParametro('ulogin'), Router::getRouter()->getParametro('usenha'))) {
                    return Auth::LOGIN_FAIL;
                } else {
                    setcookie("ulogin", $user->login, Time() + 60 * 60 * 12, '/'); // 12 horas
                    setcookie("usenha", $user->pass, Time() + 60 * 60 * 12, '/');
                    return Auth::LOGIN_OK;
                }
            }

            //Login via Cookie se tiver
            if (isset($_COOKIE['ulogin']) && isset($_COOKIE['usenha'])) {
                if (!Auth::valida_user($_COOKIE['ulogin'], $_COOKIE['usenha'])) {
                    setcookie("ulogin", "", time() - 60 * 60 * 24, '/');
                    setcookie("usenha", "", time() - 60 * 60 * 24, '/');
                } else {
                    // ok, revive os cookies
                    setcookie("ulogin", $_COOKIE['ulogin'], time() + 60 * 60 * 12, '/'); // 12 horas
                    setcookie("usenha", $_COOKIE['usenha'], time() + 60 * 60 * 12, '/');
                    return Auth::LOGIN_OK;
                }
            }
            return Auth::LOGIN_VAZIO;
        } else {
            \Bdr\Sistema::app()->setLoggedIn($_SESSION['ulogin']);
            return Auth::LOGADO;
        }
    }

    /**
     * @param $login
     * @param $senha
     * @return Usuario|bool
     */
    public static function valida_user($login, $senha)
    {
        $sql = "( email='" . $login . "' OR login = '" . $login . "' )";
        if (strpos(" " . $senha, '$P$B') > 0) {
            $sql .= " AND pass = '" . $senha . "'";
            if ($usuario = \Bdr\Model\Usuario::model()->find(array('condition' => $sql))) {
                Auth::startSession($usuario);
                return $usuario;
            }
        } else {
            if ($usuario = \Bdr\Model\Usuario::model()->find(array('condition' => $sql))) {
                $wp_hasher = new \Bdr\Ext\PasswordHash(8, true);
                $check = $wp_hasher->CheckPassword(trim($senha), $usuario->pass);
                if ($check === true) {
                    Auth::startSession($usuario);
                    return $usuario;
                }
            }
        }
        \Bdr\Sistema::app()->setError("Usuário ou senha não correspondem");
        return false;

    }

    /**
     * @param \Bdr\Model\Usuario $usuario
     * @return bool
     */
    public static function startSession($usuario)
    {
        $_SESSION ['ulogin'] = $usuario;
        \Bdr\Sistema::app()->setLoggedIn($usuario);
        return true;

    }

}