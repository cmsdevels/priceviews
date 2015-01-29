<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 26.05.14
 * Time: 12:34
 */

?>
<ul class="sidebar">
    <?php if ($menuTitle && !empty($menu)) { ?>
    <li class="active">
        <a href=""><span class="icons-peaple"></span><span class="text"><?php echo $menuTitle; ?></span><i class="va-helper"></i></a>
        <?php
            $this->widget('zii.widgets.CMenu', array(
                'items'=>$menu,
            ));
        ?>
    </li>
    <?php } ?>
    <?php if ($subMenuTitle && !empty($subMenu)) { ?>
        <li>
            <a href=""><span class="icons-vidjet"></span><span class="text"><?php echo $subMenuTitle; ?></span><i class="va-helper"></i></a>
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'items'=>$subMenu,
            ));
            ?>
        </li>
    <?php } ?>
</ul>