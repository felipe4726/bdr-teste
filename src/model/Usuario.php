<?php

namespace Bdr\Model;

class Usuario extends \Bdr\Vendor\Model implements \Bdr\Vendor\ModelInterface
{

    public $id;
    public $login;
    public $pass;
    public $email;
    public $display_name;
    public $status;
    public $auth;
    public $table_name = "usuario";
    public $install = "InstallUsuario";

    /**
     * @param null $object
     * @return Usuario[] | Usuario
     */
    public static function model($object = null)
    {
        if (is_array($object)) {
            $CollectionOf = array();
            foreach ($object as $obj) {
                $CollectionOf[] = new Usuario($obj);
            }
            return $CollectionOf;
        }
        return new Usuario($object);
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
            'login' => array('type' => 'String', 'required' => true, 'unique' => true, 'label' => 'Login'),
            'email' => array('type' => 'String', 'required' => true, 'label' => 'E-mail'),
            'pass' => array('type' => 'String', 'required' => true, 'extra' => 'passwordCheck', 'label' => 'Senha'),
            'display_name' => array('type' => 'String'),
            'status' => array('type' => 'int', 'required' => true, 'label' => 'Status'),
            'auth' => array('type' => 'String'),
        );
    }

    /**
     * Busca usuarios de acordo com as variaveis incializadas do objeto
     * @return Usuario[]
     */
    public function search($criteria = null)
    {
        if (empty($criteria)) {
            $criteria = new \Bdr\Vendor\Criteria();
        } else {
            if (get_class($criteria) != 'Criteria') {
                $criteria = new \Bdr\Vendor\Criteria($criteria);
            }
        }
        if (isset($this->login) && $this->login != '')
            $criteria->addCondition("t.login ='" . $this->login . "'");
        if (isset($this->email) && $this->email != '')
            $criteria->addCondition("t.email ='" . $this->email . "'");
        if (isset($this->display_name) && $this->display_name != '')
            $criteria->addCondition("t.display_name ='" . $this->display_name . "'");

        return $this->findAll($criteria);
    }

    /**
     * Antes de rodar o save remove as pontuações do cpf-cnpj sempre
     * @return bool
     */
    public function save()
    {
        return parent::save();
    }


    public function getStatus()
    {
        switch ($this->status) {
            case 1:
                return "Ativo";
            case 0:
                return "Aguardando Moderação";
            case 2:
                return "Bloqueado";
        }
    }

    public function bloquear()
    {
        $this->status = 0;
        $this->save();
    }


    public function passwordCheck()
    {
        if (strlen($this->pass) == 34 && strpos($this->pass, 'P$B') == 1) {
            return true;
        } else {
            if (strlen($this->pass) > 5 && strlen($this->pass) < 20) {
                $wp_hasher = new \Bdr\Ext\PasswordHash(8, true);
                $this->pass = $wp_hasher->HashPassword(trim($this->pass));
                return true;
            } else {
                $this->addError('senha', "Senha inválida. A senha deve conter entre 6 e 19 caractéres");
                return false;
            }
        }
    }

}