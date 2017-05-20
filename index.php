<?php
/**
 * Bootsrap da Applicação
 */
header('Content-type: text/html; charset=UTF-8');
include "autoloader.php";
$sistema = new \Bdr\Sistema();
$route = new \Bdr\Vendor\Router();
\Bdr\Vendor\Auth::auth();
$controller = new \Bdr\Vendor\Controller();
$controller->init();
\Bdr\Vendor\Database::close();
exit();