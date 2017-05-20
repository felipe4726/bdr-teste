<?php

namespace Bdr\Vendor;

class View implements ViewInterface
{
    const DEFAULT_TEMPLATE = "default.php";

    protected $template = self::DEFAULT_TEMPLATE;
    protected $fields = array();
    protected $breadCrumbs = array();

    /**
     * @param null $template
     * @param array $fields
     */
    public function __construct($template = null, array $fields = array(), array $breadCrumbs = array())
    {
        if ($template !== null) {
            $this->setTemplate($template);
        }
        if (!empty($fields)) {
            foreach ($fields as $name => $value) {
                $this->$name = $value;
            }
        }
        if (empty($breadCrumbs)) {
            $this->setBreadCrumbs(array(Router::getRouter()->acao => Router::getRouter()->createUrl(Router::getRouter()->acao)));
        } else {
            $this->setBreadCrumbs($breadCrumbs);
        }
    }

    /**
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $template = $template . ".php";
        if (!is_file($template) || !is_readable($template)) {
            throw new InvalidArgumentException(
                "O template '$template' é inválido.");
        }
        $this->template = $template;
        return $this;
    }

    public function setBreadCrumbs(array $breadCrumbs)
    {
        $this->breadCrumbs = $breadCrumbs;
    }

    public function getBreadCrumbs()
    {
        return $this->breadCrumbs;
    }

    public function printBreadCrumbs($encapsulateUl = true, $ulClass = 'breadcrumb', $liClass = 'bc-item')
    {
        if ($encapsulateUl)
            echo "<ul class='" . $ulClass . "'>";

        foreach ($this->breadCrumbs as $breadCrumb => $link) {
            echo "<li class='" . $liClass . "'><a href='" . $link . "'>" . $breadCrumb . "</a></li>";
        }
        if ($encapsulateUl)
            echo "</ul>";

    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function __set($name, $value)
    {
        $this->fields[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->fields[$name])) {
            throw new InvalidArgumentException(
                "Unable to get the field '$name'.");
        }
        $field = $this->fields[$name];
        return $field instanceof Closure ? $field($this) : $field;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->fields[$name]);
    }

    /**
     * @param $name
     * @return $this
     */
    public function __unset($name)
    {
        if (!isset($this->fields[$name])) {
            throw new InvalidArgumentException(
                "Unable to unset the field '$name'.");
        }
        unset($this->fields[$name]);
        return $this;
    }

    /**
     * Renders the view
     */
    public function render($print_on_screen = true)
    {
        extract($this->fields);
        if ($print_on_screen) {
            include $this->template;
            ob_flush();
        } else {
            ob_start();
            ob_implicit_flush(false);
            require $this->template;
            return ob_get_clean();
        }
    }


    public function checkActive($key, $value, $boolean = false)
    {
        if (Router::getRouter()->getParametro($key) == $value) {
            if ($boolean)
                return true;

            return 'class="active"';
        }
        if (Router::getRouter()->controler == $key && Router::getRouter()->action == $value) {
            if ($boolean)
                return true;

            return 'class="active"';
        }
        if ($boolean)
            return false;

        return "";
    }
}