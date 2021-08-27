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
    'name' => 'Blog Module Reviews',
    'table' => array(
        'name' => 'r',
        'full_name' => 'bm_review',
        'key' => 'review_id'
        ),

    'tables' => array(
        array(
            'name' => 'r2i',
            'full_name' => 'bm_review_to_image',
            'key' => 'review_id',
            'join' => 'LEFT',
            )
        ),

    'columns' => array(
        array(
            'column' => 'review_id',
            'table' => 'r',
            'name' => 'Review ID',
            'filter' => 1
            ),
        array(
            'column' => 'post_id',
            'table' => 'r',
            'name' => 'Post ID',
            'filter' => 1
            ),
        array(
            'column' => 'reply_to_review_id',
            'table' => 'r',
            'name' => 'Reply to review ID'
            ),
        array(
            'column' => 'language_id',
            'table' => 'r',
            'name' => 'Language ID',
            'filter' => 1
            ),
        array(
            'column' => 'customer_id',
            'table' => 'r',
            'name' => 'Customer ID',
            'filter' => 1
            ),
        array(
            'column' => 'guest_email',
            'table' => 'r',
            'name' => 'Guest email'
            ),
        array(
            'column' => 'image',
            'table' => 'r',
            'name' => 'Image'
            ),
        array(
            'column' => 'author',
            'table' => 'r',
            'name' => 'Author'
            ),
        array(
            'column' => 'description',
            'table' => 'r',
            'name' => 'Description'
            ),
        array(
            'column' => 'rating',
            'table' => 'r',
            'name' => 'Rating'
            ),
        array(
            'column' => 'status',
            'table' => 'r',
            'name' => 'Status'
            ),
        array(
            'column' => 'date_added',
            'table' => 'r',
            'name' => 'Date Added'
            ),
        array(
            'column' => 'date_modified',
            'table' => 'r',
            'name' => 'Date Modified'
            ),
        array(
            'column' => 'image',
            'table' => 'r2i',
            'name' => 'Review to image'
            )
        ),
    );

$_['sheets'] = array(
    );