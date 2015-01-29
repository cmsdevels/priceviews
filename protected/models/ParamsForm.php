<?php

class ParamsForm extends CFormModel
{
    public $params;

    protected $_oldParams = array();

    public $paramsDesc = array();

    public function init()
    {
        parent::init();
        $this->paramsDesc = Params::model()->findAll();
        $validatorsList = $this->getValidatorList();
        foreach ($this->paramsDesc as $paramDesc) {
            $validator = $this->createValidator($paramDesc);
            $validatorsList->add($validator);
        }
        $this->params = eval(' ?> '.file_get_contents(Yii::app()->basePath.'/config/params.php').'<?php ');

        if (!is_array($this->params))
            $this->params = array();
        $this->_oldParams = $this->params;
    }

    /**
     * @param Params $param
     * @return CValidator
     */
    protected function createValidator($param)
    {
        $validator = null;
        switch ($param->type) {
            case Params::TYPE_EMAIL:
                $validator = CValidator::createValidator('CEmailValidator', $this, $param->key);
                break;
            case Params::TYPE_IMAGE:
                $validator = CValidator::createValidator('CFileValidator', $this, $param->key, array('types'=>'jpg, jpeg, png, gif', 'allowEmpty'=>true));
                break;
            case Params::TYPE_FILE:
                $validator = CValidator::createValidator('CFileValidator', $this, $param->key, array('allowEmpty'=>true));
                break;
            case Params::TYPE_URL:
                $validator = CValidator::createValidator('CUrlValidator', $this, $param->key);
                break;
            default:
                $validator = CValidator::createValidator('CSafeValidator', $this, $param->key);
        }
        return $validator;
    }

    public function save()
    {
        foreach ($this->paramsDesc as $param) {
            if ($param->type == Params::TYPE_FILE || $param->type == Params::TYPE_IMAGE) {
                $file = CUploadedFile::getInstance($this, $param->key);
                if ($file) {
                    $name = uniqid().'.'.$file->getExtensionName();
                    $dir = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR;
                    $file->saveAs($dir.$name);
                    $this->params[$param->key] = $name;
                } elseif (isset($this->_oldParams[$param->key]) && !empty($this->_oldParams[$param->key])) {
                    // save old file
                    $this->params[$param->key] = $this->_oldParams[$param->key];
                }
            }
        }
        $configString = "<?php\n return " . var_export($this->params, true) . " ;\n?>";
        $paramsFile = Yii::app()->basePath.'/config/params.php';
        $config = fopen($paramsFile, 'w+');
        if (flock($config, LOCK_EX))
        {
            ftruncate($config, 0);
            fwrite($config, $configString);
            fflush($config);
            flock($config, LOCK_UN);
            fclose($config);
            @chmod($paramsFile, 0666);
        } else {
            $this->addError('params', Yii::t('core/admin', 'The file is not writable. Set write permissions to the file ').'/protected/config/params.php ');
            return false;
        }
        return true;
    }

    public function __get($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        else
            return null;
    }

    public function __set($name, $value)
    {
        if (is_array($value)) {
            foreach ($value as $key=>$val)
                $this->params[$key] = $val;
        } else
            $this->params[$name] = $value;
    }


    protected $_labels = null;

    public function attributeLabels()
    {
        if ($this->_labels === null) {
            $labels = array();
            foreach ($this->paramsDesc as $paramDesc)
                $labels[$paramDesc->key] = $paramDesc->desc;
            $this->_labels = $labels;
        }
        return $this->_labels;
    }

}
