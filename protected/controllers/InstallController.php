<?php

class InstallController extends CmsController
{
    public $layout='//admin/layouts/main';

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index'),
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        Yii::app()->session->clear();
        if (file_exists(Yii::app()->basePath.'/config/main.php'))
            $this->redirect(array('success'));
        $model = new Install();

        if (isset($_POST['Install']))
        {
            $model->attributes = $_POST['Install'];
            if ($model->setup())
                $this->redirect(array('success'));
        }

        $this->render('index', array('model'=>$model));
    }

    public function actionSuccess()
    {
        $this->render('success');
    }
}