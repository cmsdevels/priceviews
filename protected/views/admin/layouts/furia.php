<?php
/**
 * @var $this CmsAdminController
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/flat-ui/css/flat-ui.min.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/css/admin/furia.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/css/admin/custom.css" type="text/css"/>

    <!--[if gte IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php Yii::app()->clientScript->registerCoreScript('jquery');?>

    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/admin/furia.js');?>
</head>
<body>
<div class="wrapper">
<header>
    <?php $this->widget('application.widgets.admin.headerMenuWidget.HeaderMenuWidget'); ?>
</header>
<div class="container">
    <?php $this->widget('application.widgets.admin.adminSidebarWidget.AdminSidebarWidget', array(
        'menuTitle' => $this->menuTitle,
        'subMenuTitle' => $this->subMenuTitle,
        'menu' => $this->menu,
        'subMenu' => $this->subMenu
    ))?>
    <div class="content">
        <div class="page-body">
            <?php if (!empty($this->breadcrumbs)) { ?>
                <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                    'tagName'=>'ul',
                    'htmlOptions'=>array('class'=>'breadcrumb'),
                    'activeLinkTemplate'=>'<li><a href="{url}">{label}</a></li>',
                    'inactiveLinkTemplate'=>'<li class="active"><a href="#">{label}</a></li>',
                    'separator'=>'',
                    'homeLink'=> CHtml::tag('li',array(),CHtml::link(Yii::t('core/admin', 'Main'), array('/admin/structure/index'))),
                    'links'=>$this->breadcrumbs
                ));?>
            <?php }?>

            <?php echo $content; ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".border").click(function(){
            if($(this.parentNode).hasClass('open')==false)
                $('.open').removeClass('open');
            $(this.parentNode).toggleClass('open');
        })
    })
</script>
<div id="footer">
    Copyright &copy; <?php echo date('Y'); ?> by <a href="http://furiacms.com/" target="_blank">FuriaCMS</a>.<br/>
    <a href="http://web-logic.biz/" target="_blank">Web-Logic</a><br/>
    All Rights Reserved.<br/>
    <?php echo Yii::powered(); ?>
</div><!-- footer -->
</div>
</body>
</html>
