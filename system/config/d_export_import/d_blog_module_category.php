<?php
$_['opencart_version'] = array(
    "2.0.0.0",
    "2.0.1.0",
    "2.0.1.1",
    "2.0.2.0",
    "2.0.3.1",
    "2.1.0.1",
    "2.1.0.2",
    "2.2.0.0",
    "2.3.0.0",
    "2.3.0.1",
    "2.3.0.2");
$_['main_sheet'] = array(
    'name' => 'Blog Module Categories',
    'table' => array(
        'name' => 'c',
        'full_name' => 'bm_category',
        'key' => 'category_id'
        ),

    'tables' => array(
        array(
            'name' => 'cd',
            'full_name' => 'bm_category_description',
            'key' => 'category_id',
            'join' => 'INNER',
            'multi_language' => 1
            ),
        array(
            'name' => 'c2s',
            'full_name' => 'bm_category_to_store',
            'key' => 'category_id',
            'join' => 'LEFT'
            ),
        array(
            'name' => 'ua',
            'full_name' => 'url_alias',
            'key' => 'query',
            'related_key' => 'query',
            'prefix' => 'bm_category_id=',
            'clear' => 1,
            'not_empty' => 1,
            'join' => 'LEFT'
            )
        ),

    'columns' => array(
        array(
            'column' => 'category_id',
            'table' => 'c',
            'name' => 'Category ID',
            'filter' => 1
            ),
        array(
            'column' => 'category_description_id',
            'table' => 'cd',
            'name' => 'Category Description ID',
            'filter' => 1
            ),
        array(
            'column' => 'title',
            'table' => 'cd',
            'name' => 'Title',
            'filter' => 1
            ),
        array(
            'column' => 'description',
            'table' => 'cd',
            'name' => 'Description',
            'filter' => 1
            ),
        array(
            'column' => 'meta_title',
            'table' => 'cd',
            'name' => 'Meta Title',
            'filter' => 1
            ),
        array(
            'column' => 'meta_description',
            'table' => 'cd',
            'name' => 'Meta Description',
            'filter' => 1
            ),
        array(
            'column' => 'meta_keyword',
            'table' => 'cd',
            'name' => 'Meta keyword',
            'filter' => 1
            ),
        array(
            'column' => 'parent_id',
            'table' => 'c',
            'name' => 'Parent',
            'filter' => 1
            ),
        array(
            'column' => 'image',
            'table' => 'c',
            'name' => 'Image'
            ),
        array(
            'column' => 'keyword',
            'table' => 'ua',
            'name' => 'SEO Keyword'
            ),
        array(
            'column' => 'sort_order',
            'table' => 'c',
            'name' => 'Sort Order'
            ),
        array(
            'column' => 'status',
            'table' => 'c',
            'name' => 'Status',
            'filter' => 1
            ),
        array(
            'column' => 'store_id',
            'table' => 'c2s',
            'name' => 'Stores'
            )
        )
    );

$_['sheets'] = array(
    );

$_['events_import_after'] = array('extension/d_export_import_module/d_blog_module_category/repair');