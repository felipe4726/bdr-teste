<?php

namespace Bdr\Model;


use Bdr\Sistema;

class Tarefa extends \Bdr\Vendor\Model implements \Bdr\Vendor\ModelInterface
{

    public $id;
    public $id_usuario;
    public $titulo;
    public $descricao;
    public $status;
    public $ordem;
    public $table_name = "tarefa";

    /**
     * @param null $object
     * @return Tarefa[] | Tarefa
     */
    public static function model($object = null)
    {
        if (is_array($object)) {
            $CollectionOf = array();
            foreach ($object as $obj) {
                $CollectionOf[] = new Tarefa($obj);
            }
            return $CollectionOf;
        }
        return new Tarefa($object);
    }

    /**
     *  Validações basicas:
     *  type = tipo de dados
     *  required = not null sem valor default no banco
     *  unique = não pode se repetir no banco
     *  extra = chama uma função de validação do próprio objeto (os parenteses não devem ser declarados)
     * @return array de Regras dos Attributes desse objeto para ser utilizado pela validação
     */
    public function rules()
    {
        return array(
            'id' => array('type' => 'int'),
            'id_usuario' => array('type' => 'int', 'required' => true),
            'titulo' => array('type' => 'String', 'required' => true, 'unique' => true, 'label' => 'Título'),
            'descricao' => array('type' => 'String', 'required' => true, 'label' => 'Descrição'),
            'status' => array('type' => 'int', 'required' => true, 'label' => 'Status'),
            'ordem' => array('type' => 'int', 'required' => true),
        );
    }

    /**
     * Busca usuarios de acordo com as variaveis incializadas do objeto
     * @return Tarefa[]
     */
    public function search($criteria = null)
    {
        if (empty($criteria)) {
            $criteria = new \Bdr\Vendor\Criteria();
        } else {
            $criteria = new \Bdr\Vendor\Criteria($criteria);
        }
        if (isset($this->id) && $this->id != '')
            $criteria->addCondition("t.id ='" . $this->id . "'");
        if (isset($this->id_usuario) && $this->id_usuario != '')
            $criteria->addCondition("t.id_usuario ='" . $this->id_usuario . "'");
        if (isset($this->titulo) && $this->titulo != '')
            $criteria->addCondition("t.titulo like '%" . $this->titulo . "%'");
        if (isset($this->descricao) && $this->descricao != '')
            $criteria->addCondition("t.descricao like '%" . $this->descricao . "%'");
        if (isset($this->ordem) && $this->ordem != '')
            $criteria->addCondition("t.ordem ='" . $this->ordem . "'");
        if (isset($this->status) && $this->status != '')
            $criteria->addCondition("t.status ='" . $this->status . "'");

        return $this->findAll($criteria);
    }

    public function save(){
        $this->id_usuario = \Bdr\Sistema::app()->webUser->id;
        if(!isset($this->status))
            $this->status = 1;
        if(!isset($this->ordem))
            $this->ordem = 1;
        return parent::save();
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 1:
                return "Feito";
            case 0:
                return "Aguardando";
        }
    }

}