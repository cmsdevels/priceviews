<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 17.07.13
 * Time: 10:25
 * To change this template use File | Settings | File Templates.
 */

class StructureController extends CmsAdminController
{
    protected $_controllerName = 'Structure';

    private $_behaviorIDs = array();

    public function createAction($actionID)
    {
        $action = parent::createAction($actionID);
        if ($action !== null)
            return $action;
        foreach ($this->_behaviorIDs as $behaviorID) {
            $object = $this->asa($behaviorID);
            if ($object->getEnabled() && method_exists($object, 'action' . $actionID))
                return new CInlineAction($object, $actionID);
        }
    }

    public function attachBehavior($name, $behavior)
    {
        $this->_behaviorIDs[] = $name;
        parent::attachBehavior($name, $behavior);
    }

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>Yii::t('core/admin','Manage structure'),
                'create'=>Yii::t('core/admin','Create page'),
                'update'=>Yii::t('core/admin','Update page'),
                'delete'=>Yii::t('core/admin','Delete page'),
            ),
        );
    }

    public function getMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin', 'Manage Menu'), 'url'=>array('/admin/menu/index')),
            array('label'=>Yii::t('core/admin', 'Manage Pages'), 'url'=>array('/admin/page/index')),
            array('label'=>Yii::t('core/admin', 'Manage Modules'), 'url'=>array('/admin/module/index')),
        );
    }

    public function actionIndex()
    {
        $models = Route::model()->roots()->findAll(array('order'=>'status ASC'));
        $this->render('index', array('models'=>$models));
    }

    public function actionUpdateItem($id)
    {
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.synctranslit.js');
        $model = Route::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));
        if (isset($_POST['Route'])) {
            $model->attributes = $_POST['Route'];
            if ($model->saveNode()) {
                $model->updateUrl();
                $this->redirect(array('index'));
            }
        }
        if (is_string($model->admin_menu))
            $model->admin_menu = CJSON::decode($model->admin_menu);
        $this->render('updateItem', array('model'=>$model));
    }


    public function actionDeleteItem($id)
    {
        $model = Route::model()->findByPk($id);
        if ($model !== null)
            $model->deleteNode(true);
        else
            throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));
        $this->redirect(array('index'));
    }


    protected function afterAction($action) {
        if ($action->id=='move') {
            $moved_node_id = Yii::app()->request->getParam('id');
            if ($moved_node_id) {
                /**
                 * @var Route $model
                 */
                $model = Route::model()->findByPk($moved_node_id);
                if ($model!==null)
                    $model->updateUrl();
            }
        }
    }

    public function actions()
    {
        return array(
            'move'=>array(
                'class'=>'ext.nestedSortable.actions.MoveAction',
                'model'=>'Route',
                'returnAttributes'=>array(
                    'viewItemUrl' => array(
                        'tree'=>true
                    )
                )
            )
        );
    }
}