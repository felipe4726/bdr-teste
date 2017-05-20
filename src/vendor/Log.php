<?php

namespace Bdr\Vendor;

class Log extends Model implements ModelInterface
{

    CONST LOG_EVENTO = 1;
    CONST LOG_ERROR = 2;

    public $id;
    public $id_usuario;
    public $tipo;
    public $evento;
    public $created_at;

    public $table_name = "app_log";

    /**
     * @param null $object
     * @return Log[]|Log
     */
    public static function model($object = null)
    {
        if (is_array($object)) {
            $Collection = array();
            foreach ($object as $obj) {
                $Collection[] = new Log($obj);
            }
            return $Collection;
        }
        return new Log($object);
    }

    public function rules()
    {
        return array(
            'id' => array('type' => 'int', 'unique' => true),
            'id_usuario' => array('type' => 'int', 'unique' => true),
            'tipo' => array('type' => 'int'),
            'evento' => array('type' => 'String', 'required' => true),
            'created_at' => array('type' => 'datetime'),
        );
    }

    /**
     * Busca eventos no log de acordo com as variaveis incializadas do objeto
     * @return Log[]
     */
    public function search($criteria = null)
    {

        if (empty($criteria)) {
            $criteria = new Criteria();
        } else {
            if (get_class($criteria) != 'Criteria') {
                $criteria = new Criteria($criteria);
            }
        }

        return $this->findAll($criteria);
    }

    public static function evento($evento)
    {
        $log = new Log();
        $log->id_usuario = \Bdr\Sistema::app()->webUser->id;
        $log->tipo = Log::LOG_EVENTO;
        $log->evento = $evento;
        $log->save();
    }

    public static function erro($evento)
    {
        $log = new Log();
        $log->id_usuario = \Bdr\Sistema::app()->webUser->id;
        $log->tipo = Log::LOG_ERROR;
        $log->evento = $evento;
        $log->save();
    }


}