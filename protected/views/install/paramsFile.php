<?php echo "<?php";
echo "
return array(
    'adminEmail' => '".$model->admin_email."',
    'forward' => 'index',
    'updateServer' => 'http://api.furiacms.com/',
    'defaultTitle' => 'FuriaCMS',
    'defaultSeparator' => ' | '
);";
echo "?>";
?>