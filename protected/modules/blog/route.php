<?php
return array(
    'order'=>'1',
    'rules'=>array(
        '/blog/tag/<name>'=>'/blog/default/tag',
        '/blog/search'=>'/blog/default/search',
//        '/blog/<seo_link:\w+>'=>'/blog/default/view',
//        '/blog/<cat_link:\w+>/<seo_link:\w+>'=>'/blog/default/view',
//        '/blog'=>'/blog/default/index',
    )
);