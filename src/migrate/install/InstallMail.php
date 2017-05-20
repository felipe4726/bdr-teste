<?php

class InstallMail extends \Bdr\Migrate\Migrate
{

    /**
     * Criação do tabela de Emails Enviados
     */
    public function up()
    {
        $this->create('mail', array(
                array(
                    'name' => 'id', 'type' => 'SERIAL NOT NULL', 'pk' => true
                ),
                array(
                    'name' => 'to', 'type' => 'VARCHAR(225) NOT NULL'
                ),
                array(
                    'name' => 'from', 'type' => 'VARCHAR(225) NOT NULL'
                ),
                array(
                    'name' => 'cc', 'type' => 'VARCHAR(225)'
                ),
                array(
                    'name' => 'subject', 'type' => 'VARCHAR(225) NOT NULL'
                ),
                array(
                    'name' => 'content', 'type' => 'TEXT'
                ),
                array(
                    'name' => 'status', 'type' => 'INT(4)'
                ),
                array(
                    'name' => 'created_at', 'type' => 'TIMESTAMP'
                ),
            )
        );
    }
}