<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Recuperar Senha</h1>
        </div>
        <div class="col-md-12">
            <form method="post" action="<?php echo \Bdr\Vendor\Router::getRouter()->createUrl('lostPassword'); ?>">
                <div class="col-md-12">
                    <h4>Informe seu Email</h4>
                </div>
                <div class="col-md-9 col-sm-12">
                    <input type="text" class="form-control" name="mail" placeholder="seuemail@seudominio.com.br"/>
                </div>
                <div class="col-md-3 col-sm-12">
                    <input class="btn btn-primary" type="submit" value="Enviar"/>
                </div>
            </form>
        </div>
    </div>
</div>