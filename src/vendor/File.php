<?php

namespace Bdr\Vendor;

class File extends Model
{

    public $id;
    public $guid;
    public $object_model;
    public $object_id;
    public $file_name;
    public $title;
    public $ordem;
    public $mime_type;
    public $size;
    public $path = 'uploads/';
    /**
     * @var \Bdr\Ext\CFile $cFile
     */
    private $cFile;
    public $table_name = "file";

    public function rules()
    {
        return array(
            'id' => array('type' => 'int'),
            'guid' => array('type' => 'String', 'required' => true),
            'object_model' => array('type' => 'String', 'required' => true),
            'object_id' => array('type' => 'int', 'required' => true),
            'file_name' => array('type' => 'String', 'required' => true),
            'title' => array('type' => 'String', 'required' => true),
            'mime_type' => array('type' => 'String', 'required' => true),
            'ordem' => array('type' => 'int'),
            'size' => array('type' => 'float'),
        );
    }

    public static function model()
    {
        return new File();
    }

    /**
     * Busca cupom de acordo com as variaveis incializadas do objeto
     * @return File[]
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

    /**
     * @param Model $object
     * @return File[]
     */
    public static function getFilesOfObject(Loja $object)
    {
        return File::model()->findAll(array('condition' => "t.object_id = '" . $object->getPrimaryKey() . "' AND t.object_model = '" . get_class($object) . "'", 'order' => 't.ordem ASC'));
    }

    /**
     * @param $identifier
     * @return File
     */
    public static function getFileOfIdentifier($identifier)
    {
        return File::model()->find(array('condition' => "t.object_id = '1' AND t.object_model = '" . $identifier . "'"));
    }

    public function save($model = false, $fieldName = false, $max_sized_photo_file = false)
    {
        if ($this->isNew() && $model && $fieldName) {
            $this->guid = GUIDv4();
            $this->object_model = get_class($model);
            $this->object_id = $model->getPrimaryKey();
            $this->cfile = \Bdr\Ext\CFile::getInstanceByName($fieldName);

            if (!$max_sized_photo_file) {
                $this->setUploadedFile(\Bdr\Ext\CFile::getInstanceByName($fieldName));
            } else {
                $this->setUploadedMaxSizedPhotoFile(\Bdr\Ext\CFile::getInstanceByName($fieldName));
            }
            $this->sanitizeFilename();

            // Set new uploaded file
            if ($this->cFile !== null && $this->cFile instanceof CFile) {
                $newFilename = $this->getPath() . DIRECTORY_SEPARATOR . $this->file_name;
                if (is_uploaded_file($this->cFile->getTempName())) {
                    move_uploaded_file($this->cFile->getTempName(), $newFilename);
                    @chmod($newFilename, 0744);
                }
                if ($this->cFile->getType() == 'image/jpeg') {
                    \Bdr\Ext\ImageConverter::TransformToJpeg($newFilename, $newFilename);
                }
            }
        }
        Log::evento('Salvou Arquivo ' . $this->file_name);
        return parent::save();
    }

    public function saveByString($string = false, $fieldName = false, $max_sized_photo_file = false)
    {
        if ($this->isNew() && $string && $fieldName) {
            if ($unlinkOld = File::model()->find(array('condition' => "object_model ='" . $string . "' AND object_id = 1")))
                $unlinkOld->delete();
            $this->guid = GUIDv4();
            $this->object_model = $string;
            $this->object_id = 1;
            $cfile = \Bdr\Ext\CFile::getInstanceByName($fieldName);

            if (!$max_sized_photo_file) {
                $this->setUploadedFile($cfile);
            } else {
                $this->setUploadedMaxSizedPhotoFile($cfile);
            }
            $this->sanitizeFilename();

            // Set new uploaded file
            if ($this->cFile !== null && $this->cFile instanceof \Bdr\Ext\CFile) {
                $newFilename = $this->getPath() . DIRECTORY_SEPARATOR . $this->file_name;

                if (is_uploaded_file($this->cFile->getTempName())) {
                    move_uploaded_file($this->cFile->getTempName(), $newFilename);
                    @chmod($newFilename, 0744);
                }
                if ($this->cFile->getType() == 'image/jpeg') {
                    \Bdr\Ext\ImageConverter::TransformToJpeg($newFilename, $newFilename);
                }
            }
        }
        Log::evento('Salvou Arquivo ' . $this->file_name);
        return parent::save();
    }

    public function savePreLoadedFile(\Bdr\Ext\CFile $file, $model = false, $max_sized_photo_file = false)
    {
        $this->guid = GUIDv4();
        if ($model) {
            $this->object_model = get_class($model);
            $this->object_id = $model->getPrimaryKey();
        }
        if (!$max_sized_photo_file) {
            $this->setUploadedFile($file);
        } else {
            $this->setUploadedMaxSizedPhotoFile($file);
        }
        $this->sanitizeFilename();

        if ($this->cFile !== null && $this->cFile instanceof \Bdr\Ext\CFile) {
            $newFilename = $this->getPath() . DIRECTORY_SEPARATOR . $this->file_name;


            if (is_file($this->cFile->getTempName())) {
                rename($this->cFile->getTempName(), $newFilename);
                @chmod($newFilename, 0744);
            }

            if ($this->cFile->getType() == 'image/jpeg') {
                \Bdr\Ext\ImageConverter::TransformToJpeg($newFilename, $newFilename);
            }
        }
        Log::evento('Salvou Arquivo ' . $this->file_name);
        return parent::save();
    }

    public function delete()
    {
        $path = $this->getPath();
        // Make really sure, that we dont delete something else :-)
        if ($this->guid != "" && is_dir($path)) {
            $files = glob($path . DIRECTORY_SEPARATOR . "*");
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($path);
        }
        Log::evento('Deletou Arquivo ' . $this->file_name);
        return parent::delete();
    }

    public function getPath()
    {
        if (get_class(Router::getRouter()) == 'Router') {
            $path = $this->path . $this->guid;
        } else {
            $path = '../' . $this->path . $this->guid;
        }
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    public function getUrl($prefix = "")
    {
        return \Bdr\Config::APPURL . 'uploads' . '/' . $this->guid . '/' . $this->getFilename($prefix);
    }

    public function getFilename($prefix = "")
    {
        if ($prefix == "")
            return $this->file_name;

        $fileParts = pathinfo($this->file_name);
        return $fileParts['filename'] . "_" . $prefix . "." . $fileParts['extension'];
    }

    public function setUploadedFile(\Bdr\Ext\CFile $cFile)
    {
        $this->file_name = $cFile->getName();
        $this->mime_type = $cFile->getType();
        $this->size = $cFile->getSize();
        $this->cFile = $cFile;
    }

    public function setUploadedMaxSizedPhotoFile(\Bdr\Ext\CFile $cFile)
    {
        \Bdr\Ext\ImageConverter::Resize($cFile->getTempName(), $cFile->getTempName(), array('mode' => 'max', 'width' => 1200, 'height' => 1200));
        $this->size = filesize($cFile->getTempName());
        $this->file_name = $cFile->getName();
        $this->mime_type = $cFile->getType();
        $this->cFile = $cFile;
    }

    public function getMimeBaseType()
    {
        if ($this->mime_type != "") {
            list($baseType, $subType) = explode('/', $this->mime_type);
            return $baseType;
        }

        return "";
    }

    public function getMimeSubType()
    {
        if ($this->mime_type != "") {
            list($baseType, $subType) = explode('/', $this->mime_type);
            return $subType;
        }

        return "";
    }

    public function getPreviewImageUrl($maxWidth = 1000, $maxHeight = 1000)
    {

        $prefix = 'pi_' . $maxWidth . "x" . $maxHeight;

        $originalFilename = $this->getPath() . DIRECTORY_SEPARATOR . $this->getFilename();
        $previewFilename = $this->getPath() . DIRECTORY_SEPARATOR . $this->getFilename($prefix);

        // already generated
        if (is_file($previewFilename)) {
            return $this->getUrl($prefix);
        }

        // Check file exists & has valid mime type
        if ($this->getMimeBaseType() != "image" || !is_file($originalFilename)) {
            return "";
        }

        $imageInfo = @getimagesize($originalFilename);

        // Check if we got any dimensions - invalid image
        if (!isset($imageInfo[0]) || !isset($imageInfo[1])) {
            return "";
        }

        // Check if image type is supported
        if ($imageInfo[2] != IMAGETYPE_PNG && $imageInfo[2] != IMAGETYPE_JPEG && $imageInfo[2] != IMAGETYPE_GIF) {
            return "";
        }

        \Bdr\Ext\ImageConverter::Resize($originalFilename, $previewFilename, array('mode' => 'max', 'width' => $maxWidth, 'height' => $maxHeight));
        return $this->getUrl($prefix);
    }

    public function getExtension()
    {
        $fileParts = pathinfo($this->file_name);
        if (isset($fileParts['extension'])) {
            return $fileParts['extension'];
        }
        return '';
    }

    public function sanitizeFilename()
    {
        $this->file_name = trim($this->file_name);

        // Ensure max length
        $pathInfo = pathinfo($this->file_name);

        $checkEncode = utf8_decode($pathInfo['filename']);
        $checkEncode = utf8_encode($checkEncode);

        if ($checkEncode != $pathInfo['filename']) {
            \Bdr\Sistema::app()->setFlash('Arquivo renomeador por questões de Encoding...');
            $this->file_name = preg_replace("/[^a-z0-9_\-s\. ]/i", "", $this->file_name);
            $pathInfo = pathinfo($this->file_name);
        }

        if (strlen($pathInfo['filename']) > 60) {
            $pathInfo['filename'] = substr($pathInfo['filename'], 0, 56) . preg_replace("/[^a-z0-9_\-s\. ]/i", "", substr($pathInfo['filename'], 56, 4)); //Removing special Chars from ending of the file
        }

        $this->file_name = $pathInfo['filename'];

        if ($this->file_name == "") {
            $this->file_name = "Unnamed";
        }

        if (isset($pathInfo['extension']))
            $this->file_name .= "." . trim($pathInfo['extension']);

        $this->title = $this->file_name;
    }

    public function validateExtension($attribute, $params)
    {
        $allowedExtensions = \Bdr\Config::VALIDEXTENSIONS;

        if ($allowedExtensions != "") {
            $extension = $this->getExtension();
            $extension = trim(strtolower($extension));

            $allowed = array_map('trim', explode(",", $allowedExtensions));

            if (!in_array($extension, $allowed)) {
                $this->addError($attribute, 'This file type is not allowed!');
            }
        }
    }

    public function validateSize($attribute, $params)
    {
        if ($this->size > 20000000) {
            $this->addError($attribute, 'Maximum file size ({maxFileSize}) has been exceeded!');
        }
    }


    public static function noImage()
    {
        $file = new File();
        $file->guid = 'ops';
        $file->file_name = 'sem-imagem.png';
        $file->mime_type = 'image/png';
        $file->size = 18429;
        return $file;
    }
}