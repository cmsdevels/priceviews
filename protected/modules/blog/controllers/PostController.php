<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 16.07.13
 * Time: 17:21
 * To change this template use File | Settings | File Templates.
 */

class PostController extends CmsController
{
    public function actionView($id)
    {
        $post = Post::model()->published()->findByPk($id);
        if ($post===null)
            throw new CHttpException(404,'The requested page does not exist.');
        $this->render('view',array(
            'model'=>$post,
        ));
    }
}