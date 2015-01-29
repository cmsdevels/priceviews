<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 27.05.14
 * Time: 10:57
 */
?>
<nav>
    <a class="logotype" href="<?php echo $this->controller->createUrl('/admin') ?>"><span class="icons-logo"></span><span class="va-helper"></span></a>
    <ul class="pull-left">
        <li>
            <a target="_blank" href="<?php echo $this->controller->createUrl('/site/index'); ?>" title="<?php echo Yii::t('core/admin', 'Go to website'); ?>">
                <span class="icons-site"></span>
                <i class="va-helper"></i>
            </a>
        </li>
        <li><a href="<?php echo $this->controller->createUrl('/admin/structure/index'); ?>"><span class="icons-structure"></span><span class="text"><?php echo Yii::t('core/admin', 'Structure')?></span><i class="va-helper"></i></a></li>
        <li><a href="<?php echo $this->controller->createUrl('/admin/menu/index'); ?>"><span class="icons-menu"></span><span class="text"><?php echo Yii::t('core/admin', 'Menu')?></span><i class="va-helper"></i></a></li>
        <li><a href="<?php echo $this->controller->createUrl('/admin/page/index'); ?>"><span class="icons-directory"></span><span class="text"><?php echo Yii::t('core/admin', 'Pages')?></span><i class="va-helper"></i></a></li>
        <li class="left-ul">
            <a href="<?php echo $this->controller->createUrl('/admin/module/index'); ?>">
                <span class="icons-modules"></span>
                <span class="text"><?php echo Yii::t('core/admin', 'Modules')?></span>
                <?php if (sizeof($modules)>0): ?>
                    <span class="arrow"></span>
                <?php endif; ?>
                <i class="va-helper"></i>
            </a>
            <?php if (sizeof($modules)>0): ?>
                <ul>
                    <?php foreach ($modules as $module) { ?>
                        <li>
                            <a href="<?php echo $this->controller->createUrl('/'.strtolower($module->name).$module->admin_controller); ?>">
                                <span class="icons module-menu-icon">
                                    <?php echo CHtml::image($module->getImageUrl())?>
                                </span>
                                <span class="text"><?php echo $module->title; ?></span>
                                <span class="va-helper"></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php endif; ?>
        </li>
        <li><a href="<?php echo $this->controller->createUrl('/admin/widget/index'); ?>"><span class="icons-vidjet"></span><span class="text"><?php echo Yii::t('core/admin', 'Widgets')?></span><i class="va-helper"></i></a></li>
    </ul>
    <ul class="pull-right clean-border">
        <li>
            <a href="<?php echo $this->controller->createUrl('/admin/user/index'); ?>">
                <span class="icons-peaple"></span>
                <i class="va-helper"></i>
            </a>
        </li>
        <li class="right-ul">
            <a href="" class="toggle-settings">
                <span class="icons-settings"></span>
                <span class="arrow arrow-down"></span>
                <i class="va-helper"></i>
            </a>
            <ul>
                <li class="first"><?php echo Yii::t('core/admin', 'Settings')?></li>
                <li><a href="<?php echo $this->controller->createUrl('/admin/language/index'); ?>"><span class="icons-lang"></span><span class="text"><?php echo Yii::t('core/admin', 'Systems language')?></span><span class="va-helper"></span></a></li>
                <li><a href="<?php echo $this->controller->createUrl('/admin/settings/index'); ?>"><span class="icons-settings"></span><span class="text"><?php echo Yii::t('core/admin', 'Options')?></span><span class="va-helper"></span></a></li>
            </ul>
        </li>
        <li><a href="<?php echo $this->controller->createUrl('/admin/default/logout'); ?>"><span class="icons-exit"></span><i class="va-helper"></i></a></li>
    </ul>
</nav>