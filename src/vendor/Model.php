<?php

namespace Bdr\Vendor;
/**
 *  Model mãe de todas as Models
 */
class Model
{

    public $table_name;
    public $pk = 'id';
    public $isNew = true;
    public $errors = array();
    public $numRows;
    public $created_at;
    public $usageList = array();
    public $install = false;

    public function __construct($object = null)
    {
        if (!empty($object)) {
            $object = (array)$object;
            $this->setAttr($object);
        }
        return $this;
    }

    /**
     * Seta os parametros de um array para o objeto
     * @param array $attributes
     * @return $this
     */
    public function setAttr($attributes = array())
    {
        foreach ($attributes as $key => $value) {
            if (property_exists(get_class($this), $key))
                $this->$key = $value;
        }
        return $this;
    }

    /**
     * Define se o objeto foi persistido, sendo assim não é mais novo.
     */
    public function persisted()
    {
        $this->isNew = false;
    }

    public function reset()
    {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }

    public function hasTableName()
    {
        if (empty($this->table_name))
            throw new \Exception('Não é possível procurar ou salvar algo sem a definição do nome da tabela na Model.' . get_class($this), 401);
    }

    /**
     * @param array | Criteria $criteria
     * @return bool | Model
     * @throws \Exception
     */
    public function find($criteria = array('select' => '', 'join' => '', 'condition' => '', 'order' => '', 'group' => ''))
    {
        if (is_object($criteria))
            $criteria = (array)$criteria;

        $this->hasTableName();

        $sql = "SELECT ";

        if (isset($criteria['select']) && $criteria['select'] != "") {
            $sql .= $criteria['select'];
        } else {
            $sql .= "t.*";
        }

        $sql .= " FROM " . $this->table_name . " t ";

        if (isset($criteria['join']) && $criteria['join'] != "")
            $sql .= $criteria['join'];

        if (isset($criteria['condition']) && $criteria['condition'] != "")
            $sql .= " WHERE " . $criteria['condition'];

        if (isset($criteria['group']) && $criteria['group'] != "")
            $sql .= " GROUP BY " . $criteria['group'];

        if (isset($criteria['order']) && $criteria['order'] != "")
            $sql .= " ORDER BY " . $criteria['order'];

        $sql .= " LIMIT 1";

        $model = get_class($this);
        Database::dbaction($sql, $r, $n);
        if ($rs = $r->fetchObject($model)) {
            $this->setAttr((array)$rs);
            $this->persisted();
            return $this;
        }
        return false;
    }

    /**
     * @param bool $setValue
     * @return bool | Model
     */
    public function findByPk($setValue = false)
    {
        $this->hasTableName();

        $field = $this->pk;
        if ($setValue)
            $this->$field = $setValue;

        $sql = "SELECT t.* FROM " . $this->table_name . " t WHERE " . $field . " = '" . $this->$field . "' LIMIT 1";

        $model = get_class($this);
        Database::dbaction($sql, $r, $n);
        if ($rs = $r->fetchObject($model)) {
            $this->setAttr((array)$rs);
            $this->persisted();
            return $this;
        }
        return false;
    }

    /**
     * @param array $criteria
     * @return array()
     * @throws \Exception
     */
    public function findAll($criteria = array('select' => '', 'join' => '', 'condition' => '', 'order' => '', 'group' => '', 'limit' => '', 'page' => ''))
    {
        if (is_object($criteria)) {
            $criteria->standardProcedure();
            $criteria = (array)$criteria;
        }

        $this->hasTableName();

        $sql = "SELECT ";

        if (isset($criteria['select']) && $criteria['select'] != "") {
            $sql .= $criteria['select'];
        } else {
            $sql .= "t.*";
        }

        $sql .= " FROM " . $this->table_name . " t ";

        if (isset($criteria['join']) && $criteria['join'] != "")
            $sql .= $criteria['join'];

        if (isset($criteria['condition']) && $criteria['condition'] != "")
            $sql .= " WHERE " . $criteria['condition'];

        if (isset($criteria['group']) && $criteria['group'] != "")
            $sql .= " GROUP BY " . $criteria['group'];

        if (isset($criteria['order']) && $criteria['order'] != "")
            $sql .= " ORDER BY " . $criteria['order'];

        if (isset($criteria['limit']) && $criteria['limit'] != "") {
            $sql .= " LIMIT " . $criteria['limit'];

            if (isset($criteria['page']) && $criteria['page'] != "")
                $sql .= " OFFSET " . ((int)$criteria['limit'] * ((int)$criteria['page'] - 1));
        }

        Database::dbaction($sql, $r, $n);
        $item = array();
        $model = get_class($this);
        while ($rs = $r->fetchObject($model)) {
            $rs->persisted();
            $rs->numRows = $n;
            $item[] = $rs;
        }
        return $item;
    }

    /**
     * Salva objeto no banco
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        $this->hasTableName();
        $pk = $this->pk;
        $condition = "";
        if ($this->isNew) {
            $sql = "INSERT INTO " . $this->table_name . " SET ";
            $update = '';
        } else {
            if (!isset($this->$pk))
                throw new Exception('Update em objeto sem chave definida', 403);

            $sql = "UPDATE " . $this->table_name . " SET ";
            $update = " WHERE `" . $this->pk . "` = '" . $this->$pk . "'";
        }
        foreach ($this->rules() as $key => $validation) {
            if ($this->validateField($key, $validation) && isset($this->$key) && $this->$key != "") {
                $sql .= " `" . $key . "` = '" . $this->$key . "',";
                $condition .= " `" . $key . "` = '" . $this->$key . "' AND";
            }
        }
        if (!$this->getErrors()) {
            $sql = substr($sql, 0, -1) . $update;
            if (Database::dbactionf($sql)) {
                if ($this->isNew) {
                    $this->$pk = Database::lastID();
                    $this->persisted();
                }
                return true;
            }
        }
        return false;
    }

    public function delete()
    {
        $pk = $this->pk;
        $sql = "DELETE FROM " . $this->table_name . " WHERE " . $this->pk . " = " . $this->$pk;
        return Database::dbactionf($sql);
    }

    public function deleteRelation()
    {
        $sql = "DELETE FROM " . $this->table_name . " WHERE ";
        foreach ($this->rules() as $key => $validation) {
            if ($this->validateField($key, $validation) && isset($this->$key) && $this->$key != "") {
                $sql .= " `" . $key . "` = '" . $this->$key . "' AND";
            }
        }
        $sql = substr($sql, 0, -3); //remover ultimo AND;
        return Database::dbactionf($sql);
    }


    public function validateField($key, $validation)
    {
        if (!isset($this->$key) || $this->$key === null || $this->$key === "") {
            if (isset($validation['required']) && $validation['required'] == true) {
                $this->addError($key, ((isset($validation['label'])) ? $validation['label'] : $key) . " é obrigatório." . $this->$key);
                return false;
            }
        } else {
            if (isset($validation['type'])) {
                switch ($validation['type']) {
                    case 'int':
                        if (!is_numeric($this->$key)) {
                            $this->addError($key, ((isset($validation['label'])) ? $validation['label'] : $key) . " deve ser um número inteiro.");
                            return false;
                        }
                        break;
                    case 'float':
                        if (strpos($this->$key, ',') && strpos($this->$key, '.')) // Ajuste de valor monetário BRL para float
                            $this->$key = str_replace('.', '', $this->$key);
                        $this->$key = str_replace(',', '.', $this->$key);
                        if (!is_numeric($this->$key)) {
                            $this->addError($key, ((isset($validation['label'])) ? $validation['label'] : $key) . " deve ser um número decimal.");
                            return false;
                        }
                        break;
                    case 'date':
                        $date = str_replace('/', '-', $this->$key);
                        if (strpos($date, '-') < 3) {
                            if (!$dateValue = DateTime::createFromFormat('d-m-Y', $date)) {
                                $this->addError($key, "Data inválida.");
                                return false;
                            }
                        } else {
                            if (!$dateValue = DateTime::createFromFormat('Y-m-d', $date)) {
                                $this->addError($key, "Data inválida.");
                                return false;
                            }
                        }
                        $this->$key = $dateValue->format('Y-m-d');
                        break;
                    case 'datetime':
                        $date = str_replace('/', '-', $this->$key);
                        if (strpos($date, '-') < 3) {
                            if (!$dateValue = DateTime::createFromFormat('d-m-Y H:i:s', $date)) {
                                $this->addError($key, "Data/hora inválida.");
                                return false;
                            }
                        } else {
                            if (!$dateValue = DateTime::createFromFormat('Y-m-d H:i:s', $date)) {
                                $this->addError($key, "Data/hora inválida.");
                                return false;
                            }
                        }
                        $this->$key = $dateValue->format('Y-m-d H:i:s');
                        break;
                }
            }
            if (isset($validation['unique']) && $validation['unique'] == true && $this->isNew()) {
                $class = get_class($this);
                $newModel = new $class();
                if ($newModel->find(array('condition' => "`" . $key . "` = '" . $this->$key . "' "))) {
                    $this->addError($key, $this->$key . " já existe. E não pode ter mais de um igual.");
                    return false;
                }
            }
            if (isset($validation['extra'])) {
                if (!$this->$validation['extra']())
                    return false;
            }
        }
        return true;
    }

    public function validate()
    {
        $valid = true;
        foreach ($this->rules() as $key => $validation) {
            if (!$this->validateField($key, $validation)) {
                $valid = false;
            }
        }
        return $valid;
    }

    public function addError($key, $message)
    {
        $this->errors[$key] = $message;
    }

    public function getErrors($key = false)
    {
        if (empty($this->errors))
            return false;

        if ($key) {
            if (!empty($this->errors[$key])) {
                return $this->errors[$key];
            } else {
                return false;
            }
        }

        return $this->errors;
    }

    public function getErrorText()
    {
        $errorText = "";
        foreach ($this->getErrors() as $key => $erro) {
            $errorText .= ' ' . $key . ' ' . $erro . ',';
        }
        return substr($errorText, 0, -1);
    }

    public function isNew()
    {
        return $this->isNew;
    }

    public function getPrimaryKey()
    {
        $pk = $this->pk;
        return $this->$pk;
    }

    public function findUsage($usageList = array())
    {
        $retorno = false;
        $searchParty = $this->usageList;
        if (!empty($usageList))
            $searchParty = $usageList;

        foreach ($searchParty as $model => $relation) {
            if ($inUse = $model::model()->findAll(array('condition' => $relation . " = '" . $this->getPrimaryKey() . "'")))
                \Bdr\Sistema::app()->setError('Item em uso no objeto: ' . $model . ' , se a intenção era deletar, favor deletar primeiramente os registros aqui apontados <pre>' . print_r($inUse, true) . '</pre>');
            $retorno = true;
        }

        return $retorno;

    }

}