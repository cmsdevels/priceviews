<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 25.01.13
 * Time: 9:53
 * To change this template use File | Settings | File Templates.
 */

class BlogSearchWidget extends CWidget{

    public function init()
    {
        echo '<div class="search_form">';
            echo '<div class="title">ПОИСК ПО БЛОГУ</div>';
            echo CHtml::beginForm(array('/blog/default/search'));
                echo CHtml::textField('search','',array(
                    'placeholder'=>"Поисковая фраза...",
                ));
                echo CHtml::submitButton('');
            echo CHtml::endForm();
        echo '</div>';
    }


}