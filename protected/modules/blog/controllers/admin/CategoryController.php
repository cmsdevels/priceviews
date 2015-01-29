<?php

class CategoryController extends CmsAdminController
{
    protected $_controllerName = 'Categories';

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>'Список категорий',
                'update'=>'Редактирование категории',
                'create'=>'Создание категории',
                'delete'=>'Удаление категории',
                'statusList'=>array(
                    'access'=>'isAdmin'
                ),
                'updateField'=>array(
                    'access'=>'isAdmin'
                ),
            ),
        );
    }

    public $menuTitle = "Модуль Блог";
    public $menu = array(
        array('label'=>'Управление категориями', 'url'=>array('admin/category/index')),
        array('label'=>'Управление записями', 'url'=>array('admin/post/index')),
    );
    public $subMenuTitle = "Действия";
    public $subMenu = array(
        array('label'=>'Добавить категорию', 'url'=>array('admin/category/create')),
        array('label'=>'Добавить запись', 'url'=>array('admin/post/create'))
    );


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new PostCat;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PostCat']))
		{
			$model->attributes=$_POST['PostCat'];
			if($model->save()) {
                if(isset($_POST['apply'])) {
                    $this->redirect(array('update', 'id'=>$model->id));
                } else
                    $this->redirect(array('index'));
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PostCat']))
		{
			$model->attributes=$_POST['PostCat'];
			if($model->save())
            {
                if(!isset($_POST['apply']))
                    $this->redirect(array('index'));
            }

		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

    public function actionUpdateField()
    {
        Yii::import('ext.bootstrap.widgets.TbEditableSaver');
        $es = new TbEditableSaver('PostCat');
        $es->update();
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new PostCat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PostCat']))
			$model->attributes=$_GET['PostCat'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

    public function actionStatusList()
    {
        $statuses = array(
            array('value' => PostCat::STATUS_NO_PUBLISHED, 'text'=>'Не опубликовано'),
            array('value' => PostCat::STATUS_PUBLISHED, 'text'=>'Опубликовано'),
        );
        echo CJSON::encode($statuses);
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PostCat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PostCat::model()->multilang()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PostCat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-cat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
