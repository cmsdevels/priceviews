<?php /* @var $this CmsController */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="<?php echo Yii::app()->language; ?>" />
    <title><?php echo $this->getSeoTitle(); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/default/css/style.css"/>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/default/js/main.js"></script>
</head>
<body>
<div class="main_container">
    <header>
        <div class="container">
            <div class="logo">
                <?php echo CHtml::link(
                    CHtml::image(Yii::app()->request->baseUrl."/themes/default/images/header_logo.png"),
                    array('/site/index')
                )?>
            </div>
            <div class="main_menu_header">
                <?php $this->position('top_menu')?>
            </div>
        </div>
    </header>
    <?php echo $content?>
    <div class="footer_helper"></div>
</div>
<footer>
    <div class="container">
        <div class="footer_logo">
            <?php echo CHtml::link(
                CHtml::image(Yii::app()->request->baseUrl.'/themes/default/images/footer_logo.png',Yii::app()->name),
                array('site/index')
            )?>
        </div>
        <div class="footer_info" style="display: none">
            <div class="developer_info">
                <p>Copyright © 2009 - 2014. All Rights Reserved.</p>
                <a href="#">Разработка web-logic</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
