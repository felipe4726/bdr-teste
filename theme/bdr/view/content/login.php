<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <h1>LOGIN</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo \Bdr\Vendor\Router::getRouter()->createUrl('login'); ?>" method="POST">
                <div class="form-group text-left">
                    <label>Login</label>
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
                                        href="<?php echo \Bdr\Vendor\Router::getRouter()->createUrl('senha'); ?>">[
                        Esqueci minha senha ]</a></p>
            </form>
        </div>
    </div>
</div>