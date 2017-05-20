<?php

namespace Bdr\Vendor;

use Bdr\Sistema;

class Router
{

    public $controller = 'index';
    public $action = "index";
    public $parametros = array();
    public $acao = "index";
    public $next;

    public function __construct()
    {
        return $this->init();
    }

    public function init()
    {
        //Definição de URLs Amigáveis
        $urlRef = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        if (isset($urlRef[1]))
            $this->controller = str_replace("'", "", $urlRef[1]);
        if (isset($urlRef[2]))
            $this->action = str_replace("'", "", $urlRef[2]);

        for ($i = 3; $i < count($urlRef); $i = $i + 2) {
            if (count($urlRef) > $i + 1) {
                $this->parametros[$urlRef[$i]] = str_replace("'", "", $urlRef[$i + 1]);
            }
        }
        $this->setAcao($this->controller);
        $_REQUEST['acao'] = $this->controller;

        foreach ($_REQUEST as $key => $value) {
            $this->parametros[$key] = str_replace("'", '', $value);
        }

        $this->refresh();
        return $this;
    }

    /**
     * @return Router
     */
    public static function getRouter()
    {
        if (isset($_SESSION['router']))
            return $_SESSION['router'];
        return false;
    }

    public function refresh()
    {
        $_SESSION['router'] = $this;
    }

    public function setAcao($acao)
    {
        $this->acao = $acao;
        //$this->refresh();
    }

    public function addParametros($parametros = array())
    {
        $this->parametros = array_merge($this->parametros, $parametros);
        $this->refresh();
    }

    public function getParametro($key)
    {
        if (isset($this->parametros[$key]))
            return $this->parametros[$key];

        return false;
    }

    public function setNext($next)
    {
        $this->next = $next;
        $this->refresh();
    }

    public function redirect($acao, $text = "")
    {
        $this->setAcao($acao);
        if ($text != "")
            \Bdr\Sistema::app()->setFlash($text);

        Sistema::app()->end("<script>window.location = '" . $this->createUrl($this->acao) . "';</script>");
    }

    public function createUrl($acao, $action = "", $params = array())
    {
        $bp = "";
        foreach ($params as $key => $param) {
            $bp .= "/" . $key . "/" . $param;
        }
        if ($this->next)
            $bp .= "/goTo/" . $this->next;

        if ($acao == 'index')
            return \Bdr\Config::APPURL . $action . $bp;

        return \Bdr\Config::APPURL . $acao . '/' . $action . $bp;
    }

    /**
     * @param $caminho
     * @return string
     */
    public function createTemplateUrl($caminho)
    {
        return \Bdr\Config::APPURL . \Bdr\Sistema::app()->getTemplatePath() . $caminho;
    }

    public static function override($router)
    {
        $_SESSION['router'] = $router;
    }

}
