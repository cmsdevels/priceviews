<?php

class SettingsController extends CmsAdminController
{
    protected $_controllerName = 'Site settings';

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>Yii::t('core/admin','Manage settings'),
            ),
        );
    }

    public function getMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin','All params description'), 'url'=>array('descriptions')),
        );
    }

    public function getSubMenu()
    {
        return array(
                array('label'=> Yii::t('core/admin','Create settings param'), 'url'=>array('create')),
        );
    }

    public function actionIndex()
    {
        $model = new ParamsForm();
        if (isset($_POST['ParamsForm'])) {
            $model->attributes = $_POST['ParamsForm'];
            if ($model->validate() && $model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('core/admin', 'Settings saved successfully'));
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array(
            'model'=>$model,
        ));
    }

    public function actionDescriptions()
    {
        $model = new Params();
        $this->render('descriptions', array(
            'model'=>$model
        ));
    }

    public function actionCreate()
    {
        $model = new Params();
        if (isset($_POST['Params'])) {
            $model->attributes = $_POST['Params'];
            if ($model->save())
                $this->redirect(array('descriptions'));
        }
        $this->render('create', array('model'=>$model));

    }

    public function actionUpdateField()
    {
        Yii::import('ext.bootstrap.widgets.TbEditableSaver');
        $es = new TbEditableSaver('Params');
        $es->update();
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        try {
            $model->delete();
            $paramsFile = Yii::app()->basePath.'/config/params.php';
            $params = require($paramsFile);
            unset($params[$model->key]);

            $configString = "<?php\n return ".var_export($params, true)." ;\n?>";
            $config = fopen($paramsFile, 'w+');
            if ($config)
            {
                fwrite($config, $configString);
                fclose($config);
                @chmod($paramsFile, 0666);
            }
        } catch (Exception $e) {

        }


        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function loadModel($id)
    {
        $model=Params::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}
?>

