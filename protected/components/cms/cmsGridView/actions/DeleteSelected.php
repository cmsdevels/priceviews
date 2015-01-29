<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10.09.13
 * Time: 16:34
 */

class DeleteSelected extends CAction
{
    public $modelName;

    public function run()
    {
        if (isset($_POST['ids'])) {
            $ids = $_POST['ids'];

            exit();
        } else
            throw new CHttpException(404, 'Page not found');
    }

} 