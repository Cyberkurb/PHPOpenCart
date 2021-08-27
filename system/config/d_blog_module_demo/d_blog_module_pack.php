<?php
$_['d_blog_module_pack_demo'] = array(
    'text' => 'Modules',
    'description' => '<h4>Demo Data for Blog Modules</h4><p>Since you have installed the Blog Module Pack, you can use this option to quickly setup your Modules to look like the demo site. You can then edit the modules and layouts to fit your needs. Remeber that this option will remove the current Blog Layouts if there are any.</p>',
    'sql' => 'd_blog_module_pack.sql',
    'permission' => array(
        'access' => array(
            'extension/module/d_blog_module_category',
            'extension/module/d_blog_module_date',
            'extension/module/d_blog_module_latest_posts',
            'extension/module/d_blog_module_popular_posts',
            'extension/module/d_blog_module_relat_post_to_prod',
            'extension/module/d_blog_module_related_post',
            'extension/module/d_blog_module_related_product',
            'extension/module/d_blog_module_search',
            'extension/module/d_blog_module_tags'
        ),
        'modify' => array(
            'extension/module/d_blog_module_category',
            'extension/module/d_blog_module_date',
            'extension/module/d_blog_module_latest_posts',
            'extension/module/d_blog_module_popular_posts',
            'extension/module/d_blog_module_relat_post_to_prod',
            'extension/module/d_blog_module_related_post',
            'extension/module/d_blog_module_related_product',
            'extension/module/d_blog_module_search',
            'extension/module/d_blog_module_tags'
        )
    )
);