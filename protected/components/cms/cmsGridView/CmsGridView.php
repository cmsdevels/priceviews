<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10.09.13
 * Time: 12:20
 */

Yii::import('bootstrap.widgets.*');
Yii::import('application.components.cms.cmsGridView.*');

class CmsGridView extends TbExtendedGridViewCms
{
    public $groupActions = null;
    protected $groupSelect = null;

    public function renderItems()
    {
        if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
        {
            echo "<table class=\"{$this->itemsCssClass} custom-table ".($this->filter!==null ? 'has-filter' : '')."\">\n";
            $this->renderTableHeader();
            ob_start();
            $this->renderTableBody();
            $body=ob_get_clean();
            $this->renderTableFooter();
            echo $body; // TFOOT must appear before TBODY according to the standard.
            echo "</table>";
        }
        else
            $this->renderEmptyText();
    }

    public function renderTableRow($row)
    {
        if ($this->rowCssClassExpression !== null)
        {
            $data = $this->dataProvider->data[$row];
            echo '<tr class="' . $this->evaluateExpression($this->rowCssClassExpression, array('row' => $row, 'data' => $data)) . '">';
        } else if (is_array($this->rowCssClass) && ($n = count($this->rowCssClass)) > 0)
            echo '<tr class="' . $this->rowCssClass[$row % $n] . '">';
        else
            echo '<tr>';
        foreach ($this->columns as $column)
        {
            echo $this->displayExtendedSummary && !empty($this->extendedSummary['columns']) ? $this->parseColumnValue($column, $row) : $column->renderDataCell($row);
        }
        echo "</tr>\n";
    }

    /**
     * Renders the table footer.
     */
    public function renderTableFooter()
    {
        $hasFilter = $this->filter !== null && $this->filterPosition === self::FILTER_POS_FOOTER;

        $hasFooter = $this->getHasFooter();
        if ($this->bulk !== null || $hasFilter || $hasFooter || $this->groupActions !== null)
        {
            echo "<tfoot>\n";
            if ($hasFooter)
            {
                echo "<tr>\n";
                foreach ($this->columns as $column)
                    $column->renderFooterCell();
                echo "</tr>\n";
            }
            if ($hasFilter)
                $this->renderFilter();

            if ($this->bulk !== null)
                $this->renderBulkActions();
            if ($this->groupActions)
                $this->renderGroupActions();
            echo "</tfoot>\n";
        }
    }

    public function renderGroupActions()
    {
        $items = array();
        foreach ($this->groupActions as $key=>$value) {
            $items[$value['url']] = $value['label'];
        }
        echo '<tr><td colspan="' . count($this->columns) . '">';
        echo '<div style="float: left; margin-top: 10px; ">';
            echo '<span class="total_count_selected" style="display: inline-block; margin-right: 15px; ">Выбрано 0 елементов</span>';
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => Yii::t('core/admin', 'Выбрать все'),
                'htmlOptions' => array(
                    'class'=>'select_all'
                ),
            ));
        echo '</div>';
        echo '<div style="float: right; margin: 10px 0 0 0;">';
            echo CHtml::dropDownList($this->id.'_groups_operations', null, $items, array(
                'empty'=>'Выбрать действие',
                'style'=>'float: left; margin-right: 10px; display: inline-block;',
                'id'=>$this->id.'_groups_operations'
            ));
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => Yii::t('core/admin', 'Применить к выбраным'),
                'htmlOptions' => array(
                    'class'=>'group_apply',
                    'style'=>'float: left; '
                ),
            ));
        echo '</div>';
        echo '</td></tr>';
        $this->registerGroupOperationsScripts();
    }

    public function registerGroupOperationsScripts()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerScript('#'.$this->id.'_groupsOperations', "

            var selected_ids = new Array();
            var all_count = ".$this->dataProvider->getTotalItemCount().";
            var selected_all = false;

            $(document).on('change', '#".$this->id." input:checkbox', function () {
                selected_all = false;
                var th = $(this);
                var val = $(this).val();
                var id = th.attr('id');
                if (id.indexOf('all') != -1 && th.attr('checked') == 'checked') {
                    $('#".$this->id." input:checkbox:not(#'+id+')').each (function () {
                        var current_val = $(this).val();
                        if (selected_ids.indexOf(current_val) == -1)
                            selected_ids.push(current_val);
                    });
                } else if (id.indexOf('all') != -1 && th.attr('checked') == undefined) {
                    $('#".$this->id." input:checkbox:not(#'+id+')').each (function () {
                        var current_val = $(this).val();
                        var pos = selected_ids.indexOf(current_val);
                        if (pos != -1)
                            selected_ids.splice(pos, 1);
                    });
                }
                if (id.indexOf('all') == -1) {
                    if (th.attr('checked') == 'checked') {
                    if (selected_ids.indexOf(val) == -1)
                        selected_ids.push(val);
                    } else {
                        var pos = selected_ids.indexOf(val);
                        if (pos != -1)
                            selected_ids.splice(pos, 1);
                    }
                }
                $('#".$this->id." .total_count_selected').html('Выбрано '+ selected_ids.length +' елементов');
                console.log(selected_ids);
            });

            $('#".$this->id."').parent().on('ajaxUpdate.yiiGridView', '#".$this->id."', function () {
                $('#".$this->id." input:checkbox').each( function () {
                    selected_all = false;
                    var val = $(this).val();
                    var id = $(this).attr('id');
                    if (selected_ids.indexOf(val) != -1)
                        $(this).attr('checked', 'checked');
                });
                if (selected_ids.length > 0)
                    $('button.bulk-actions-btn').removeClass('disabled');
                $('#".$this->id." .total_count_selected').html('Выбрано '+ selected_ids.length +' елементов');
            });

            $(document).on('click', '#".$this->id." .group_apply', function (e) {
                e.preventDefault();
                var url = $('#".$this->id."_groups_operations').val();
                if (url != '')
                    $.post(
                        url,
                        {selected_all: selected_all, ids: selected_ids},
                        function () {
                            $.fn.yiiGridView.update('".$this->id."');
                        }
                    );
            });

            $(document).on('click', '#".$this->id." .select_all', function (e) {
                e.preventDefault();
                selected_all = true;
                selected_ids = [];
                $('#".$this->id." input:checkbox').each (function () {
                    $(this).attr('checked', 'checked');
                    if ($(this).attr('id').indexOf('all') == -1)
                        selected_ids.push($(this).val());
                });
                $('#".$this->id." .total_count_selected').html('Выбрано '+ all_count +' елементов');
                console.log(selected_ids);
            });
        ", CClientScript::POS_READY);
    }

    /**
     * Renders the key values of the data in a hidden tag.
     */
    public function renderKeys()
    {
        $data = $this->dataProvider->getData();

        if(!$this->sortableRows || !$this->getAttribute($data[0], $this->sortableAttribute))
        {
            return CGridView::renderKeys();
        }

        echo CHtml::openTag('div',array(
            'class'=>'keys',
            'style'=>'display:none',
            'title'=>Yii::app()->getRequest()->getUrl(),
        ));
        foreach($data as $d)
            echo CHtml::tag('span',array('data-order' => $this->getAttribute($d, $this->sortableAttribute), ), CHtml::encode($this->getPrimaryKey($d)));
        echo "</div>\n";
    }
} 