<?php
/**
 *   JsTreeWidget  class file.
 *
 * @author Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://iws.kabasakalis.gr/
 * @link http://www.reverbnation.com/spiroskabasakalis
 * @copyright Copyright &copy; 2013 Spiros Kabasakalis
 * @since 1.0
 * @license  http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @version 1.0.0
 */

class JsTreeWidget extends CWidget
{

    /**
     * @var string the model class name with the NestedSetBehavior
     */
    public $modelClassName;

    public $action = 'fetchTree';

    /**
     * @var string the id of the div that will wrap jstree
     */
    public $jstree_container_ID = 'jstree_container';


    /**
     * @var string theme configuration
     * @link  http://www.jstree.com/documentation/themes
     */
    public $themes = array('theme' => 'default', 'dots' => true, 'icons' => true);

    public $types = array();

    /**
     * @var string plugins  configuration
     * @link http://www.jstree.com/demo
     *  If you want to disable tree manipulation (for example if rendering tree on frontend),just exclude contextmenu,crrm and dnd plugins.
     */
    public $plugins = array('themes', 'html_data', 'contextmenu', 'crrm', 'dnd', 'cookies','ui');



    public function init()
    {
        $this->register_Js_Css();
        parent::init();
    }

    private function register_Js_Css()
    {
        $baseUrl = Yii::app()->baseUrl;
        $jsUrl = $this->getAssetsUrl();
        $csrf = Yii::app()->request->csrfToken;
        $open_nodes = $this->getOpenNodes();
        $themes = json_encode($this->themes);
        $plugins = json_encode($this->plugins);
        $types  = json_encode($this->types);

        //assuming that we use the widget in  controller with JsTreeBehavior
        if(isset($this->controller->module)){
            $controllerID = $this->controller->module->id."/".$this->controller->id;
        }else{
            $controllerID = $this->controller->id;
        }

        //pass php variables to javascript
        $jstree_behavior_js = <<<EOD
      (function ($) {
          JsTreeBehavior = {
           controllerID:'$controllerID',
            container_ID:'$this->jstree_container_ID',
            open_nodes:$open_nodes,
            themes:$themes,
            plugins:$plugins,
            action:$plugins,
            types: $types
              },
         Yii_js = {
           baseUrl:'$baseUrl',
           csrf:'$csrf'
           }
      }(jQuery));
EOD;

        //uncomment to register jquery only if you have not already registered it somewhere else in your application
        //Yii::app()->clientScript->registerCoreScript('jquery');

        //uncomment to register bootstrap css if you have not already included  it (optional),or else you will have to style the html by yourself.
        //Yii::app()->clientScript->registerCssFile($baseUrl . '/js_plugins/bootstrap/css/bootstrap.css');
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('cookie');
        Yii::app()->clientScript->registerScript(__CLASS__ . 'jstree_behavior_params', $jstree_behavior_js, CClientScript::POS_END);

        //modal dialog with noty.js
//        Yii::app()->clientScript->registerScriptFile($jsUrl . '/noty/js/noty/jquery.noty.js', CClientScript::POS_END);
//        Yii::app()->clientScript->registerScriptFile($jsUrl . '/noty/js/noty/layouts/center.js', CClientScript::POS_END);
//        Yii::app()->clientScript->registerScriptFile($jsUrl . '/noty/js/noty/themes/default.js', CClientScript::POS_END);
        //js spinner
        Yii::app()->clientScript->registerScriptFile($jsUrl . '/spin.min.js', CClientScript::POS_END);
        //fancybox

//        Yii::app()->clientScript->registerScriptFile($jsUrl . '/json2/json2.js');

        Yii::app()->clientScript->registerCoreScript('yiiactiveform');

        // jquery.form.js plugin http://malsup.com/jquery/form/
//        Yii::app()->clientScript->registerScriptFile($jsUrl . '/ajaxform/jquery.form.js', CClientScript::POS_END);
//        Yii::app()->clientScript->registerScriptFile($jsUrl . '/ajaxform/form.js', CClientScript::POS_END);
        //jstree
        Yii::app()->clientScript->registerScriptFile($jsUrl . '/jstree/jquery.jstree.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile($jsUrl . '/jstree.behavior.js', CClientScript::POS_END);
    }

    /**
     * Specify which nodes to open.Default is all but you can modify.
     * @return  string  $open_nodes  all the open nodes with node ids delimited by comma.
     */
    private function getOpenNodes()
    {
        $categories = CActiveRecord::model($this->modelClassName)->findAll(array('order' => 'lft'));
        $identifiers = array();
        foreach ($categories as $n => $category) {
            $identifiers[] = "'" . 'node_' . $category->id . "'";
        }
        $open_nodes = '[' . implode(',', $identifiers) . ']';
        return $open_nodes = '[]';
    }



    public function run()
    {
        $this->render('treewidget');
    }

    public function getAssetsPath()
    {
        return __DIR__ . '/../js_plugins';
    }

    public function getAssetsUrl()
    {
        return Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
    }


}

