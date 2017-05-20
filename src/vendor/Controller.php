<?php

namespace Bdr\Vendor;

class Controller
{

    public $responseGiven = false;
    public $isAjax = false;
    public $viewPath;
    public $runInController = false;
    public $noHeader = array();

    public function init($controladora = false, $acao = false)
    {
        $this->viewPath = dirname(__FILE__) . '/../../' . \Bdr\Sistema::app()->getTemplatePath() . 'view/';
        if (!$controladora)
            $controladora = Router::getRouter()->controller;
        if (!$acao)
            $acao = Router::getRouter()->action;
        $controller = "\Bdr\Controller\\" . ucfirst($controladora) . 'Controller';
        if (class_exists($controller)) {
            $this->runInController = true;
            $controller = new $controller();
            if (method_exists($controller, $acao) && is_callable(array($controller, $acao))) {
                $this->call($controller, $acao);
            } else {
                $this->notFound();
            }
        } elseif ($controladora == 'index' || !($this->runInController)) {
            if (!$this->responseGiven()) {
                $controller = new \Bdr\Controller\ContentController();
                $controller->init();
            }
        } else {
            $this->notFound();
        }
        $this->renderMessages();
    }

    public function call($controller, $acao)
    {
        if (!$this->isAjax() && !in_array($acao, $controller->noHeader))
            $this->getHeader();
        call_user_func(array($controller, $acao));
        if (!$this->isAjax() && !in_array($acao, $controller->noHeader))
            $this->getFooter();
    }

    public function getHeader()
    {
        $busca = "";
        if (Router::getRouter()->getParametro('keywords'))
            $busca = Router::getRouter()->getParametro('keywords');
        $view = new \Bdr\Vendor\View(dirname(__FILE__) . '/../../' . \Bdr\Sistema::app()->getTemplatePath() . 'view/' . 'header', array('busca' => $busca));
        $view->render();
    }

    public function getFooter()
    {
        $view = new \Bdr\Vendor\View(dirname(__FILE__) . '/../../' . \Bdr\Sistema::app()->getTemplatePath() . 'view/' . 'footer', array());
        $view->render();
    }

    public function renderMessages()
    {
        foreach (\Bdr\Sistema::app()->errors as $errorMsg) {
            $view = new \Bdr\Vendor\View($this->viewPath . 'errorMessage', array('errorMsg' => $errorMsg));
            $view->render();
        }
        foreach (\Bdr\Sistema::app()->flashMessage as $flashMsg) {
            $view = new \Bdr\Vendor\View($this->viewPath . 'flashMessage', array('flashMsg' => $flashMsg));
            $view->render();
        }
        \Bdr\Sistema::app()->clearMessages();
    }

    public function notFound()
    {
        $this->redirect('content', 'notFound');
    }

    public function responseGiven()
    {
        return $this->responseGiven;
    }

    public function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            $this->isAjax = true;

        return $this->isAjax;
    }

    public function redirect($controladora = 'content', $acao = 'index')
    {
        Router::getRouter()->setAcao($acao);
        $this->init($controladora, $acao);
    }

}