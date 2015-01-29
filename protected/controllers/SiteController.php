<?php

class SiteController extends CmsController
{
	public function actionIndex()
	{
        if (isset(Yii::app()->params['forward']) && Yii::app()->params['forward'] !== 'index')
            $this->forward(Yii::app()->params['forward']);
        else
            $this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else {
                if (Yii::app()->user->isAdmin)
                    $this->layout = '//admin/layouts/main';
                $this->render('error', $error);
            }
		}
	}
}