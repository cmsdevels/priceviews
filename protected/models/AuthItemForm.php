<?php
class AuthItemForm extends CFormModel
{
    public $name;
    public $description;
    public $type;
    public $bizRule;
    public $data;
    public $operations = array();
    public $is_admin = 0;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('name, description', 'required'),
            array('is_admin', 'numerical', 'integerOnly'=>true),
            array('name', 'nameIsAvailable', 'on'=>'create'),
//            array('name', 'newNameIsAvailable', 'on'=>'update'),
//            array('name', 'isSuperuser', 'on'=>'update'),
//            array('data', 'bizRuleNotEmpty'),
            array('bizRule, data, operations', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'name'			=> Yii::t('core/admin', 'Name'),
            'description'	=> Yii::t('core/admin', 'Description'),
            'bizRule'		=> Yii::t('core/admin', 'Business rule'),
            'data'			=> Yii::t('core/admin', 'Data'),
            'is_admin'  	=> Yii::t('core/admin', 'Is admin'),
            'operations'  	=> Yii::t('core/admin', 'Operations access'),
        );
    }

    public function nameIsAvailable($attribute, $params)
    {
        if (Yii::app()->authManager->getAuthItem($this->name)!==null)
            $this->addError('name', Yii::t('core/admin', 'An item ":name" is already exists.', array(':name'=>$this->name)));
    }

    /**
     * Makes sure that the new name is available if the name been has changed.
     * This is the 'newNameIsAvailable' validator as declared in rules().
     */
    public function newNameIsAvailable($attribute, $params)
    {
        if( strtolower(urldecode($_GET['name']))!==strtolower($this->name) )
            $this->nameIsAvailable($attribute, $params);
    }

    /**
     * Makes sure that the superuser roles name is not changed.
     * This is the 'isSuperuser' validator as declared in rules().
     */
    public function isSuperuser($attribute, $params)
    {
        if( strtolower($_GET['name'])!==strtolower($this->name) && strtolower($_GET['name'])===strtolower(Rights::module()->superuserName) )
            $this->addError('name', Yii::t('core/admin', 'Name of the superuser cannot be changed.'));
    }

    /**
     * Makes sure that the business rule is not empty when data is specified.
     * This is the 'bizRuleNotEmpty' validator as declared in rules().
     */
    public function bizRuleNotEmpty($attribute, $params)
    {
        if( empty($this->data)===false && empty($this->bizRule)===true )
            $this->addError('data', Yii::t('core/admin', 'Business rule cannot be empty.'));
    }

    public function loadItem($name)
    {
        $authItem = Yii::app()->authManager->getAuthItem($name);
        $this->name = $authItem->name;
        $this->description = $authItem->description;
        $this->is_admin = $authItem->data['is_admin'];
        $this->operations = CHtml::listData(array_values(Yii::app()->authManager->getItemChildren($this->name)), 'name', 'name');
    }

    public function save($name=null)
    {
        if ($this->validate()) {
            $authManager = Yii::app()->authManager;
            $this->data = CMap::mergeArray($this->data, array('is_admin'=>(int)$this->is_admin));
            if ($name) {
                $role = $authManager->getAuthItem($name);
                $role->name = strtolower($this->name);
                $role->description = $this->description;
                $role->data = $this->data;
                $authManager->saveAuthItem($role, $name);
            } else {
                $role = $authManager->createRole(strtolower($this->name), $this->description, null, $this->data);
            }
            foreach ($authManager->getItemChildren($role->name) as $child) {
                $authManager->removeItemChild($role->name, $child->name);
            }
            if (is_array($this->operations))
                foreach ($this->operations as $operation) {
                    if ($authManager->hasItemChild($role->name, $operation)==false)
                        $role->addChild($operation);
                }
            return true;
        } else
            return false;
    }

    public function init()
    {
        self::getAdminSpecs();
    }

    static public function getAdminSpecs()
    {
        $auth=Yii::app()->authManager;
        $files = CFileHelper::findFiles(realpath(Yii::app()->basePath . DIRECTORY_SEPARATOR . 'controllers'. DIRECTORY_SEPARATOR . 'admin'), array('level'=>0));
        foreach ($files as $file) {
            $filename = basename($file, '.php');
            if (($pos = strpos($filename, 'Controller')) > 0) {
                list($controller) = Yii::app()->createController('/admin/'.substr($filename, 0, $pos));
                $controllerSpecs = $controller->accessSpecs();
                if (isset($controllerSpecs['operations'])) {
                    foreach ($controllerSpecs['operations'] as $key=>$value) {
                        if (!empty($value)&&is_string($value)) {
                            $operationName = 'admin/'.strtolower(substr($filename, 0, $pos)).'.'.$key;
                            if (Yii::app()->authManager->getAuthItem($operationName)==null)
                                $auth->createOperation($operationName, $controller->controllerName.' - '.$value);
                        }
                    }
                }
            }
        }
        return;
    }

    /**
     * @param $module Module Object of module
     */
    static public function getModuleSpecs(Module $module)
    {
        $auth=Yii::app()->authManager;
        $dir = realpath(Yii::app()->basePath . DIRECTORY_SEPARATOR. 'modules' . DIRECTORY_SEPARATOR . strtolower($module->name) . DIRECTORY_SEPARATOR . 'controllers'. DIRECTORY_SEPARATOR . 'admin');
        if (is_dir($dir)) {
            $files = CFileHelper::findFiles($dir, array('level'=>0));
            foreach ($files as $file) {
                $filename = basename($file, '.php');
                if (($pos = strpos($filename, 'Controller')) > 0) {
                    list($controller) = Yii::app()->createController(strtolower($module->name).'/admin/'.substr($filename, 0, $pos));
                    $controllerSpecs = $controller->accessSpecs();
                    if (isset($controllerSpecs['operations'])) {
                        foreach ($controllerSpecs['operations'] as $key=>$value) {

                            if (!empty($value)&&is_string($value)) {
                                $operationName = strtolower($module->name).'/admin/'.strtolower(substr($filename, 0, $pos)).'.'.$key;
                                if (Yii::app()->authManager->getAuthItem($operationName)==null) {
                                    $auth->createOperation($operationName, $module->title.' - '.$controller->controllerName.' - '.$value);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* TODO: move to bootstrap widget */
    public function getItemsArray($attribute)
    {
        $items = array();
        $selectedItems = array();
        $operationBlockTitle = '';
        $item = array();
        $selectedItem = array();
        foreach (Yii::app()->authManager->operations as $operation) {
            $names = explode('-', $operation->description);
            if ($operationBlockTitle!=''&&$operationBlockTitle!=trim($names[0])) {
                $items[] = array(
                    'key'=>$operationBlockTitle,
                    'values'=>$item
                );
                $item = array();
                // for selected
                $selectedItems[] = array(
                    'key'=>$operationBlockTitle,
                    'values'=>$selectedItem
                );
                $selectedItem = array();
            }
            $item[] = array(
                'key'=>$operation->description,
                'value'=>$operation->name,
            );
            if (in_array($operation->name, $this->operations)) {
                $selectedItem[] = array(
                    'key'=>$operation->description,
                    'value'=>$operation->name,
                );
            }
            $operationBlockTitle = trim($names[0]);
        }
        $items[] = array(
            'key'=>$operationBlockTitle,
            'values'=>$item
        );
        $selectedItems[] = array(
            'key'=>$operationBlockTitle,
            'values'=>$selectedItem
        );

        Yii::app()->bootstrap->registerCoreScripts();
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl."/js/underscore-min.js");
        Yii::app()->bootstrap->registerAssetCss("bootstrap-listTree.css");
        Yii::app()->bootstrap->registerAssetJs("bootstrap-listTree.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScript('bootstrap-listTree',"
        var data = ".CJSON::encode($items).";
        var dataSelected = ".CJSON::encode($selectedItems).";
        $(document).on('click', '.btn-success', function(e) {
                e.preventDefault();
                $('.listTree').listTree('selectAll');
            }).on('click', '.btn-danger', function(e) {
                e.preventDefault();
                $('.listTree').listTree('deselectAll');
            }).on('click', '.btn-info', function(e) {
                e.preventDefault();
                $('.listTree').listTree('expandAll');
            }).on('click', '.btn-warning', function(e) {
                e.preventDefault();
                $('.listTree').listTree('collapseAll');
            });

        $('.listTree').listTree(data, { \"startCollapsed\": true, \"selected\": dataSelected , \"model\": '".get_class($this)."', \"attribute\": '".$attribute."'});
        ");
    }
}

