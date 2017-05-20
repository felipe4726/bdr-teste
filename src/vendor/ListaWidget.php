<?php

namespace Bdr\Vendor;

class ListaWidget extends Widgets
{
    public $model;
    public $fields = array();
    public $criteria;

    /**
     * @param Model[] $model
     * @param array $fields
     * @param Criteria $criteria
     */
    public function __construct($model, $fields = array(array('label' => '', 'attribute' => '', 'filter' => '', 'selectOptions' => '', 'data' => '', 'class' => '')), $criteria)
    {
        $this->model = $model;
        $this->fields = $fields;
        $this->criteria = $criteria;
    }

    public function render()
    {

        if (Router::getRouter()->getParametro('page'))
            $this->criteria->page = Router::getRouter()->getParametro('page');

        if (Router::getRouter()->getParametro('order'))
            $this->criteria->order = Router::getRouter()->getParametro('order');

        if (Router::getRouter()->getParametro('limit')) {
            $this->criteria->limit = Router::getRouter()->getParametro('limit');
        } else {
            $this->criteria->limit = 20;
        }

        $view = new View($this->getViewPath() . 'listaWidget', array('model' => $this->model, 'campos' => $this->fields, 'criteria' => $this->criteria));
        $view->render();
    }

}