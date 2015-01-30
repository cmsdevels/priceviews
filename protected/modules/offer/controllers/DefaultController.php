<?php

class DefaultController extends CmsController
{
	public function actionIndex()
	{
		$this->render('index');
	}
}