Olá <?php echo $usuario->display_name; ?>,
<br>
Para resetar sua senha acesse esse link: <a
        href="<?php echo \Bdr\Vendor\Router::getRouter()->createUrl('retrievePassword', $usuario->email, array('token' => $usuario->auth)); ?>"><?php echo \Bdr\Vendor\Router::getRouter()->createUrl('retrievePassword', $usuario->email, array('token' => $usuario->auth)); ?></a>
<br>