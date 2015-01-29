<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 18.07.13
 * Time: 11:25
 * To change this template use File | Settings | File Templates.
 */

class PageController extends CmsController
{
    protected $_controllerName = 'Page';

    public function accessSpecs()
    {
        return array(
            'operations' => array(
                'view' => Yii::t('core/admin', 'View page')
            )
        );
    }

    public function actionView($id)
    {
        $model = Page::model()->findByPk($id);
        if($model==null){
            throw new CHttpException(404, Yii::t('core/site','The requested page does not exist.'));
        }
        $this->_model = $model;
        $this->render('view', array('model'=>$model));
    }
}