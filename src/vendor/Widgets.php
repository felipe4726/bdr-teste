<?php

namespace Bdr\Vendor;

class Widgets
{
    public $viewPath;

    public function getViewPath()
    {
        if (!isset($this->viewPath))
            $this->viewPath = dirname(__FILE__) . '/../../' . \Bdr\Sistema::app()->getTemplatePath() . 'view/widgets/';

        return $this->viewPath;
    }
}
