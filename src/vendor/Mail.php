<?php

namespace Bdr\Vendor;

class Mail extends Model implements ModelInterface
{

    const _DEBBUG = false;
    const _NO_TAGS = true; // True = Sem as tags do Blog

    public $id;
    public $to;
    public $from = 'felipe@labbo.com.br';
    public $cc = 'felipe@labbo.com.br';
    public $subject;
    public $content;
    public $status;

    public $table_name = 'mail';
    public $viewPath = '';

    public function __construct()
    {
        $this->from = \Bdr\Config::mailSender;
    }

    /**
     * @param $object
     * @return Mail[]|Mail
     */
    public static function model($object = null)
    {
        if (is_array($object)) {
            $Collection = array();
            foreach ($object as $obj) {
                $Collection[] = new Mail($obj);
            }
            return $Collection;
        }
        return new Mail($object);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            'id' => array('type' => 'int', 'unique' => true),
            'to' => array('type' => 'String', 'required' => true),
            'from' => array('type' => 'String', 'required' => true),
            'cc' => array('type' => 'String'),
            'subject' => array('type' => 'String', 'required' => true),
            'content' => array('type' => 'String', 'required' => true),
            'status' => array('type' => 'int', 'required' => true)
        );
    }

    public function getHeader()
    {
        $header = new \Bdr\Vendor\View($this->viewPath . 'header');
        $template = $header->render(false);
        return $template;
    }

    public function getFooter()
    {
        $footer = new \Bdr\Vendor\View($this->viewPath . 'footer');
        $template = $footer->render(false);
        return $template;
    }

    public function getContent($view, $attributes)
    {
        $this->viewPath = dirname(__FILE__) . '/../../theme/' . \Bdr\Config::TEMPLATEPATH . '/_mail/';
        $content = new \Bdr\Vendor\View($this->viewPath . $view, $attributes);
        $retorno = $this->getHeader();
        $retorno .= $content->render(false);
        $retorno .= $this->getFooter();
        $this->content = $retorno;
    }

    public function sendMail()
    {
        require_once __DIR__ . '/../ext/Zend/Zend_Mail.php';
        require_once __DIR__ . '/../ext/Zend/Mail/Transport/Smtp.php';
        $mail = new \Zend_Mail('UTF-8');
        $regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
        if (!preg_match($regex, $this->to)) $this->to = $this->from;
        $mail->addTo($this->to)
            ->addBcc($this->cc)
            ->setSubject($this->subject)
            ->setBodyHtml($this->content)
            ->setFrom($this->from);
        try {
            if (\Bdr\Config::authmail) {
                $transport = new \Zend_Mail_Transport_Smtp(\Bdr\Config::mailHost, \Bdr\Sistema::app()->mailConfig);
                $mail->send($transport);
            } else {
                $mail->send();
            }
            $this->status = 1;
            $this->content = addslashes($this->content);
            if (!$this->save())
                Log::erro('Não foi possível salvar o email:' . print_r($this->getErrors(), true));
        } catch (\Exception $e) {
            Log::erro("Email não foi enviado: " . print_r($e, true));
            $this->status = 0;
            $this->content = addslashes($this->content);
            $this->save();
            return false;
        }
        return true;
    }

    public function lostpass(\Bdr\Model\Usuario $usuario)
    {
        $mail = new Mail();
        $mail->getContent('lostpass', array('usuario' => $usuario));
        $mail->to = $usuario->email;
        $mail->subject = "Recuperar a Senha";
        $mail->sendMail();
    }

    public static function reportError($error)
    {
        $mail = new Mail();
        $mail->getContent('error', array('error' => $error));
        $mail->subject = "Erro";
        $mail->sendMail();
    }

}

?>