<?php

namespace Bdr\Controller;

use Bdr\Sistema;

class ContentController extends \Bdr\Vendor\Controller implements \Bdr\Vendor\ControllerInterface
{

    public $viewPath;

    public function getViewPath()
    {
        if (!isset($this->viewPath))
            $this->viewPath = dirname(__FILE__) . '/../../' . \Bdr\Sistema::app()->getTemplatePath() . 'view/content/';

        return $this->viewPath;
    }

    public function index()
    {
        $view = new \Bdr\Vendor\View($this->getViewPath() . 'index', array());
        $view->render();
    }

    public function init($controladora = false, $acao = false)
    {
        if (!$acao)
            $acao = \Bdr\Vendor\Router::getRouter()->controller;
        if (method_exists($this, $acao) && is_callable(array($this, $acao))) {
            $this->call($this, $acao);
        } else {
            $this->notFound();
        }
    }

    public function notFound()
    {
        $view = new \Bdr\Vendor\View($this->getViewPath() . '404');
        $view->render();
    }

    public function notDisponible($mensagem = null)
    {
        $view = new \Bdr\Vendor\View($this->getViewPath() . '403', array('mensagem' => $mensagem));
        $view->render();
    }

    public function login()
    {
        if (\Bdr\Sistema::app()->webUser) {
            \Bdr\Vendor\Router::getRouter()->redirect('index');
        } else {
            $view = new \Bdr\Vendor\View($this->getViewPath() . 'login', array());
            $view->render();
        }
    }

    public function logout()
    {
        if (\Bdr\Sistema::app()->webUser) {
            Sistema::app()->logout();
        }
        \Bdr\Vendor\Router::getRouter()->redirect('index');
    }

    public function senha()
    {
        if (!\Bdr\Sistema::app()->webUser) {
            $view = new \Bdr\Vendor\View($this->getViewPath() . 'senha', array('controller' => $this));
            $view->render();
        } else {
            \Bdr\Vendor\Router::getRouter()->redirect('index', 'Você já está logado', true);
        }
    }

    public function mudarSenha()
    {
        if (\Bdr\Sistema::app()->webUser) {
            $view = new \Bdr\Vendor\View($this->getViewPath() . 'mudarSenha', array('controller' => $this));
            $view->render();
        } else {
            \Bdr\Sistema::app()->setError('Você não está logado');
            \Bdr\Vendor\Router::getRouter()->redirect('index');
        }
    }

    public function lostPassword()
    {
        $condition = false;
        if ((\Bdr\Vendor\Router::getRouter()->getParametro('mail') != "") && ($mail = \Bdr\Vendor\Router::getRouter()->getParametro('mail')))
            $condition = "email ='" . $mail . "'";
        if ($condition) {
            if ($usuario = \Bdr\Model\Usuario::model()->find(array('condition' => $condition))) {
                $usuario->auth = GUIDv4();
                if ($usuario->save()) {
                    \Bdr\Vendor\Mail::model()->lostpass($usuario);
                    \Bdr\Vendor\Log::evento($usuario->display_name . ' Pediu para Recuperar a senha a partir do IP:' . $_SERVER['REMOTE_ADDR'] . " Enviado No email: " . $usuario->email);
                    \Bdr\Sistema::app()->setFlash("Um email foi enviado para a sua caixa de mensagem. No email: " . $usuario->email);
                } else {
                    echo 'Sistema teve problema ao tentar salvar recuperação de Senha do usuário: ' . $usuario->display_name . '. ' . $usuario->getErrorText();
                    \Bdr\Sistema::app()->setError("Não foi possível salvar seu link de recuperação. Por favor entre em contato conosco.");
                }
            }
        } else {
            \Bdr\Sistema::app()->setError("Usuário não encontrado");
        }
        $this->senha();
    }

    public function retrievePassword()
    {
        if ($mail = \Bdr\Vendor\Router::getRouter()->action) {
            if ($auth = \Bdr\Vendor\Router::getRouter()->getParametro('token')) {
                if ($usuario = \Bdr\Model\Usuario::model()->find(array('condition' => "user_email ='" . $mail . "'"))) {
                    if ($usuario->auth == $auth) {
                        \Bdr\Vendor\Log::evento($usuario->login . ' Recuperou a senha.');
                        \Bdr\Sistema::app()->setFlash('Você está logado, entre no seu cadastro e altere sua senha.');
                        \Bdr\Sistema::app()->setLoggedIn($usuario);
                    } else {
                        \Bdr\Vendor\Log::evento('Link inválido para a recuperação de senha.');
                        \Bdr\Sistema::app()->setError('Esse link não é válido para a recuperação de senha da sua conta.');
                    }
                } else {
                    \Bdr\Vendor\Log::evento('Email não encontrado.');
                    \Bdr\Sistema::app()->setError('Email não encontrado.');
                }
            } else {
                \Bdr\Sistema::app()->setError('Link está incompleto, tente copiar e colar o link do seu email na barra de endereço do navegador.');
            }
        }
        \Bdr\Vendor\Router::getRouter()->redirect('index');
    }


}