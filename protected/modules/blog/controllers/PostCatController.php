<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 19.07.13
 * Time: 10:01
 * To change this template use File | Settings | File Templates.
 */

class PostCatController extends CmsController
{
    public function actionView($id)
    {
        $model = PostCat::model()->findByPk($id);
        $this->render('view', array('model'=>$model));
    }

    public function actionViewAllSub($id)
    {
        $model = PostCat::model()->findByPk($id);
        $dataProvider = new CArrayDataProvider($model->children, array(
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
        $this->render('allSub', array('model'=>$model, 'dataProvider'=>$dataProvider));
    }

    public function actionViewSubCategories($id)
    {
        $model = PostCat::model()->findByPk($id);
        $dataProvider = new CArrayDataProvider($model->getChildrenByModel('PostCat'));
        $this->render('categories', array('model'=>$model, 'dataProvider'=>$dataProvider));
    }

    public function actionViewPreview($id)
    {
        $model = PostCat::model()->findByPk($id);
        $dataProvider = new CArrayDataProvider($model->getChildrenByModel('Post'));
        $this->render('prevPosts', array('model'=>$model, 'dataProvider'=>$dataProvider));
    }
}