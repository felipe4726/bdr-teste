<?php

namespace Bdr\Controller;

class UsuarioController extends \Bdr\Vendor\Controller implements \Bdr\Vendor\ControllerInterface
{
    public $viewPath;
    public $noHeader = array('logout');

    public function getViewPath()
    {
        if (!isset($this->viewPath))
            $this->viewPath = dirname(__FILE__) . '/../../' . \Bdr\Sistema::app()->getTemplatePath() . 'view/usuario/';

        return $this->viewPath;
    }

    public function index()
    {
        $view = new \Bdr\Vendor\View($this->getViewPath() . 'index', array(
                'titulo' => "um teste",
            )
        );
        $view->render();
    }

    public function login()
    {
        $this->redirect('content', 'login');
    }

    public function logout()
    {
        \Bdr\Sistema::app()->logout();
        \Bdr\Vendor\Router::getRouter()->redirect('login');
    }


}