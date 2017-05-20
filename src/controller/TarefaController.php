<?php

namespace Bdr\Controller;

class TarefaController extends \Bdr\Vendor\Controller implements \Bdr\Vendor\ControllerInterface
{
    public $viewPath;
    public $noHeader = array('jsonList', 'jsonEdit', 'jsonDelete');

    public function getViewPath()
    {
        if (!isset($this->viewPath))
            $this->viewPath = dirname(__FILE__) . '/../../' . \Bdr\Sistema::app()->getTemplatePath() . 'view/tarefa/';

        return $this->viewPath;
    }

    public function index()
    {
        return;
    }


    public function lista()
    {
        if (\Bdr\Sistema::app()->webUser) {
            $tarefa = new \Bdr\Model\Tarefa();
            $criteria = new \Bdr\Vendor\Criteria();
            $criteria->limit = 20;
            if (\Bdr\Vendor\Router::getRouter()->getParametro('Tarefa'))
                $tarefa->setAttr(\Bdr\Vendor\Router::getRouter()->getParametro('Tarefa'));

            if ($this->isAjax()) {
                $campos = json_decode(\Bdr\Vendor\Router::getRouter()->getParametro('campos'), true);
                $lista = new \Bdr\Vendor\ListaWidget($tarefa, $campos, $criteria);
                $lista->render();
            } else {
                $view = new \Bdr\Vendor\View($this->getViewPath() . 'lista', array(
                        'tarefas' => $tarefa,
                        'criteria' => $criteria
                    )
                );
                $view->render();
            }
        }
    }

    public function jsonEdit(){
        if (\Bdr\Sistema::app()->webUser) {
            if(\Bdr\Vendor\Router::getRouter()->getParametro('id')){
                if(!$tarefa = \Bdr\Model\Tarefa::model()->findByPk(\Bdr\Vendor\Router::getRouter()->getParametro('id')))
                    \Bdr\Sistema::app()->end(json_encode(array('success' => false, 'message' => 'Tarefa não encontrada')));
            }else{
                $tarefa = new \Bdr\Model\Tarefa();
            }
            if(\Bdr\Vendor\Router::getRouter()->getParametro('Tarefa')) {
                $tarefa->setAttr(\Bdr\Vendor\Router::getRouter()->getParametro('Tarefa'));
                if($tarefa->save()){
                    \Bdr\Sistema::app()->end(json_encode(array('success' => true, 'tarefa' => $tarefa)));
                }else{
                    \Bdr\Sistema::app()->end(json_encode(array('success' => false, 'message' => $tarefa->getErrorText())));
                }
            }
        }
    }

    public function jsonDelete(){
        if (\Bdr\Sistema::app()->webUser) {
            if(\Bdr\Vendor\Router::getRouter()->getParametro('id')){
                if(!$tarefa = \Bdr\Model\Tarefa::model()->findByPk(\Bdr\Vendor\Router::getRouter()->getParametro('id')))
                    \Bdr\Sistema::app()->end(json_encode(array('success' => false, 'message' => 'Tarefa não encontrada')));

                if($tarefa->delete()){
                    \Bdr\Sistema::app()->end(json_encode(array('success' => true)));
                }else{
                    \Bdr\Sistema::app()->end(json_encode(array('success' => false, 'message' => $tarefa->getErrorText())));
                }
            }
        }
    }

    public function jsonList(){
        if (\Bdr\Sistema::app()->webUser) {
            $tarefa = new \Bdr\Model\Tarefa();
            $criteria = new \Bdr\Vendor\Criteria();
            if(\Bdr\Vendor\Router::getRouter()->getParametro('page'))
                $criteria->page = \Bdr\Vendor\Router::getRouter()->getParametro('page');
            if(\Bdr\Vendor\Router::getRouter()->getParametro('order'))
                $criteria->order = \Bdr\Vendor\Router::getRouter()->getParametro('order');

            if (\Bdr\Vendor\Router::getRouter()->getParametro('Tarefa')) {
                $tarefa->setAttr(\Bdr\Vendor\Router::getRouter()->getParametro('Tarefa'));
            }
            \Bdr\Sistema::app()->end(json_encode(array('success' => true, 'tarefa' => $tarefa->search())));
        }
    }

}