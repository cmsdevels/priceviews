<?php
/**
 * Created by PhpStorm.
 * User: Sergiy
 * Company: http://web-logic.biz/
 * Email: sirogabox@gmail.com
 * Date: 02.06.14
 * Time: 12:08
 */
Yii::import('application.components.cms.cmsGridView.*');
Yii::import('bootstrap.widgets.*');
class TbBulkActionsCms extends TbBulkActions{

    protected function attachCheckBoxColumn()
    {
        $dataProvider = $this->grid->dataProvider;
        $columnName = null;

        if (!isset($this->checkBoxColumnConfig['name']))
        {
            // supports two types of DataProviders
            if ($dataProvider instanceof CActiveDataProvider)
            {
                // we need to get the name of the key field 'by default'
                if (is_string($dataProvider->modelClass))
                {
                    $modelClass = $dataProvider->modelClass;
                    $model = CActiveRecord::model($modelClass);
                } elseif ($dataProvider->modelClass instanceof CActiveRecord)
                {
                    $model = $dataProvider->modelClass;
                }
                $table = $model->tableSchema;
                if (is_string($table->primaryKey))
                    $columnName = $this->{$table->primaryKey};
                else if (is_array($table->primaryKey))
                {
                    $columnName = $table->primaryKey[0]; // just get the first one
                }
            }
            if ($dataProvider instanceof CArrayDataProvider)
            {
                $columnName = $dataProvider->keyField; // key Field
            }
        }
        // create CCheckBoxColumn and attach to columns at its beginning
        $column = CMap::mergeArray(array(
            'class' => 'application.components.cms.cmsGridView.CCheckBoxColumnCms',
            'name' => $columnName,
            'selectableRows' => 2
        ), $this->checkBoxColumnConfig);

        array_unshift($this->grid->columns, $column);
        $this->columnName = $this->grid->id . '_c0\[\]'; //
    }

} 