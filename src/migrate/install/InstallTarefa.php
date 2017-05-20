<?php

class InstallTarefa extends \Bdr\Migrate\Migrate
{

    /**
     * Criação do tabela de Tarefas
     */
    public function up()
    {
        $this->create('tarefa', array(
                array(
                    'name' => 'id', 'type' => 'SERIAL NOT NULL', 'pk' => true
                ),
                array(
                    'name' => 'id_usuario', 'type' => 'INT(11) NOT NULL'
                ),
                array(
                    'name' => 'titulo', 'type' => 'VARCHAR(225) NOT NULL'
                ),
                array(
                    'name' => 'descricao', 'type' => 'TEXT'
                ),
                array(
                    'name' => 'status', 'type' => 'INT(2) NOT NULL DEFAULT 1'
                ),
                array(
                    'name' => 'ordem', 'type' => 'INT(4) NOT NULL DEFAULT 1'
                ),
                array(
                    'name' => 'created_at', 'type' => 'TIMESTAMP'
                ),
            )
        );
    }
}