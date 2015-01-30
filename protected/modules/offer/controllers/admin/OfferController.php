<?php

class OfferController extends CmsAdminController
{

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>'Список записей',
                'update'=>'Редактирование записи',
                'create'=>'Создание записи',
                'delete'=>'Удаление записи',
                // 'statusList'=>array(
                //     'access'=>'isAdmin'
                // ),
                // 'updateField'=>array(
                //     'access'=>'isAdmin'
                // ),
                'updateField'=>array(
                    'access'=>'isAdmin'
                ),
                'listMethods'=>array(
                    'access'=>'isAdmin'
                ),
            ),
        );
    }

    public $menuTitle = "Название модуля";
    public $menu = array(
        array('label'=>'Управление записями', 'url'=>array('/admin/offer/offer/index')),
        array('label'=>'Управление товарами', 'url'=>array('/admin/offer/offerItems/index')),
    );
    public $subMenuTitle = "Действия";
    public $subMenu = array(
        array('label'=>'Добавить запись', 'url'=>array('/admin/offer/offer/create')),
        array('label'=>'Добавить товар', 'url'=>array('/admin/offer/offerItems/create')),
    );

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Offer;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Offer']))
		{
			$model->attributes=$_POST['Offer'];

            if($model->save())
                if(isset($_POST['apply'])){
                    $this->redirect(array('update', 'id'=>$model->id));
                }else{
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



		if(isset($_POST['Offer']))
		{
			$model->attributes=$_POST['Offer'];

            if($model->save())
                if(isset($_POST['apply'])){
                    $this->redirect(array('update', 'id'=>$model->id));
                }else{
                    $this->redirect(array('index'));
                }
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
        $model=new Offer('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Offer']))
        $model->attributes=$_GET['Offer'];

        $this->render('index',array(
        'model'=>$model,
        ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Offer the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Offer::model()->findByPk($id);
		// $model=Offer::model()->multilang()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Offer $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='offer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionStatusList()
    {
        $statuses = array(
            array('value' => Offer::STATUS_NO_ACTIVE, 'text'=>'Не актинвный'),
            array('value' => Offer::STATUS_ACTIVE, 'text'=>'Активный'),
        );
        echo CJSON::encode($statuses);
    }
    public function actionUpdateField()
    {
        Yii::import('ext.bootstrap.widgets.TbEditableSaver');
        $es = new TbEditableSaver('Offer');
        $es->update();
    }
}
