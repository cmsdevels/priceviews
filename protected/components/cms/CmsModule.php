<?php
/**
 * CmsModule.php.
 * @author Yuriy Firs <firs.yura@gmail.com>
 */
class CmsModule extends CWebModule
{
    /**
     * Module installation
     * @return bool
     */
    public function install()
    {
        if ($this->checkDepends())
        {
            $runner=new CConsoleCommandRunner();
            $moduleName = strtolower($this->name);
            $runner->commands=array(
                'migrate' => array(
                    'class' => 'system.cli.commands.MigrateCommand',
                    'migrationPath' => 'application.modules.'.$moduleName.'.migrations',
                    'migrationTable' => '{{migration_'.$moduleName.'}}',
                    'interactive' => false,
                ),
            );

            ob_start();
            $runner->run(array(
                'yiic',
                'migrate',
            ));
            ob_get_clean();
            return true;
        } else
            return false;

    }

    /**
     * Module uninstallation
     * @return bool
     */
    public function uninstall()
    {
        if ($this->checkChildrenDepends())
        {
            $runner=new CConsoleCommandRunner();
            $moduleName = strtolower($this->name);
            $runner->commands=array(
                'migrate' => array(
                    'class' => 'system.cli.commands.MigrateCommand',
                    'migrationPath' => 'application.modules.'.$moduleName.'.migrations',
                    'migrationTable' => '{{migration_'.$moduleName.'}}',
                    'interactive' => false,
                ),
            );

            ob_start();
            $runner->run(array(
                'yiic',
                'migrate',
                'to',
                '000000_000000',
            ));
            ob_get_clean();
            return true;
        } else
            return false;
    }

    public $depends = array();

    public function checkDepends()
    {
        if (empty($this->depends))
            return true;
        else {
            if (isset($this->depends['modules'])&&is_array($this->depends['modules']))
            {

                foreach ($this->depends['modules'] as $module)
                {
                    if (Yii::app()->getModule(strtolower($module))===null)
                    {
                        $this->installError = Yii::t('core/admin', 'Module')."<b>".$this->id."</b> ".Yii::t('core/admin', 'depends on the module')." <b>".$module."</b>. ".Yii::t('core/admin', 'You must install it first.');
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function checkChildrenDepends()
    {
        foreach(Yii::app()->modules as $key=>$value)
        {
            $module = Yii::app()->getModule($key);
            if (isset($module->depends)&&!empty($module->depends))
            {
                // For modules
                if (isset($module->depends['modules'])&&is_array($module->depends['modules']))
                {
                    foreach ($module->depends['modules'] as $dependModule)
                    {
                        if ($dependModule==$this->id)
                        {
                            $this->installError = Yii::t('core/admin', 'Module')."<b>".$module->id."</b> ".Yii::t('core/admin', 'depends on the module')." <b>".$this->id."</b>. ".Yii::t('core/admin', 'You must remove it first.');
                            return false;
                        }
                    }
                }
            }

        }
        return true;
    }

    protected $_installError = null;

    public function setInstallError($error)
    {
        if ($this->_installError===null)
            $this->_installError = $error;
    }

    public function getInstallError()
    {
        return $this->_installError;
    }

    /**
     * Return default editor widget for textarea fields
     * @param $model
     * @param $attribute
     * @return mixed
     */
    public function editorWidget($model, $attribute)
    {
        return Yii::app()->controller->widget('application.extensions.extckeditor.ExtCKEditor', array(
            'model'=>$model,
            'attribute'=>$attribute, // model atribute
            'language'=>'en', /* default lang, If not declared the language of the project will be used in case of using multiple languages */
            'editorTemplate'=>'full', // Toolbar settings (full, basic, advanced)
        ),true);
    }
}
