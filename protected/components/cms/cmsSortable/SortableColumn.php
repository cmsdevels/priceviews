<?php
Yii::import('zii.widgets.grid.CGridColumn');

class SortableColumn extends CGridColumn
{
    public $name;

    public $sortable=true;

    public $editableOrdering = false;
    /**
     * @var array the HTML options for the data cell tags.
     */
    public $htmlOptions=array('class'=>'button-column');
    /**
     * @var array the HTML options for the header cell tag.
     */
    public $headerHtmlOptions=array('class'=>'button-column');
    /**
     * @var array the HTML options for the footer cell tag.
     */
    public $footerHtmlOptions=array('class'=>'button-column');

    public $template='{ordering}';

    /**
     * @var string the label for the delete button. Defaults to "Delete".
     * Note that the label will not be HTML-encoded when rendering.
     */
    public $buttonLabel;
    /**
     * @var string the image URL for the button. If not set, an integrated image will be used.
     * You may set this property to be false to render a text link instead.
     */
    /**
     * @var string a PHP expression that is evaluated for every button and whose result is used
     * as the URL for the button. In this expression, the variable
     * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
     * and <code>$this</code> the column object.
     */
    public $buttonUrl='Yii::app()->controller->createUrl("ordering", array("id"=>$data->primaryKey))';
    /**
     * @var array the HTML options for the view button tag.
     */
    public $buttonOptions=array('class'=>'ordering');
    /**
     * @var string a javascript function that will be invoked after the delete ajax call.
     * This property is used only if <code>$this->buttons['delete']['click']</code> is not set.
     *
     * The function signature is <code>function(link, success, data)</code>
     * <ul>
     * <li><code>link</code> references the delete link.</li>
     * <li><code>success</code> status of the ajax call, true if the ajax call was successful, false if the ajax call failed.
     * <li><code>data</code> the data returned by the server in case of a successful call or XHR object in case of error.
     * </ul>
     * Note that if success is true it does not mean that the delete was successful, it only means that the ajax call was successful.
     *
     * Example:
     * <pre>
     *  array(
     *     class'=>'CButtonColumn',
     *     'afterDelete'=>'function(link,success,data){ if(success) alert("Delete completed successfuly"); }',
     *  ),
     * </pre>
     */
    public $afterRequest;

    public $button=array();
    /**
     * @var string the base script URL for all grid view resources (e.g. javascript, CSS file, images).
     * Defaults to null, meaning using the integrated grid view resources (which are published as assets).
     */
    public $baseScriptUrl;

    public $upIcon = 'up.png';
    public $downIcon = 'down.png';

    public $route = 'ordering';

    /**
     * Initializes the column.
     * This method registers necessary client script for the button column.
     */
    public function init()
    {
        if ($this->header===null)
            $this->header = CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
        if($this->baseScriptUrl===null)
            $this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.components.cms.cmsSortable.assets')).DIRECTORY_SEPARATOR;

        if($this->name===null)
            throw new CException(Yii::t('OrderingColumn','"name" must be specified for OrderingColumn.'));

        $this->initDefaultButton();

        if(isset($button['click']))
        {
            if(!isset($this->button['options']['class']))
                $this->button['options']['class']='status';
            if(strpos($this->button['click'],'js:')!==0)
                $this->button['click']='js:'.$this->button['click'];
        }

        $this->registerClientScript();
    }

    /**
     * Initializes the default button.
     */
    protected function initDefaultButton()
    {
        if($this->buttonLabel===null)
            $this->buttonLabel=Yii::t('OrderingColumn','Ordering');

        $this->button=array(
            'label'=>$this->buttonLabel,
            'url'=>$this->buttonUrl,
            'options'=>$this->buttonOptions,
        );

        if(!isset($this->button['click']))
        {

            if(Yii::app()->request->enableCsrfValidation)
            {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            }
            else
                $csrf = '';

            if($this->afterRequest===null)
                $this->afterRequest='function(){}';

            $this->button['click']=<<<EOD
function() {
	var th=this;
	var afterRequest=$this->afterRequest;
	$.fn.yiiGridView.update('{$this->grid->id}', {
		type:'POST',
		url:$(this).attr('href'),$csrf
		success:function(data) {
			$.fn.yiiGridView.update('{$this->grid->id}');
			afterRequest(th,true,data);
		},
		error:function(XHR) {
			return afterRequest(th,false,XHR);
		}
	});
	return false;
}
EOD;
        }
    }

    /**
     * Registers the client scripts for the button column.
     */
    protected function registerClientScript()
    {
        $js=array();
        if(isset($this->button['click']))
        {
            $function=$this->button['click'];
            $class=preg_replace('/\s+/','.',$this->button['options']['class']);
            $js[]="jQuery('#{$this->grid->id} a.{$class}').live('click',$function);";
        }

        if($js!==array())
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id, implode("\n",$js));
        if ($this->editableOrdering) {
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id.'_setOrdering', "
                $(document).on('submit', 'form.setOrdering', function (e) {
                    e.preventDefault();
                    var url = $(this).attr('action');
                    var data = $(this).serialize();
                    var th=this;
                    var afterRequest=$this->afterRequest;
                    $.fn.yiiGridView.update('{$this->grid->id}', {
                        type:'POST',
                        url:url,
                        data: data,
                        success:function(data) {
                            $.fn.yiiGridView.update('{$this->grid->id}');
                            afterRequest(th,true,data);
                        },
                        error:function(XHR) {
                            return afterRequest(th,false,XHR);
                        }
                    });

                });
            ");
        }
    }

    /**
     * Renders the data cell content.
     * This method renders the view, update and delete buttons in the data cell.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row,$data)
    {
        $tr=array();
        ob_start();
        $this->renderButton($this->id,$this->button,$row,$data);
        $tr['{ordering}']=ob_get_contents();
        ob_clean();
        ob_end_clean();
        echo strtr($this->template,$tr);
    }

    /**
     * Renders a link button.
     * @param string $id the ID of the button
     * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
     * See {@link buttons} for more details.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data object associated with the row
     */
    protected function renderButton($id,$button,$row,$data)
    {
        $value=CHtml::value($data,$this->name);
        $totalCountItems = $this->grid->dataProvider->getTotalItemCount();
        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'],array('row'=>$row,'data'=>$data)))
            return;
        $label=isset($button['label']) ? $button['label'] : $id;
        $url=isset($button['url']) ? $this->evaluateExpression($button['url'],array('data'=>$data,'row'=>$row)) : '#';
        $options=isset($button['options']) ? $button['options'] : array();
        if(!isset($options['title']))
            $options['title']=$label;
        if (!$this->editableOrdering && $value != 1)
            echo CHtml::link(
                CHtml::image($this->baseScriptUrl.$this->upIcon),
                Yii::app()->controller->createUrl($this->route, array("id"=>$data->primaryKey, 'action'=>'up', 'attribute'=>$this->name)),
                $this->buttonOptions
            );
        if ($this->editableOrdering) {
            echo CHtml::beginForm(
                Yii::app()->controller->createUrl($this->route, array("id"=>$data->primaryKey, 'action'=>'setValue', 'attribute'=>$this->name)),
                'post',
                array(
                    'class'=>'setOrdering'
                )
            );
            echo CHtml::textField('orderValue', $value, array(
                'style'=>'width: 35px',
                'class'=>'ordering',
            ));
            echo CHtml::endForm();
        }
        if (!$this->editableOrdering && $value != $totalCountItems)
            echo CHtml::link(
                CHtml::image($this->baseScriptUrl.$this->downIcon),
                Yii::app()->controller->createUrl($this->route, array("id"=>$data->primaryKey, 'action'=>'down', 'attribute'=>$this->name)),
                $this->buttonOptions
            );
    }

    protected function renderHeaderCellContent()
    {
        if($this->grid->enableSorting && $this->sortable && $this->name!==null)
            echo $this->grid->dataProvider->getSort()->link($this->name,$this->header,array('class'=>'sort-link'));
        elseif($this->name!==null && $this->header===null)
        {
            if($this->grid->dataProvider instanceof CActiveDataProvider)
                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
            else
                echo CHtml::encode($this->name);
        }
        else
            parent::renderHeaderCellContent();
    }
} 