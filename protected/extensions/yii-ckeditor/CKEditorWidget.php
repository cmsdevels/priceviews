<?php
/**
 * Simple Yii CKEditor widget
 * @property string $configJS
 * @property string $assetsPath
 * @property string $assetsUrl
 * @author Yuriy Firs <firs.yura@gmail.com>
 * @version 0.1
 */ 
class CKEditorWidget extends CInputWidget {
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'ckeditor-widget';
    const FINDER_PACKAGE_ID = 'elfinder-widget';

    /**
     * @var array Options
     */
    public $config = array(
        'language' => 'ru',
    );

    public $package = array();
    public $finder_package = array();

    /**
     * Init widget.
     */
    public function init()
    {
        parent::init();

        $this->package = array(
            'baseUrl' => $this->assetsUrl,
            'js' => array(
                'ckeditor.js',
            )
        );
        $this->finder_package = array(
            'baseUrl' => $this->assetsUrl,
            'js' => array(
                'elfinder/js/elfinder.min.js',
                'elfinder/js/i18n/elfinder.ru.js'
            ),
            'css' => array(
                'elfinder/css/elfinder.min.css'
            )
        );

        $this->registerClientScript();
    }

    /**
     * Register CSS and Script.
     */
    protected function registerClientScript()
    {
        Yii::app()->clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)->registerScript(
                $this->id,
                "CKEDITOR.editorConfig = function( config ) {
                ".$this->configJS."
                };
                ",
                CClientScript::POS_HEAD
            );

        Yii::app()->clientScript
            ->addPackage(self::FINDER_PACKAGE_ID, $this->package)
            ->registerPackage(self::FINDER_PACKAGE_ID);
    }

    /**
     * Get the assets path.
     * @return string
     */
    public function getAssetsPath()
    {
        return __DIR__ . '/assets';
    }

    public function run()
    {
        // add class ckeditor
        if (isset($this->htmlOptions['class']) && strpos($this->htmlOptions['class'], 'ckeditor')===false) {
            $this->htmlOptions['class'] .= ' ckeditor';
        } elseif (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = 'ckeditor';
        }
        if (!empty($this->name)) {
            echo CHtml::textArea($this->name, $this->value, $this->htmlOptions);
        } else
            echo CHtml::activeTextArea($this->model ,$this->attribute, $this->htmlOptions);

    }

    /**
     * Publish assets and return url.
     * @return string
     */
    public function getAssetsUrl()
    {
        return Yii::app()->assetManager->publish($this->assetsPath);
    }

    public function getConfigJS()
    {
        $return = '';
        foreach ($this->config as $key=>$value) {
            $return .= "config.".$key." = '".$value."'; ";
        }
        $return .= "config.filebrowserBrowseUrl = '".$this->assetsUrl."/elfinder/elfinder.html'; ";
        return $return;
    }
}
