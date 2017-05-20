<?php

namespace Bdr\Vendor;
interface ModelInterface
{

    /**
     * Campos do Objeto e suas regras para validação
     *  type = tipo de dados
     *  required = not null, sem valor default no banco
     *  unique = não pode se repetir no banco
     *  extra = chama uma função de validação do próprio objeto (os parenteses não devem ser declarados)
     *  Exemplo: return array(
     *                  'id' => array('type' => 'int'),
     *                  'titulo' => array('type' => 'String', 'required' => true),
     *              );
     * @return array de Regras dos Attributes desse objeto para ser utilizado pela validação
     */
    public function rules();

    public static function model($object = null);
}