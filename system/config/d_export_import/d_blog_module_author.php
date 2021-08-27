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
    'name' => 'Blog Module Authors',
    'table' => array(
        'name' => 'a',
        'full_name' => 'bm_author',
        'key' => 'author_id'
        ),

    'tables' => array(
        array(
            'name' => 'ad',
            'full_name' => 'bm_author_description',
            'key' => 'author_id',
            'join' => 'INNER',
            'multi_language' => 1
            )
        ),

    'columns' => array(
        array(
            'column' => 'author_id',
            'table' => 'a',
            'name' => 'Author ID',
            'filter' => 1
            ),
        array(
            'column' => 'user_id',
            'table' => 'a',
            'name' => 'User ID',
            'filter' => 1
            ),
        array(
            'column' => 'author_group_id',
            'table' => 'a',
            'name' => 'User Group ID'
            ),
        array(
            'column' => 'name',
            'table' => 'ad',
            'name' => 'Name',
            'filter' => 1
            ),
        array(
            'column' => 'description',
            'table' => 'ad',
            'name' => 'Description',
            'filter' => 1
            ),
        array(
            'column' => 'short_description',
            'table' => 'ad',
            'name' => 'Short Description'
            )
        ),
    );

$_['sheets'] = array(
    );