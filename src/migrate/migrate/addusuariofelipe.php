<?php

class addusuariofelipe extends \Bdr\Migrate\Migrate
{

    /**
     * Criação da tabela de Usuario
     */
    public function up()
    {
        $this->insert('usuario',
            array(
                'id' => 2,
                'login' => 'felipe',
                'email' => 'felipe4726@gmail.com',
                'pass' => '$P$B038Z9r9robu8DY2CbFoQcwuacf8ay.',
                'display_name' => 'Felipe',
            )
        );
    }
}