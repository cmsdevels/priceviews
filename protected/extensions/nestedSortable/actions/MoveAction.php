<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10.10.14
 * Time: 14:46
 */

class MoveAction extends CAction
{
    public $alias = null;
    public $model;
    public $parentAttribute = 'parent_id';
    public $returnAttributes = array();

    public function run()
    {
        if ($this->alias !== null)
            Yii::import($this->alias);
        if (isset($_POST['id'])&&isset($_POST['parent_id'])&&isset($_POST['prev_id'])) {
            $model = CActiveRecord::model($this->model)->findByPk($_POST['id']);
            if ($model===null)
                throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));

            if ($_POST['parent_id'] && $_POST['prev_id'] && $_POST['parent_id']==$_POST['prev_id']) {
                $parent = CActiveRecord::model($this->model)->findByPk($_POST['parent_id']);
                if ($parent===null)
                    throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));
                $model->moveAsFirst($parent);
            } elseif ($_POST['parent_id']) {
                $prev = CActiveRecord::model($this->model)->findByPk($_POST['prev_id']);
                if ($prev===null)
                    throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));
                $model->moveAfter($prev);
            } elseif (!$model->isRoot()) {
                $model->moveAsRoot();
            }
            if ($this->parentAttribute && ($model->hasAttribute($this->parentAttribute) || $model->hasProperty($this->parentAttribute)))
                $model->{$this->parentAttribute} = $model->isRoot() ? null : $model->parent->id;
            $model->saveNode();
            if (!empty($this->returnAttributes)) {
                $returnValues = array();
                foreach ($this->returnAttributes as $attribute=>$options) {
                    if (!isset($options['tree']) || $options['tree'] === false)
                        $returnValues[$attribute] = $model->{$attribute};
                    else {
                        $tree = array(
                            array(
                                'id'=>$model->id,
                                'value'=>$model->{$attribute}
                            )
                        );
                        foreach ($model->descendants()->findAll() as $node)
                            $tree[] = array(
                                'id'=>$node->id,
                                'value'=>$node->{$attribute}
                            );
                        $returnValues[$attribute] = $tree;
                    }
                }
                echo json_encode($returnValues);
                Yii::app()->end();
            }
        }
    }
} 