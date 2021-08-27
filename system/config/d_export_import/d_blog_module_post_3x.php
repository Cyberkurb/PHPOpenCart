<?php
$_['opencart_version'] = array(
    "3.0.0.0",
    "3.0.1.1",
    "3.0.1.2",
    "3.0.2.0");
$_['main_sheet'] = array(
    'name' => 'Blog Module Posts',
    'table' => array(
        'name' => 'p',
        'full_name' => 'bm_post',
        'key' => 'post_id'
        ),

    'tables' => array(
        array(
            'name' => 'pd',
            'full_name' => 'bm_post_description',
            'key' => 'post_id',
            'join' => 'INNER',
            'multi_language' => 1
            ),
        array(
            'name' => 'p2s',
            'full_name' => 'bm_post_to_store',
            'key' => 'post_id',
            'join' => 'LEFT'
            ),
        array(
            'name' => 'su',
            'full_name' => 'seo_url',
            'key' => 'query',
            'related_key' => 'query',
            'prefix' => 'bm_post_id=',
            'clear' => 1,
            'not_empty' => 1,
            'join' => 'LEFT'
            ),
        array(
            'name' => 'p2c',
            'full_name' => 'bm_post_to_category',
            'join' => 'LEFT',
            'key' => 'post_id',
            'concat' => 1
            ),
        array(
            'name' => 'pr',
            'full_name' => 'bm_post_related',
            'join' => 'LEFT',
            'key' => 'post_id',
            'concat' => 1
            ),
        array(
            'name' => 'p2p',
            'full_name' => 'bm_post_to_product',
            'join' => 'LEFT',
            'key' => 'post_id',
            'concat' => 1
            )
        ),

    'columns' => array(
        array(
            'column' => 'post_id',
            'table' => 'p',
            'name' => 'Post ID',
            'filter' => 1
            ),
        array(
            'column' => 'post_description_id',
            'table' => 'pd',
            'name' => 'Post Description ID',
            'filter' => 1
            ),
        array(
            'column' => 'user_id',
            'table' => 'p',
            'name' => 'User ID'
            ),
        array(
            'column' => 'title',
            'table' => 'pd',
            'name' => 'Title',
            'filter' => 1
            ),
        array(
            'column' => 'description',
            'table' => 'pd',
            'name' => 'Description',
            'filter' => 1
            ),
        array(
            'column' => 'short_description',
            'table' => 'pd',
            'name' => 'Short Description'
            ),
        array(
            'column' => 'meta_title',
            'table' => 'pd',
            'name' => 'Meta Title',
            'filter' => 1
            ),
        array(
            'column' => 'meta_description',
            'table' => 'pd',
            'name' => 'Meta Description',
            'filter' => 1
            ),
        array(
            'column' => 'meta_keyword',
            'table' => 'pd',
            'name' => 'Meta keyword',
            'filter' => 1
            ),
        array(
            'column' => 'image',
            'table' => 'p',
            'name' => 'Image'
            ),
        array(
            'column' => 'image_title',
            'table' => 'p',
            'name' => 'Image Title'
            ),
        array(
            'column' => 'image_alt',
            'table' => 'p',
            'name' => 'Image Alt'
            ),
        array(
            'column' => 'tag',
            'table' => 'p',
            'name' => 'Tags'
            ),
        array(
            'column' => 'review_display',
            'table' => 'p',
            'name' => 'Review Display'
            ),
        array(
            'column' => 'images_review',
            'table' => 'p',
            'name' => 'Images Review'
            ),
        array(
            'column' => 'viewed',
            'table' => 'p',
            'name' => 'Viewed'
            ),
        array(
            'column' => 'keyword',
            'table' => 'su',
            'name' => 'SEO Keyword'
            ),
        array(
            'column' => 'status',
            'table' => 'p',
            'name' => 'Status',
            'filter' => 1
            ),
        array(
            'column' => 'store_id',
            'table' => 'p2s',
            'name' => 'Stores'
            ),
        array(
            'column' => 'category_id',
            'table' => 'p2c',
            'concat' => 1,
            'name' => 'Categories'
            ),
        array(
            'column' => 'post_related_id',
            'table' => 'pr',
            'concat' => 1,
            'name' => 'Related Posts'
            ),
        array(
            'column' => 'product_id',
            'table' => 'p2p',
            'concat' => 1,
            'name' => 'Related Products'
            ),
        array(
            'column' => 'date_added',
            'table' => 'p',
            'name' => 'Date added'
            ),
        array(
            'column' => 'date_published',
            'table' => 'p',
            'name' => 'Date Published'
            ),
        array(
            'column' => 'date_modified',
            'table' => 'p',
            'name' => 'Date Modified'
            ),
        array(
            'column' => 'custom',
            'table' => 'p',
            'name' => 'Custom'
            ),
        array(
            'column' => 'setting',
            'table' => 'p',
            'name' => 'Setting'
            )
        ),
    );

$_['sheets'] = array(
    );