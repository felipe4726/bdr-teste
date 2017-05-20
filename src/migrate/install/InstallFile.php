<?php

class InstallFile extends \Bdr\Migrate\Migrate
{

    /**
     * Criação do tabela de Grupo
     */
    public function up()
    {
        $this->create('file', array(
                array(
                    'name' => 'id', 'type' => 'SERIAL NOT NULL', 'pk' => true
                ),
                array(
                    'name' => 'guid', 'type' => 'VARCHAR(50)'
                ),
                array(
                    'name' => 'object_model', 'type' => 'VARCHAR(225) NOT NULL'
                ),
                array(
                    'name' => 'object_id', 'type' => 'INT(4)'
                ),
                array(
                    'name' => 'file_name', 'type' => 'VARCHAR(225) NOT NULL'
                ),
                array(
                    'name' => 'title', 'type' => 'VARCHAR(255)'
                ),
                array(
                    'name' => 'mime_type', 'type' => 'VARCHAR(155)'
                ),
                array(
                    'name' => 'ordem', 'type' => 'INT(4)'
                ),
                array(
                    'name' => 'size', 'type' => 'FLOAT'
                ),
                array(
                    'name' => 'created_at', 'type' => 'TIMESTAMP'
                ),
            )
        );
    }
}