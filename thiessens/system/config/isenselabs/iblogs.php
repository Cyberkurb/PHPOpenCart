<?php
$_['iblogs'] = array(
    'title'         => 'iBlog',
    'name'          => $name = 'iblogs',
    'version'       => '5.0',

    // Internal
    'code'          => $name,
    'path'          => 'extension/module/' . $name,
    'model'         => 'model_extension_module_' . $name,
    'ext_link'      => 'marketplace/extension',
    'ext_type'      => '&type=module',

    'url_token'     => 'user_token=%s',
    'store_id'      => 0,

    // Default setting
    'setting'   => array(
        // Tab Setting
        'status'            => 0,
        'title'             => array(0 => 'iBlog'),
        'main_nav'          => 1,
        'main_nav_order'    => 7,

        // Tab Setting sub-tab
        'blog_listing'      => array(
            'layout'                => 'leading_list',
            'info_format'           => array(0 => 'By {author} on {date}'),
            'limit'                 => 10,
            'image_width'           => 300,
            'image_height'          => 200,
            'meta_title'            => array(0 => ''),
            'meta_description'      => array(0 => ''),
            'meta_keyword'          => array(0 => ''),
            'url_alias'             => array(0 => 'iblogs'),
            'custom_css'            => ''
        ),
        'post_view'         => array(
            'info_format'           => array(0 => 'Written by {author} on {date}. Posted in {category}'),
            'main_image'            => 1,
            'image_width'           => 800,
            'image_height'          => 350,
            'comment'               => '',
            'addthis'               => 0,
            'custom_css'            => '',
            'comment_disqus'        => '', // Disqus shortname
            'comment_facebook'      => '', // FB App ID
        ),

        // Page Form
        'post'              => array(
            'post_id'               => 0,
            'title'                 => array(0 => ''),
            'excerpt'               => array(0 => ''),
            'content'               => array(0 => ''),
            'meta_title'            => array(0 => ''),
            'meta_description'      => array(0 => ''),
            'meta_keyword'          => array(0 => ''),
            'url_alias'             => array(0 => ''),
            'author_id'             => 0,
            'is_featured'           => 0,
            'category_id'           => 0,
            'categories'            => array(),
            'image'                 => '',
            'sort_order'            => 0,
            'meta'                  => array(
                'related_post'          => 'tags',
                'related_post_items'    => array(),
                'related_product'       => 1,
                'related_product_items' => array(),
            ),
            'status'                => 1,
            'publish'               => date('Y-m-d'),
            'unpublish'             => '',
        ),
        'category'          => array(
            'category_id'           => 0,
            'title'                 => array(0 => ''),
            'content'               => array(0 => ''),
            'meta_title'            => array(0 => ''),
            'meta_description'      => array(0 => ''),
            'meta_keyword'          => array(0 => ''),
            'url_alias'             => '',
            'parent_id'             => 0,
            'image'                 => '',
            'sort_order'            => 0,
            'meta'                  => array(),
            'status'                => 1,
            'path_html'             => ''
        ),
        'widget'            => array(
            'widget_id'             => 0,
            'name'                  => '',
            'title'                 => array(0 => ''),
            'type'                  => 'post_tabs',
            'status'                => 1,
            'custom_css'            => '',

            'search_placeholder'    => array(0 => 'Search post'),
            'post_limit'            => 5,
        ),
    ),
);
