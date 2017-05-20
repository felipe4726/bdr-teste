<?php
/**
 * @var \Bdr\Model\Tarefa[] $tarefas
 */
?>
<h3>Lista de Tarefas</h3>
<div class="panel panel-default">
    <div class="panel-body">
        <?php $lista = new \Bdr\Vendor\ListaWidget($tarefas,
            array(
                array(
                    'label' => 'Titulo',
                    'class' => '',
                    'filter' => 'text',
                    'attribute' => 'titulo',
                    'data' => '$data->titulo'
                ),
                array(
                    'label' => 'Descricao',
                    'class' => '',
                    'filter' => 'text',
                    'attribute' => 'descricao',
                    'data' => '$data->descricao'
                ),
            ), $criteria
        );
        $lista->render(); ?>
    </div>
</div>

