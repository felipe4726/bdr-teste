<?php

namespace Bdr\Vendor;

class ListaWidgetPagination extends Widgets
{
    public $randomId;
    public $criteria;
    public $numRows;
    public $class;
    public $fields;

    public function __construct($randomId, $criteria, $numRows, $fields, $class = '')
    {
        $this->randomId = $randomId;
        $this->criteria = $criteria;
        $this->numRows = $numRows;
        $this->fields = $fields;
        $this->class = $class;
        $this->render();
    }

    public function render()
    {

        $view = new View($this->getViewPath() . 'listaWidgetPagination', array('randomId' => $this->randomId, 'criteria' => $this->criteria, 'numRows' => $this->numRows, 'campos' => $this->fields, 'class' => $this->class));
        $view->render();
    }
}