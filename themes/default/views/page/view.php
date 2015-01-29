<?php
/**
 * Created by PhpStorm.
 * User: Sergiy
 * Company: http://web-logic.biz/
 * Email: sirogabox@gmail.com
 * Date: 25.04.14
 * Time: 12:14
 * @var $model Page
 */
?>
<div class="container">
    <div class="block_news">
        <div class="news_items">
            <div class="news_item">
                <p class="item_name"><?php echo $model->title?></p>
                <div class="item_info">
                    <a href="#" class="author"><img src="<?php echo Yii::app()->request->baseUrl.'/themes/default/images/default_author_image.png'; ?>"><span><?php echo $model->author->username?></span></a>
                    <p class="date_create"><?php echo Yii::app()->dateFormatter->format('dd MMM yyyy HH:mm',$model->pub_date)?></p>
                </div>
                <div class="news_item_content">
<!--                    --><?php //echo CHtml::image(Yii::app()->request->baseUrl.'/upload/news/'.$model->image,$model->title)?>
                    <?php echo $model->content?>
                </div>
            </div>
        </div>
    </div>
</div>