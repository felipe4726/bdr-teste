<?php

namespace Bdr;
/**
 * Class Sistema
 */
class Sistema
{

    CONST version = '0.1';
    CONST SYSPATH = 'src';
    public $timestart;
    public $tem_session = false;
    public $config = array();
    public $errors = array();
    public $flashMessage = array();
    public $language;
    public $database;
    public $db_user;
    public $db_pass;
    public $mailConfig;

    public $layout;

    /**
     * @var \Bdr\Vendor\Usuario
     */
    public $webUser;

    public function __construct($admin = false)
    {
        Config::load($this);
        $this->timestart = time();
        include "vendor/Functions.php";
        session_start();
        if (isset($_SESSION['app']) && $this->tem_session = count($_SESSION['app']) > 0)
            $this->reload($this->app());

        if (isset($_REQUEST['install']) || !\Bdr\Vendor\Database::init($this))
            \Bdr\Migrate\Migrate::install($this);

        $this->refresh();
        return $this;
    }


    public function getTimestart()
    {
        return $this->timestart;
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return \Bdr\Vendor\Usuario
     */
    public function getLoggedUser()
    {
        return $this->webUser;
    }

    public function end($print = false)
    {
        if ($print)
            print $print;
        \Bdr\Vendor\Database::close();
        exit();
    }

    public function setError($msg)
    {
        $this->errors[$msg] = $msg;
        $this->refresh();
    }

    public function setFlash($text)
    {
        $this->flashMessage[$text] = $text;
        $this->refresh();
    }

    public function clearMessages()
    {
        $this->flashMessage = array();
        $this->errors = array();
        $this->refresh();
    }

    /**
     * @param \Bdr\Vendor\Usuario $usuario
     */
    public function setLoggedIn($usuario)
    {
        $this->webUser = $usuario;
        $this->refresh();
    }

    /**
     * Removes the logged user..
     */
    public function logout()
    {
        $this->webUser = false;
        $this->refresh();
        \Bdr\Vendor\Database::logout();
    }

    /**
     * @return Sistema
     */
    public static function app()
    {
        return $_SESSION['app'];
    }

    public function refresh()
    {
        $_SESSION['app'] = $this;
    }

    public function reload($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (property_exists(get_class($this), $key))
                $this->$key = $value;
        }
    }

    public function hasErrors()
    {
        if (!empty($this->errors))
            return true;

        return false;
    }

    public function getTemplatePath()
    {
        return 'theme/' . Config::TEMPLATEPATH . '/';
    }

}