<?php
$_['d_blog_module_layout'] = array(
    'id' => 'masonry',
    'name' => 'Masonry',
    'description' => 'Display posts like Pinterest. Use the grid layout option below to setup the number of columns. Only first value is taken into account. The rest are ignored.',
    'template' => 'd_blog_module/layout_masonry',
    'styles'=> array(
        'd_blog_module/layout/masonry/main.css'
    ),
    'scripts' => array(
        'd_blog_module/layout/masonry/main.js'
    )
);