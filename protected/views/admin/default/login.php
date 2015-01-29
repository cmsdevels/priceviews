<?php
/**
  *@var $form CActiveForm
  * */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
    'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
    'enableAjaxValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnType' => false
    ),
)); ?>
    <h1><?php echo Yii::t('core/user','Login'); ?></h1>
	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('autocomplete'=>"off")); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe',array('class'=>'custom-check-box')); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
        <?php
            $this->widget('bootstrap.widgets.TbButton',array(
            'label' => Yii::t('core/user', 'Login'),
            'buttonType' => 'submit',
            'type' => '',
                'htmlOptions'=>array(
                    'class'=>'button_login_form'
                )
            ));
        ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript">
    function centeredForm(){
        var form = $('#login-form');
        var window_height = $(window).innerHeight();
        var window_width = $(window).innerWidth();
        var formHeight = form.height();
        var formWidth = form.width();
        var formBorder = parseInt(form.css('border'));
        var formTop = 100;
        var formLeft = 300;
        if(isNaN(formBorder)){
            formBorder = 2
        }
        if(window_height>formHeight){
            formTop = (window_height/2) - (formHeight/2+formBorder);
        }
        if(window_width>formWidth){
            formLeft = (window_width/2) - (formWidth/2+formBorder);
        }
        if(window_height<520){
            formTop = 95;
        }
        if (window_height<485){
            $('#footer').css({position:'relative'});
        }else{
            $('#footer').css({position:'absolute'});
        }
        form.css({
            top:formTop+'px',
            left:formLeft+'px',
            margin: 0
        });
    }
    $(document).ready(function(){
        centeredForm();
    });
    $(window).resize(function(){
        centeredForm();
    });
    function runForm($_this){
        var formDivRow = $('div.row');
        var speedMove = 100;
        if($_this.parent('div.row').hasClass("error")){
            console.log('error');
            var form = $('#login-form');
            form.animate({
                left: "+=35"
            }, speedMove, function() {

                form.animate({
                    left: "-=70"
                }, speedMove, function() {

                    form.animate({
                        left: "+=70"
                    }, speedMove, function() {
                        form.animate({
                            left: "-=70"
                        }, speedMove, function() {
                            form.animate({
                                left: "+=70"
                            }, speedMove, function() {
                                form.animate({
                                    left: "-=35"
                                }, speedMove, function() {
                                    // Animation complete.
                                });
                            });
                        });
                    });
                });
            });

        }
    }
    $( document ).ajaxComplete(function() {
        runForm($('#login-form input'));
    });
</script>
