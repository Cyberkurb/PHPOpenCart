<?php 
$_['d_seo_module_blog_setting'] = array(
	'sheet' => array(
		'blog_category' => array(
			'code' => 'blog_category',
			'icon' => 'fa-book',
			'name' => 'text_blog_category',
			'unique_url' => true,
			'exception_data' => 'sort, order, page, limit, codename, secret, bfilter, ajaxfilter, edit, gclid, utm_source, utm_medium, utm_campaign, format',
			'short_url' => false,
			'custom_title_1_class' => '#content h1',
			'custom_title_2_class' => '#content h2',
			'custom_image_class' => '#content .bm-category-info .image img'
		),
		'blog_post' => array(
			'code' => 'blog_post',
			'icon' => 'fa-file-text-o',
			'name' => 'text_blog_post',
			'unique_url' => true,
			'exception_data' => 'sort, order, page, limit, review_id, codename, secret, bfilter, ajaxfilter, edit, gclid, utm_source, utm_medium, utm_campaign, format',
			'short_url' => true,
			'custom_title_1_class' => '#content h1',
			'custom_title_2_class' => '#content h2',
			'custom_image_class' => '#content .bm-post-info .image a, #content .bm-post-info .image img'
		),
		'blog_author' => array(
			'code' => 'blog_author',
			'icon' => 'fa-user',
			'name' => 'text_blog_author',
			'unique_url' => true,
			'exception_data' => 'sort, order, page, limit, codename, secret, bfilter, ajaxfilter, edit, gclid, utm_source, utm_medium, utm_campaign, format',
			'custom_title_1_class' => '#content h1',
			'custom_title_2_class' => '#content h2',
			'custom_image_class' => '#content .bm-author-info .image img'
		),
		'blog_search' => array(
			'code' => 'blog_search',
			'icon' => 'fa-search',
			'name' => 'text_blog_search',
			'unique_url' => true,
			'exception_data' => 'tag, date_published, page, limit, codename, secret, bfilter, ajaxfilter, edit, gclid, utm_source, utm_medium, utm_campaign, format'
		)
	),
	'cache_expire' => '2592000',
	'custom_page_exception_routes' => array(
		'extension/d_blog_module/search'
	)
);
$_['d_seo_module_blog_field_setting'] = array(
	'sheet' => array(
		'blog_category' => array(
			'code' => 'blog_category',
			'icon' => 'fa-book',
			'name' => 'text_blog_category',
			'sort_order' => '10',
			'field' => array(
				'title' => array(
					'code' => 'title',
					'name' => 'text_category_title',
					'description' => '',
					'type' => 'text',
					'sort_order' => '1',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => true
				),
				'short_description' => array(
					'code' => 'short_description',
					'name' => 'text_short_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '2',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'description' => array(
					'code' => 'description',
					'name' => 'text_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '3',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'description' => '',
					'type' => 'text',
					'sort_order' => '4',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => true
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '5',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '6',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'description' => 'help_category_custom_title',
					'type' => 'text',
					'sort_order' => '10',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'description' => 'help_category_custom_title',
					'type' => 'text',
					'sort_order' => '11',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'description' => 'help_category_custom_image_title',
					'type' => 'text',
					'sort_order' => '12',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'description' => 'help_category_custom_image_alt',
					'type' => 'text',
					'sort_order' => '13',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_robots' => array(
					'code' => 'meta_robots',
					'name' => 'text_meta_robots',
					'description' => 'help_blog_category_meta_robots',
					'type' => 'select',
					'option' => array(
						'0' => array(
							'code' => 'index,follow', 
							'name' => 'index,follow'
						),
						'1' => array(
							'code' => 'noindex,follow', 
							'name' => 'noindex,follow'
						),
						'2' => array(
							'code' => 'index,nofollow', 
							'name' => 'index,nofollow'
						),
						'3' => array(
							'code' => 'noindex,nofollow', 
							'name' => 'noindex,nofollow'
						)
					),
					'sort_order' => '14',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'target_keyword' => array(
					'code' => 'target_keyword',
					'name' => 'text_target_keyword',
					'description' => 'help_target_keyword',
					'type' => 'textarea',
					'sort_order' => '20',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'description' => 'help_url_keyword',
					'type' => 'text',
					'sort_order' => '30',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'seo_rating' => array(
					'code' => 'seo_rating',
					'name' => 'text_seo_rating',
					'description' => 'help_seo_rating',
					'type' => 'info',
					'sort_order' => '50',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				)
			)
		),
		'blog_post' => array(
			'code' => 'blog_post',
			'icon' => 'fa-file-text-o',
			'name' => 'text_blog_post',
			'sort_order' => '11',
			'field' => array(
				'title' => array(
					'code' => 'title',
					'name' => 'text_post_title',
					'description' => '',
					'type' => 'text',
					'sort_order' => '1',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => true
				),
				'short_description' => array(
					'code' => 'short_description',
					'name' => 'text_short_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '2',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'description' => array(
					'code' => 'description',
					'name' => 'text_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '3',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'description' => '',
					'type' => 'text',
					'sort_order' => '4',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => true
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '5',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '6',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'tag' => array(
					'code' => 'tag',
					'name' => 'text_tag',
					'description' => '',
					'type' => 'text',
					'sort_order' => '7',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'description' => 'help_post_custom_title',
					'type' => 'text',
					'sort_order' => '10',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'description' => 'help_post_custom_title',
					'type' => 'text',
					'sort_order' => '11',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'description' => 'help_post_custom_image_title',
					'type' => 'text',
					'sort_order' => '12',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'description' => 'help_post_custom_image_alt',
					'type' => 'text',
					'sort_order' => '13',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_robots' => array(
					'code' => 'meta_robots',
					'name' => 'text_meta_robots',
					'description' => 'help_post_meta_robots',
					'type' => 'select',
					'option' => array(
						'0' => array(
							'code' => 'index,follow', 
							'name' => 'index,follow'
						),
						'1' => array(
							'code' => 'noindex,follow', 
							'name' => 'noindex,follow'
						),
						'2' => array(
							'code' => 'index,nofollow', 
							'name' => 'index,nofollow'
						),
						'3' => array(
							'code' => 'noindex,nofollow', 
							'name' => 'noindex,nofollow'
						)
					),
					'sort_order' => '14',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'target_keyword' => array(
					'code' => 'target_keyword',
					'name' => 'text_target_keyword',
					'description' => 'help_target_keyword',
					'type' => 'textarea',
					'sort_order' => '20',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'category_id' => array(
					'code' => 'category_id',
					'name' => 'text_category_id',
					'description' => 'help_category_id',
					'type' => 'text',
					'sort_order' => '30',
					'multi_store' => false,
					'multi_language' => false,
					'multi_store_status' => false,
					'required' => false
				),
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'description' => 'help_url_keyword',
					'type' => 'text',
					'sort_order' => '31',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'seo_rating' => array(
					'code' => 'seo_rating',
					'name' => 'text_seo_rating',
					'description' => 'help_seo_rating',
					'type' => 'info',
					'sort_order' => '50',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				)
			)
		),
		'blog_author' => array(
			'code' => 'blog_author',
			'icon' => 'fa-user',
			'name' => 'text_blog_author',
			'sort_order' => '12',
			'field' => array(
				'name' => array(
					'code' => 'name',
					'name' => 'text_author_name',
					'description' => '',
					'type' => 'text',
					'sort_order' => '1',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => true
				),
				'short_description' => array(
					'code' => 'short_description',
					'name' => 'text_short_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '2',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'description' => array(
					'code' => 'description',
					'name' => 'text_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '3',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'description' => '',
					'type' => 'text',
					'sort_order' => '4',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => true
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '5',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'description' => '',
					'type' => 'textarea',
					'sort_order' => '6',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'description' => 'help_author_custom_title',
					'type' => 'text',
					'sort_order' => '10',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'description' => 'help_author_custom_title',
					'type' => 'text',
					'sort_order' => '11',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'description' => 'help_author_custom_image_title',
					'type' => 'text',
					'sort_order' => '17',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'description' => 'help_author_custom_image_alt',
					'type' => 'text',
					'sort_order' => '18',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'meta_robots' => array(
					'code' => 'meta_robots',
					'name' => 'text_meta_robots',
					'description' => 'help_author_meta_robots',
					'type' => 'select',
					'option' => array(
						'0' => array(
							'code' => 'index,follow', 
							'name' => 'index,follow'
						),
						'1' => array(
							'code' => 'noindex,follow', 
							'name' => 'noindex,follow'
						),
						'2' => array(
							'code' => 'index,nofollow', 
							'name' => 'index,nofollow'
						),
						'3' => array(
							'code' => 'noindex,nofollow', 
							'name' => 'noindex,nofollow'
						)
					),
					'sort_order' => '19',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'target_keyword' => array(
					'code' => 'target_keyword',
					'name' => 'text_target_keyword',
					'description' => 'help_target_keyword',
					'type' => 'textarea',
					'sort_order' => '20',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'description' => 'help_url_keyword',
					'type' => 'text',
					'sort_order' => '30',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				),
				'seo_rating' => array(
					'code' => 'seo_rating',
					'name' => 'text_seo_rating',
					'description' => 'help_seo_rating',
					'type' => 'info',
					'sort_order' => '50',
					'multi_store' => true,
					'multi_language' => true,
					'multi_store_status' => false,
					'required' => false
				)
			)
		)
	)
);
$_['d_seo_module_blog_target_setting'] = array(
	'sheet' => array(
		'blog_category' => array(
			'code' => 'blog_category',
			'icon' => 'fa-book',
			'name' => 'text_blog_category',
			'sort_order' => '10'
		),
		'blog_post' => array(
			'code' => 'blog_post',
			'icon' => 'fa-file-text-o',
			'name' => 'text_blog_post',
			'sort_order' => '11',
		),
		'blog_author' => array(
			'code' => 'blog_author',
			'icon' => 'fa-user',
			'name' => 'text_blog_author',
			'sort_order' => '12',
		)
	)
);
$_['d_seo_module_blog_meta_generator_setting'] = array(
	'sheet' => array(
		'blog_category' => array(
			'code' => 'blog_category',
			'icon' => 'fa-book',
			'name' => 'text_blog_category',
			'sort_order' => '10',
			'field' => array(
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'description' => 'help_generate_blog_category_meta_title',
					'sort_order' => '1',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'description' => 'help_generate_blog_category_meta_description',
					'sort_order' => '2',
					'template_default' => '[title] - [description#sentences=1]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'description' => 'help_generate_blog_category_meta_keyword',
					'sort_order' => '3',
					'template_default' => '[title], [parent_title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'description' => 'help_generate_blog_category_custom_title_1',
					'sort_order' => '10',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'description' => 'help_generate_blog_category_custom_title_2',
					'sort_order' => '11',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_name' => array(
					'code' => 'custom_image_name',
					'name' => 'text_custom_image_name',
					'description' => 'help_generate_blog_category_custom_image_name',
					'sort_order' => '12',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '1',
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'description' => 'help_generate_blog_category_custom_image_title',
					'sort_order' => '13',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'description' => 'help_generate_blog_category_custom_image_alt',
					'sort_order' => '14',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[parent_title]' => '[parent_title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[sample_posts]' => '[sample_posts]', 
						'[total_posts]' => '[total_posts]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				)
			),
			'button_popup' => array(
				'[short_description]' => array(
					'code' => '[short_description]',
					'name' => 'text_insert_short_description',
					'field' => array(
						'sentences' => array(
							'code' => 'sentences',
							'name' => 'text_sentence_total',
							'type' => 'text',
							'value' => '1'
						)
					)
				),
				'[description]' => array(
					'code' => '[description]',
					'name' => 'text_insert_description',
					'field' => array(
						'sentences' => array(
							'code' => 'sentences',
							'name' => 'text_sentence_total',
							'type' => 'text',
							'value' => '1'
						)
					)
				),
				'[target_keyword]' => array(
					'code' => '[target_keyword]',
					'name' => 'text_insert_target_keyword',
					'field' => array(
						'number' => array(
							'code' => 'number',
							'name' => 'text_keyword_number',
							'type' => 'text',
							'value' => '1'
						)
					)
				),
				'[sample_posts]' => array(
					'code' => '[sample_posts]',
					'name' => 'text_insert_sample_posts',
					'field' => array(
						'total' => array(
							'code' => 'total',
							'name' => 'text_post_total',
							'type' => 'text',
							'value' => '3'
						),
						'separator' => array(
							'code' => 'separator',
							'name' => 'text_post_separator',
							'type' => 'text',
							'value' => ','
						)
					)
				)
			)
		),
		'blog_post' => array(
			'code' => 'blog_post',
			'icon' => 'fa-file-text-o',
			'name' => 'text_blog_post',
			'sort_order' => '11',
			'field' => array(
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'description' => 'help_generate_blog_post_meta_title',
					'sort_order' => '1',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'description' => 'help_generate_blog_post_meta_description',
					'sort_order' => '2',
					'template_default' => '[title]. [description#sentences=1]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'description' => 'help_generate_blog_post_meta_keyword',
					'sort_order' => '3',
					'template_default' => '[title], [category]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'description' => 'help_generate_blog_post_custom_title_1',
					'sort_order' => '10',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'description' => 'help_generate_blog_post_custom_title_2',
					'sort_order' => '11',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_name' => array(
					'code' => 'custom_image_name',
					'name' => 'text_custom_image_name',
					'description' => 'help_generate_blog_post_custom_image_name',
					'sort_order' => '12',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '1',
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'description' => 'help_generate_blog_post_custom_image_title',
					'sort_order' => '13',
					'template_default' => '[title] [category]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'description' => 'help_generate_blog_post_custom_image_alt',
					'sort_order' => '14',
					'template_default' => '[title] [category]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[category]' => '[category]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				)
			),
			'button_popup' => array(
				'[short_description]' => array(
					'code' => '[short_description]',
					'name' => 'text_insert_short_description',
					'field' => array(
						'sentences' => array(
							'code' => 'sentences',
							'name' => 'text_sentence_total',
							'type' => 'text',
							'value' => '1'
						)
					)
				),
				'[description]' => array(
					'code' => '[description]',
					'name' => 'text_insert_description',
					'field' => array(
						'sentences' => array(
							'code' => 'sentences',
							'name' => 'text_sentence_total',
							'type' => 'text',
							'value' => '1'
						)
					)
				),
				'[target_keyword]' => array(
					'code' => '[target_keyword]',
					'name' => 'text_insert_target_keyword',
					'field' => array(
						'number' => array(
							'code' => 'number',
							'name' => 'text_keyword_number',
							'type' => 'text',
							'value' => '1'
						)
					)
				)
			)
		),
		'blog_author' => array(
			'code' => 'blog_author',
			'icon' => 'fa-user',
			'name' => 'text_blog_author',
			'sort_order' => '12',
			'field' => array(
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'description' => 'help_generate_blog_author_meta_title',
					'sort_order' => '1',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'description' => 'help_generate_blog_author_meta_description',
					'sort_order' => '2',
					'template_default' => '[name]. [description#sentences=1]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'description' => 'help_generate_blog_author_meta_keyword',
					'sort_order' => '3',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'description' => 'help_generate_blog_author_custom_title_1',
					'sort_order' => '10',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'description' => 'help_generate_blog_author_custom_title_2',
					'sort_order' => '11',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_name' => array(
					'code' => 'custom_image_name',
					'name' => 'text_custom_image_name',
					'description' => 'help_generate_blog_author_custom_image_name',
					'sort_order' => '12',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => false,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '1',
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'description' => 'help_generate_blog_author_custom_image_title',
					'sort_order' => '13',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'description' => 'help_generate_blog_author_custom_image_alt',
					'sort_order' => '14',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[short_description]' => '[short_description]', 
						'[description]' => '[description]', 
						'[target_keyword]' => '[target_keyword]', 
						'[store_name]' => '[store_name]', 
						'[store_title]' => '[store_title]'
					),
					'multi_language' => true,
					'translit_symbol_status' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '0',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				)
			),
			'button_popup' => array(
				'[short_description]' => array(
					'code' => '[short_description]',
					'name' => 'text_insert_short_description',
					'field' => array(
						'sentences' => array(
							'code' => 'sentences',
							'name' => 'text_sentence_total',
							'type' => 'text',
							'value' => '1'
						)
					)
				),
				'[description]' => array(
					'code' => '[description]',
					'name' => 'text_insert_description',
					'field' => array(
						'sentences' => array(
							'code' => 'sentences',
							'name' => 'text_sentence_total',
							'type' => 'text',
							'value' => '1'
						)
					)
				),
				'[target_keyword]' => array(
					'code' => '[target_keyword]',
					'name' => 'text_insert_target_keyword',
					'field' => array(
						'number' => array(
							'code' => 'number',
							'name' => 'text_keyword_number',
							'type' => 'text',
							'value' => '1'
						)
					)
				)
			)
		)
	)
);
$_['d_seo_module_blog_url_generator_setting'] = array(
	'sheet' => array(
		'blog_category' => array(
			'code' => 'blog_category',
			'icon' => 'fa-book',
			'name' => 'text_blog_category',
			'sort_order' => '10',
			'field' => array(
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'description' => 'help_generate_blog_category_url_keyword',
					'sort_order' => '1',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[target_keyword]' => '[target_keyword]'
					),
					'multi_language' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '1',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				)
			),
			'button_popup' => array(
				'[target_keyword]' => array(
					'code' => '[target_keyword]',
					'name' => 'text_insert_target_keyword',
					'field' => array(
						'number' => array(
							'code' => 'number',
							'name' => 'text_keyword_number',
							'type' => 'text',
							'value' => '1'
						)
					)
				)
			)
		),
		'blog_post' => array(
			'code' => 'blog_post',
			'icon' => 'fa-file-text-o',
			'name' => 'text_blog_post',
			'sort_order' => '11',
			'field' => array(
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'description' => 'help_generate_blog_post_url_keyword',
					'sort_order' => '1',
					'template_default' => '[title]',
					'template_button' => array(
						'[title]' => '[title]', 
						'[target_keyword]' => '[target_keyword]'
					),
					'multi_language' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '1',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				)
			),
			'button_popup' => array(
				'[target_keyword]' => array(
					'code' => '[target_keyword]',
					'name' => 'text_insert_target_keyword',
					'field' => array(
						'number' => array(
							'code' => 'number',
							'name' => 'text_keyword_number',
							'type' => 'text',
							'value' => '1'
						)
					)
				)
			)
		),
		'blog_author' => array(
			'code' => 'blog_author',
			'icon' => 'fa-user',
			'name' => 'text_blog_author',
			'sort_order' => '12',
			'field' => array(
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'description' => 'help_generate_blog_author_url_keyword',
					'sort_order' => '1',
					'template_default' => '[name]',
					'template_button' => array(
						'[name]' => '[name]', 
						'[target_keyword]' => '[target_keyword]'
					),
					'multi_language' => true,
					'translit_language_symbol_status' => false,
					'transform_language_symbol_id' => '1',
					'overwrite' => false,
					'button_generate' => true,
					'button_clear' => true
				)
			),
			'button_popup' => array(
				'[target_keyword]' => array(
					'code' => '[target_keyword]',
					'name' => 'text_insert_target_keyword',
					'field' => array(
						'number' => array(
							'code' => 'number',
							'name' => 'text_keyword_number',
							'type' => 'text',
							'value' => '1'
						)
					)
				)
			)
		)
	)
);
$_['d_seo_module_blog_url_setting'] = array(
	'sheet' => array(
		'blog_category' => array(
			'code' => 'blog_category',
			'icon' => 'fa-book',
			'name' => 'text_blog_category',
			'sort_order' => '10'
		),
		'blog_post' => array(
			'code' => 'blog_post',
			'icon' => 'fa-file-text-o',
			'name' => 'text_blog_post',
			'sort_order' => '11',
		),
		'blog_author' => array(
			'code' => 'blog_author',
			'icon' => 'fa-user',
			'name' => 'text_blog_author',
			'sort_order' => '12',
		)
	)
);		
$_['d_seo_module_blog_manager_setting'] = array(
	'sheet' => array(
		'blog_category' => array(
			'code' => 'blog_category',
			'icon' => 'fa-book',
			'name' => 'text_blog_category',
			'sort_order' => '10',
			'field_index' => 'category_id',
			'field' => array(
				'category_id' => array(
					'code' => 'category_id',
					'name' => 'text_category_id',
					'type' => 'link',
					'sort_order' => '1',
					'multi_store' => false,
					'multi_language' => false,
					'list_status' => true,
					'export_status' => true,
					'required' => true
				),
				'title' => array(
					'code' => 'title',
					'name' => 'text_category_title',
					'type' => 'text',
					'sort_order' => '2',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'short_description' => array(
					'code' => 'short_description',
					'name' => 'text_short_description',
					'type' => 'textarea',
					'sort_order' => '3',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'description' => array(
					'code' => 'description',
					'name' => 'text_description',
					'type' => 'textarea',
					'sort_order' => '4',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'type' => 'text',
					'sort_order' => '5',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'type' => 'textarea',
					'sort_order' => '6',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'type' => 'textarea',
					'sort_order' => '7',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'type' => 'text',
					'sort_order' => '10',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'type' => 'text',
					'sort_order' => '11',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'type' => 'text',
					'sort_order' => '12',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'type' => 'text',
					'sort_order' => '13',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'meta_robots' => array(
					'code' => 'meta_robots',
					'name' => 'text_meta_robots',
					'type' => 'select',
					'option' => array(
						'0' => array(
							'code' => 'index,follow', 
							'name' => 'index,follow'
						),
						'1' => array(
							'code' => 'noindex,follow', 
							'name' => 'noindex,follow'
						),
						'2' => array(
							'code' => 'index,nofollow', 
							'name' => 'index,nofollow'
						),
						'3' => array(
							'code' => 'noindex,nofollow', 
							'name' => 'noindex,nofollow'
						)
					),
					'sort_order' => '14',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'target_keyword' => array(
					'code' => 'target_keyword',
					'name' => 'text_target_keyword',
					'type' => 'textarea',
					'sort_order' => '20',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'type' => 'text',
					'sort_order' => '30',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				)
			)
		),
		'blog_post' => array(
			'code' => 'blog_post',
			'icon' => 'fa-file-o',
			'name' => 'text_blog_post',
			'sort_order' => '11',
			'field_index' => 'post_id',
			'field' => array(
				'post_id' => array(
					'code' => 'post_id',
					'name' => 'text_post_id',
					'type' => 'link',
					'sort_order' => '1',
					'multi_store' => false,
					'multi_language' => false,
					'list_status' => true,
					'export_status' => true,
					'required' => true
				),
				'title' => array(
					'code' => 'title',
					'name' => 'text_post_title',
					'type' => 'text',
					'sort_order' => '2',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'short_description' => array(
					'code' => 'short_description',
					'name' => 'text_short_description',
					'type' => 'textarea',
					'sort_order' => '3',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'description' => array(
					'code' => 'description',
					'name' => 'text_description',
					'type' => 'textarea',
					'sort_order' => '4',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'type' => 'text',
					'sort_order' => '5',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'type' => 'textarea',
					'sort_order' => '6',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'type' => 'textarea',
					'sort_order' => '7',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'tag' => array(
					'code' => 'tag',
					'name' => 'text_tag',
					'type' => 'text',
					'sort_order' => '8',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'type' => 'text',
					'sort_order' => '10',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'type' => 'text',
					'sort_order' => '11',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'type' => 'text',
					'sort_order' => '12',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'type' => 'text',
					'sort_order' => '13',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'meta_robots' => array(
					'code' => 'meta_robots',
					'name' => 'text_meta_robots',
					'type' => 'select',
					'option' => array(
						'0' => array(
							'code' => 'index,follow', 
							'name' => 'index,follow'
						),
						'1' => array(
							'code' => 'noindex,follow', 
							'name' => 'noindex,follow'
						),
						'2' => array(
							'code' => 'index,nofollow', 
							'name' => 'index,nofollow'
						),
						'3' => array(
							'code' => 'noindex,nofollow', 
							'name' => 'noindex,nofollow'
						)
					),
					'sort_order' => '14',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'target_keyword' => array(
					'code' => 'target_keyword',
					'name' => 'text_target_keyword',
					'type' => 'textarea',
					'sort_order' => '20',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'category_id' => array(
					'code' => 'category_id',
					'name' => 'text_category_id',
					'type' => 'text',
					'sort_order' => '30',
					'multi_store' => true,
					'multi_language' => false,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'type' => 'text',
					'sort_order' => '31',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				)
			)
		),
		'blog_author' => array(
			'code' => 'blog_author',
			'icon' => 'fa-user',
			'name' => 'text_blog_author',
			'sort_order' => '12',
			'field_index' => 'author_id',
			'field' => array(
				'author_id' => array(
					'code' => 'author_id',
					'name' => 'text_author_id',
					'type' => 'link',
					'sort_order' => '1',
					'multi_store' => false,
					'multi_language' => false,
					'list_status' => true,
					'export_status' => true,
					'required' => true
				),
				'name' => array(
					'code' => 'name',
					'name' => 'text_author_name',
					'type' => 'text',
					'sort_order' => '2',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'short_description' => array(
					'code' => 'short_description',
					'name' => 'text_short_description',
					'type' => 'textarea',
					'sort_order' => '3',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'description' => array(
					'code' => 'description',
					'name' => 'text_description',
					'type' => 'textarea',
					'sort_order' => '4',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'type' => 'text',
					'sort_order' => '5',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'type' => 'textarea',
					'sort_order' => '6',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'type' => 'textarea',
					'sort_order' => '7',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_title_1' => array(
					'code' => 'custom_title_1',
					'name' => 'text_custom_title_1',
					'type' => 'text',
					'sort_order' => '10',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_title_2' => array(
					'code' => 'custom_title_2',
					'name' => 'text_custom_title_2',
					'type' => 'text',
					'sort_order' => '11',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'custom_image_title' => array(
					'code' => 'custom_image_title',
					'name' => 'text_custom_image_title',
					'type' => 'text',
					'sort_order' => '12',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'custom_image_alt' => array(
					'code' => 'custom_image_alt',
					'name' => 'text_custom_image_alt',
					'type' => 'text',
					'sort_order' => '13',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'meta_robots' => array(
					'code' => 'meta_robots',
					'name' => 'text_meta_robots',
					'type' => 'select',
					'option' => array(
						'0' => array(
							'code' => 'index,follow', 
							'name' => 'index,follow'
						),
						'1' => array(
							'code' => 'noindex,follow', 
							'name' => 'noindex,follow'
						),
						'2' => array(
							'code' => 'index,nofollow', 
							'name' => 'index,nofollow'
						),
						'3' => array(
							'code' => 'noindex,nofollow', 
							'name' => 'noindex,nofollow'
						)
					),
					'sort_order' => '14',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => false,
					'export_status' => true,
					'required' => false
				),
				'target_keyword' => array(
					'code' => 'target_keyword',
					'name' => 'text_target_keyword',
					'type' => 'textarea',
					'sort_order' => '20',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'type' => 'text',
					'sort_order' => '30',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				)
			)
		)
	)
);
$_['d_seo_module_blog_feature_setting'] = array(
	'meta_robots_field_per_page' => array(
		'name' => 'text_meta_robots_field_per_page',
		'image' => 'catalog/d_seo_module_blog/feature/meta_robots_field_per_page.svg',
		'href' => 'https://opencartseomodule.com/meta-robots',
	),
	'edit_meta_information_for_all_pages' => array(
		'name' => 'text_edit_meta_information_for_all_pages',
		'image' => 'catalog/d_seo_module_blog/feature/edit_meta_information_for_all_pages.svg',
		'href' => 'https://opencartseomodule.com/edit-meta-information-for-all-pages',
	),
	'unique_urls_for_all_pages' => array(
		'name' => 'text_unique_urls_for_all_pages',
		'image' => 'catalog/d_seo_module_blog/feature/unique_urls_for_all_pages.svg',
		'href' => 'https://opencartseomodule.com/unique-urls-for-all-pages',
	),
	'long_or_short_urls_for_blog_category_and_post' => array(
		'name' => 'text_long_or_short_urls_for_blog_category_and_post',
		'image' => 'catalog/d_seo_module_blog/feature/long_or_short_urls_for_blog_category_and_post.svg',
		'href' => 'https://opencartseomodule.com/long-or-short-urls-for-blog-category-and-post',
	),
	'seo_module_blog_api' => array(
		'name' => 'text_seo_module_blog_api',
		'image' => 'catalog/d_seo_module_blog/feature/seo_module_blog_api.svg',
		'href' => 'https://opencartseomodule.com/seo-module-blog-api',
	),
	'multi_language_urls_for_all_pages' => array(
		'name' => 'text_multi_language_urls_for_all_pages',
		'image' => 'catalog/d_seo_module_blog/feature/multi_language_urls_for_all_pages.svg',
		'href' => 'https://opencartseomodule.com/multilanguage-urls-for-all-pages',
	),
	'set_canonicals_for_all_pages' => array(
		'name' => 'text_set_canonicals_for_all_pages',
		'image' => 'catalog/d_seo_module_blog/feature/set_canonicals_for_all_pages.svg',
		'href' => 'https://opencartseomodule.com/canonicals-for-all-pages',
	),
	'pagination_canonicals' => array(
		'name' => 'text_pagination_canonicals',
		'image' => 'catalog/d_seo_module_blog/feature/pagination_canonicals.svg',
		'href' => 'https://opencartseomodule.com/pagination-canonicals',
	),
	'pagination_links_next_and_prev' => array(
		'name' => 'text_pagination_links_next_and_prev',
		'image' => 'catalog/d_seo_module_blog/feature/pagination_links_next_and_prev.svg',
		'href' => 'https://opencartseomodule.com/pagination-links-next-and-prev',
	),
	'alternate_hreflang_tag' => array(
		'name' => 'text_alternate_hreflang_tag',
		'image' => 'catalog/d_seo_module_blog/feature/alternate_hreflang_tag.svg',
		'href' => 'https://opencartseomodule.com/alternate-hreflang-tag',
	)
);
?>