<?php
/**
 * Created by PhpStorm.
 * User: Sergiy
 * Company: http://web-logic.biz/
 * Email: sirogabox@gmail.com
 * Date: 06.08.14
 * Time: 11:58
 */
/* Example code
        $search = Yii::app()->request->getParam('search');
        $autoCompleteSettings=array(
            'source' =>Yii::app()->createUrl('/shop/category/searchAutoComplete'),
            'name'=>'search',
            'renderItem'=>"function( ul, item ) {
                   return renderAutoCompleteItemSearch(ul, item)}",
            'renderMenu'=>'function( ul, items ) {
                var that = this;
                   renderAutoCompleteMenuSearch(ul, items,that);
            }',
            'value'=>$search,
            'options'=>array(
                // минимальное кол-во символов, после которого начнется поиск
                'minLength'=>'2',
                'showAnim'=>'fold',
                // обработчик события, выбор пункта из списка
                'select' =>'js: function(event, ui) {
                        // действие по умолчанию, значение текстового поля
                        // устанавливается в значение выбранного пункта
                        if(ui.item!==undefined){
                          this.value = ui.item.value;
                          if (ui.item.url!=""){
                              location.href = ui.item.url;
                          }
                        }
                        return false;
                    }',
            ),
            'htmlOptions' => array(
                'name'=>'search',
                'class'=>'siteSearch',
                'maxlength'=>100,
                'placeholder'=>"Поиск по сайту...",
                'value'=>$search,
                'onfocus'=>"if (this.value == 'Поиск по сайту') this.value = '';",
            ),
        );
        $this->widget('application.components.cms.CmsAutoCompleteWidget', $autoCompleteSettings);

<script type="text/javascript">

    function renderAutoCompleteMenuSearch(ul,items,that){

        $.each( items, function( index, item ) {
            that._renderItemData( ul, item );
        });

        var header = "<li class='autocomplete_header'><span >Модели</span></li>";
        var footerLi = "<li class='autocomplete_footer'><a href='#' onclick='viewAllSearchButton()'>Все результаты</a></li>";
        $(ul).prepend(header);
        $(ul).append(footerLi);
        $( ul ).find( "li:odd" ).addClass( "odd" );
    }
    function renderAutoCompleteItemSearch(ul,item){
        var link =
            '<div class="autocomplete_item">' +
                '<div class="item_img">' +
                    '<img src="'+item.imgSrc+'" >' +
                '</div>' +
                '<div class="item_content"> ' +
                    '<a href='+item.url+'>'+
                        '<span class="title">'+
                            item.label+
                        '</span>'+
                        '<span class="price">'+
                            item.price+' <?php echo Good::model()->getCurrency()?>'+
                        '</span>'+
                    '</a>' +
                '</div>' +
            '</div>';

        return $( '<li style="width: 362px;min-height: 75px;"></li>' )
            .append(link)
            .appendTo( ul );
    }
</script>
 */
Yii::import('zii.widgets.jui.CJuiAutoComplete');
class CmsAutoCompleteWidget extends CJuiAutoComplete{

    public $renderItem = "function( ul, item ) {
        return $( '<li></li>' )
        .append( '<a>' + item.label +'</a>')
        .appendTo( ul );}";

    public $renderMenu = 'function( ul, items ) {
            var that = this;
            $.each( items, function( index, item ) {
                that._renderItemData( ul, item );
            });
            console.log(1);
            var header = "<li>Модели</li>";
            $(ul).append(header);
            $( ul ).find( "li:odd" ).addClass( "odd" );
        }';

    public function run()
    {
        list($name,$id)=$this->resolveNameID();

        if(isset($this->htmlOptions['id']))
            $id=$this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
        if(isset($this->htmlOptions['name']))
            $name=$this->htmlOptions['name'];

        if($this->hasModel())
            echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
        else
            echo CHtml::textField($name,$this->value,$this->htmlOptions);

        if($this->sourceUrl!==null)
            $this->options['source']=CHtml::normalizeUrl($this->sourceUrl);
        else
            $this->options['source']=$this->source;



        $options=CJavaScript::encode($this->options);


        Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"var auto = jQuery('#{$id}')
            .autocomplete($options);
            auto.data('ui-autocomplete')._renderMenu = ".$this->renderMenu.";
            auto.data('ui-autocomplete')._renderItem = ".$this->renderItem.";");
    }



} 