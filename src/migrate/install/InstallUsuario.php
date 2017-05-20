<?php

class InstallUsuario extends \Bdr\Migrate\Migrate
{
    /**
     * Criação da tabela de Usuario
     */
    public function up()
    {
        $this->create('usuario', array(
                array(
                    'name' => 'id', 'type' => 'SERIAL NOT NULL', 'pk' => true
                ),
                array(
                    'name' => 'login', 'type' => 'VARCHAR(125)'
                ),
                array(
                    'name' => 'pass', 'type' => 'VARCHAR(125) NOT NULL'
                ),
                array(
                    'name' => 'email', 'type' => 'VARCHAR(255) NOT NULL'
                ),
                array(
                    'name' => 'display_name', 'type' => 'VARCHAR(225)'
                ),
                array(
                    'name' => 'status', 'type' => 'INT(2) NOT NULL DEFAULT 1'
                ),
                array(
                    'name' => 'auth', 'type' => 'VARCHAR(45)'
                ),
                array(
                    'name' => 'created_at', 'type' => 'TIMESTAMP'
                ),
            )
        );

        $this->insert('usuario',
            array(
                'id' => 1,
                'login' => 'bdr',
                'email' => 'contato@brd.com.br',
                'pass' => '$P$B038Z9r9robu8DY2CbFoQcwuacf8ay.',
                'display_name' => 'Teste',
            )
        );
    }
}