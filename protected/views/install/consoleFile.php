<?php echo "<?php";
echo "
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'".$model->name." Console Application',
	'sourceLanguage' => 'en',
    'language' => '".$model->language."',
	'components'=>array(
        'db'=>array(
            'connectionString' => 'mysql:host=".$model->db_host.";dbname=".$model->db_dbname."',
            'emulatePrepare' => true,
            'username' => '".$model->db_username."',
            'password' => '".$model->db_password."',
            'charset' => 'utf8',
            'tablePrefix'=>'".$model->db_tablePrefix."',
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
        ),
	),
);" ?>