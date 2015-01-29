<?php
if (isset( $this->module->id)&&!empty( $this->module->id)){
    $this->breadcrumbs=array(
        $this->module->id,
    );
}
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>