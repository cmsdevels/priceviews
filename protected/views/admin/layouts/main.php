<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/furia.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/custom.css" />



    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <script src="<?php echo Yii::app()->baseUrl?>/js/adminHelper.js"></script>
</head>

<body>
<div class="wrapper">
<div class="container" id="page">

    <div id="header">
        <div class="caption" id="logo"><h1><?php echo CHtml::link(Yii::app()->name, array('/site/index'), array('target'=>'_blank')); ?></h1></div>
        <?php  ?>
    </div><!-- header -->
    <div id="mainmenu">
        <?php $this->widget('zii.widgets.CMenu',array(
        'items'=>array(
            array('label'=>Yii::t('core/admin', 'Structure'), 'url'=>array('/admin/structure/index'), 'visible'=>Yii::app()->user->checkAccess('admin.structure.index')),
            array('label'=>Yii::t('core/admin', 'Menu'), 'url'=>array('/admin/menu/index'), 'visible'=>Yii::app()->user->checkAccess('admin.menu.index')),
            array('label'=>Yii::t('core/admin', 'Pages'), 'url'=>array('/admin/page/index'), 'visible'=>Yii::app()->user->checkAccess('admin.page.index')),
            array('label'=>Yii::t('core/admin', 'Modules'), 'url'=>array('/admin/module/index'), 'visible'=>Yii::app()->user->checkAccess('admin.module.index')),
            array('label'=>Yii::t('core/admin', 'Widgets'), 'url'=>array('/admin/widget/index'), 'visible'=>Yii::app()->user->checkAccess('admin.widget.index')),
            array('label'=>Yii::t('core/admin', 'Users'), 'url'=>array('/admin/user/index'), 'visible'=>Yii::app()->user->checkAccess('admin.user.index')),
            array('label'=>Yii::t('core/admin', 'System languages'), 'url'=>array('/admin/language/index'), 'visible'=>Yii::app()->user->checkAccess('admin.language.index')),
            array('label'=>Yii::t('core/admin', 'Settings'), 'url'=>array('/admin/settings/index'), 'visible'=>Yii::app()->user->checkAccess('admin.settings.index')),
            array('label'=>Yii::t('core/user', 'Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/admin/default/logout'), 'visible'=>!Yii::app()->user->isGuest)
        ),
    )); ?>
    </div>
    <table id="main" cellpadding="0" cellspacing="0">
        <tr>
            <?php if (!empty($this->menu)||!empty($this->subMenu)) { ?>
            <td id="sidebar">
                <?php
                $this->beginWidget('zii.widgets.CPortlet', array(
                    'title'=>$this->menuTitle,
                ));
                $this->widget('zii.widgets.CMenu', array(
                    'items'=>$this->menu,
                    'htmlOptions'=>array('class'=>'operations'),
                ));
                $this->endWidget();
                ?>
                <?php
                $this->beginWidget('zii.widgets.CPortlet', array(
                    'title'=>$this->subMenuTitle,
                ));
                $this->widget('zii.widgets.CMenu', array(
                    'items'=>$this->subMenu,
                    'htmlOptions'=>array('class'=>'operations'),
                ));
                $this->endWidget();
                ?>
            </td>
            <?php } ?>
            <td id="tab-content">
                <?php if ($this->title) { ?>
                    <h1><?php echo $this->title; ?></h1>
                <?php } ?>
                <div class="white-block">
                    <?php echo $content; ?>
                </div>
            </td>
        </tr>
    </table>
    <div id="footer">
        Copyright &copy; <?php echo date('Y'); ?> by <a href="http://furiacms.com/" target="_blank">FuriaCMS</a>.<br/>
        <a href="http://web-logic.biz/" target="_blank">Web-Logic</a><br/>
        All Rights Reserved.<br/>
        <?php echo Yii::powered(); ?>
    </div><!-- footer -->
    </div>
</div><!-- page -->
</body>
</html>

<style>
    #footer {
        padding: 10px;
        margin: 10px 20px;
        font-size: 0.8em;
        text-align: center;
    }

    table {
        width: 98%;
        margin: 0 auto;
    }
    #page {
        max-width: 800px;
    }
    html body .caption h1 {
        text-align: center;
    }
</style>