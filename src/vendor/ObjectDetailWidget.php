<?php

namespace Bdr\Vendor;

class ObjectDetailWidget extends Widgets
{
    public $viewPath;
    public $model;
    public $fields = array();

    /**
     * @param Model[] $model
     * @param array $fields
     */
    public function __construct($model, $fields = array(array('label' => '', 'attribute' => '', 'form' => '', 'data' => '', 'class' => '')))
    {
        $this->model = $model;
        $this->fields = $fields;
    }

    public function render()
    {
        $view = new View($this->getViewPath() . 'objectDetailWidget', array('model' => $this->model, 'campos' => $this->fields));
        $view->render();
    }

}