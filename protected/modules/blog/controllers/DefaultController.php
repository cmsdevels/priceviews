<?php

class DefaultController extends CmsController
{

	public function actionIndex()
	{
        $dataProvider=new CActiveDataProvider(Post::model()->published(), array(
            'pagination'=>array(
                'pageSize'=>4,
            ),
        ));
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
	}

    public function actionView($seo_link, $cat_link=null)
    {
        if ($cat_link===null) {
            $category = PostCat::model()->published()->findByAttributes(array('seo_link'=>$seo_link));
            if ($category===null) {
                $post = Post::model()->published()->findByAttributes(array('seo_link'=>$seo_link));
                if ($post===null)
                    throw new CHttpException(404,'The requested page does not exist.');
                $this->render('view',array(
                    'model'=>$post,
                ));
            } else {
                $criteria = new CDbCriteria();
                $criteria->compare('cat_id',$category->id);

                $dataProvider=new CActiveDataProvider('Post', array(
                    'criteria'=>$criteria
                ));

                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            }
        } else {
            $post = Post::model()->published()->findByAttributes(array('seo_link'=>$seo_link));
            if ($post===null)
                throw new CHttpException(404,'The requested page does not exist.');
            $this->render('view',array(
                'model'=>$post,
            ));
        }
    }

    public function actionTag($name)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('tags', $name, true);

        $dataProvider=new CActiveDataProvider('Post', array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    public function actionSearch()
    {
        if (isset($_POST['search'])){
            $criteria = new CDbCriteria();
            $criteria->compare('content',$_POST['search'],true);
            $criteria->compare('status',Post::STATUS_PUBLISHED);
            $dataProvider = new CActiveDataProvider('Post',array(
                'criteria'=>$criteria,
            ));
            $this->render('index',array(
                'dataProvider'=>$dataProvider,
            ));
        }
    }
}