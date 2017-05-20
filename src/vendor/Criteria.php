<?php

namespace Bdr\Vendor;

class Criteria
{

    public $select;
    public $join;
    public $condition;
    public $order = "";
    public $limit;
    public $group;
    public $page = 1;

    public function __construct($object = null)
    {
        if (!empty($object)) {
            $object = (array)$object;
            $this->setAttr($object);
        }
        return $this;
    }

    public function setAttr($attributes = array())
    {
        foreach ($attributes as $key => $value) {
            if (property_exists(get_class($this), $key))
                $this->$key = $value;
        }
        return $this;
    }

    public function addCondition($condition, $concat = "AND")
    {
        if ($this->condition != "") {
            $this->condition .= " " . $concat . " ";
        }
        $this->condition .= $condition;
    }

    public function compare($attribute, $value, $like = false)
    {
        if ($this->condition != "") {
            $this->condition .= ' AND ';
        }
        $this->condition .= $attribute;
        if ($like) {
            $this->condition .= " LIKE '%" . $value . "%'";
        } else {
            $this->condition .= " = '" . $value . "'";
        }
    }

    public function modelCompare($model, $attribute, $concat = "AND", $tableIdentifier = "t", $like = false)
    {
        if (isset($model->$attribute) && $model->$attribute != '') {
            if ($this->condition != "") {
                $this->condition .= " " . $concat . " ";
            }
            $this->condition .= $attribute;
            if ($like) {
                $this->condition .= " LIKE '%" . $model->$attribute . "%'";
            } else {
                $this->condition .= " = '" . $model->$attribute . "'";
            }
        }
    }

    public function standardProcedure()
    {
        $this->pagination();
        $this->order();
    }

    public function order($defineNewOrder = false)
    {
        if ($defineNewOrder)
            Router::getRouter()->parametros['order'] = $defineNewOrder;

        if ($order = Router::getRouter()->getParametro('order') && empty($this->order)) {
            $this->order = $order;
        }
    }

    public function pagination($defineNewPage = false, $defineNewLimit = false)
    {
        if ($defineNewPage)
            Router::getRouter()->parametros['page'] = $defineNewPage;

        if ($defineNewLimit)
            Router::getRouter()->parametros['limit'] = $defineNewLimit;

        if (!isset($this->page) && $page = Router::getRouter()->getParametro('page'))
            $this->page = $page;

        if (!isset($this->page))
            $this->page = 1;

        if ($limit = Router::getRouter()->getParametro('limit'))
            $this->limit = $limit;

    }

}