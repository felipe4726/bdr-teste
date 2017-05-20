<?php

class InstallMigrate extends \Bdr\Migrate\Migrate
{

    /**
     * Criação do tabela de Grupo
     */
    public function up()
    {
        $this->create('migrations', array(
                array(
                    'name' => 'id', 'type' => 'SERIAL NOT NULL', 'pk' => true
                ),
                array(
                    'name' => 'filename', 'type' => 'VARCHAR(225) NOT NULL'
                ),
                array(
                    'name' => 'created_at', 'type' => 'TIMESTAMP'
                ),
            )
        );
    }
}