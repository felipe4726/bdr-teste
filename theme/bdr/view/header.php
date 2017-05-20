<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felipe">
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <title><?php echo \Bdr\Config::Sitename; ?></title>
    <link rel="shortcut icon" href="<?php echo \Bdr\Config::APPURL; ?>img/favicon.png" type="image/x-icon">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet"
          href="<?php echo \Bdr\Config::APPURL . \Bdr\Sistema::app()->getTemplatePath(); ?>/assets/css/custom.css?ver=<?php \Bdr\Sistema::version; ?>"
          type="text/css"/>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- PrettyPrint for sourceCodes -->
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
    <script src="<?php echo \Bdr\Config::APPURL . \Bdr\Sistema::app()->getTemplatePath(); ?>/assets/js/custom.js?ver=<?php \Bdr\Sistema::version; ?>"></script>
</head>
<body>
<header>
    <div class="container">
        <div class="row mar-ver">
            <div class="col-sm-4">
                <a href="<?php echo \Bdr\Config::APPURL; ?>"><img class="logo-topo"
                                                                  src="<?php echo \Bdr\Config::APPURL . \Bdr\Sistema::app()->getTemplatePath(); ?>img/logo.png"/></a>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-7">

                    </div>
                    <div class="col-sm-5">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <?php if (\Bdr\Sistema::app()->webUser) { ?>
                                    <a href="#" class="minha-conta logado btn btn-primary">Minha Conta</a>
                                <?php } else { ?>
                                    <a href="#" class="minha-conta btn btn-primary">Entrar</a>
                                <?php } ?>
                                <div class="sub-menu-conta">
                                    <div class="interno">
                                        <?php if (\Bdr\Sistema::app()->webUser) { ?>
                                            <p><b>Bem vindo <?php echo \Bdr\Sistema::app()->webUser->display_name; ?>
                                                    !</b></p>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo \Bdr\Vendor\Router::getRouter()->createUrl('usuario', 'logout'); ?>">Sair</a>
                                                </li>
                                            </ul>
                                        <?php } else { ?>
                                            <form name="frmlogin" action="<?php echo \Bdr\Config::APPURL; ?>login"
                                                  method="post">
                                                <input name="acao" value="login" type="hidden">
                                                <input type="hidden" name="usenha" value="">
                                                <div class="form-group text-left">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" name="ulogin">
                                                </div>
                                                <div class="form-group text-left">
                                                    <label>Senha</label>
                                                    <input type="password" class="form-control" name="usenha">
                                                </div>
                                                <p class="text-left">
                                                    <button type="submit" class="btn btn-primary">ENVIAR</button>
                                                </p>
                                                <p class="text-left"><a class="textoEsqueciSenha"
                                                                        href="<?php echo \Bdr\Config::APPURL; ?>senha">[
                                                        Esqueci minha senha ]</a></p>
                                                <hr/>
                                                <p class="text-left"><a class="btn btn-primary" style="color: #FFF;"
                                                                        href="<?php echo \Bdr\Config::APPURL; ?>cadastro">QUERO
                                                        ME CADASTRAR</a></p>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (\Bdr\Sistema::app()->webUser) { ?>
        <div class="navbar navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse bg-blue">
                    <ul class="pad-all-sm pad-btm">
                        <li><a href="<?php echo \Bdr\Vendor\Router::getRouter()->createUrl('index'); ?>">Início</a></li>
                    </ul>
                </div>
                <!--/.navbar-collapse -->
            </div>
        </div>
    <?php } ?>
    <div class="message-box"></div>
</header>
<div class="container">
