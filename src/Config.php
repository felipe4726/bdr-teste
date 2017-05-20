<?php

namespace Bdr;

class Config
{
    CONST DEFAULT_LANG = "pt";
    CONST Sitename = 'Bdr Test - Task App';
    CONST APPURL = "http://test.localhost/";
    CONST TEMPLATEPATH = 'bdr';
    CONST VALIDEXTENSIONS = 'jpg, png, gif, txt, bmp, pdf, xls, zip';

    //Config de banco de dados
    CONST database = 'bdr';
    CONST db_user = 'root';
    CONST db_pass = '';

    //Config de email
    CONST authmail = true;
    CONST mailAuthType = 'login';
    CONST mailHost = '';
    CONST mailUsername = '';
    CONST mailPassword = '';
    CONST mailSender = '';
    CONST ERROR_REPORTING = E_ALL;  // Alterar para E_ALL durante desenvolvimento

    public static function load(Sistema &$sistema)
    {
        error_reporting(Config::ERROR_REPORTING);
        $sistema->database = Config::database;
        $sistema->db_user = Config::db_user;
        $sistema->db_pass = Config::db_pass;
        $sistema->language = Config::DEFAULT_LANG;
        $sistema->mailConfig = array(
            'auth' => Config::mailAuthType,
            'username' => Config::mailUsername,
            'password' => Config::mailPassword
        );
    }

}