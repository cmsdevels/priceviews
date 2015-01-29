<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="<?php echo Yii::app()->language?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/other.css" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
</head>
<body>
    <div class="container" id="page">
        <div class="logo_login_page">
            <?php echo CHtml::image(Yii::app()->baseUrl.'/images/logo_login_page.png','')?>
        </div>
        <div class="content login_page">
            <?php echo $content; ?>
        </div>
    </div><!-- page -->
    <div id="footer">
        Copyright &copy; <?php echo date('Y'); ?> by <a href="http://furiacms.com/" target="_blank">FuriaCMS</a>.<br/>
        <a href="http://web-logic.biz/" target="_blank">Web-Logic</a><br/>
        All Rights Reserved.<br/>
        <?php echo Yii::powered(); ?>
    </div><!-- footer -->
</body>
</html>