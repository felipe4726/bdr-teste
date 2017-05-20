<?php

class InstallLog extends \Bdr\Migrate\Migrate
{

    /**
     * Criação do tabela de Log
     */
    public function up()
    {
        $this->create('log', array(
                array(
                    'name' => 'id', 'type' => 'SERIAL NOT NULL', 'pk' => true
                ),
                array(
                    'name' => 'id_usuario', 'type' => 'INT(11)'
                ),
                array(
                    'name' => 'tipo', 'type' => 'INT(2)'
                ),
                array(
                    'name' => 'evento', 'type' => 'TEXT'
                ),
                array(
                    'name' => 'created_at', 'type' => 'TIMESTAMP'
                ),
            )
        );
    }
}