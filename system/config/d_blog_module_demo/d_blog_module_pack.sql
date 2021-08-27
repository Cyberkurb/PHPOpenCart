DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_related_post';
DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_category';
DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_related_product';
DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_latest_posts';
DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_popular_posts';
DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_tags';
DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_date';
DELETE FROM `oc_extension` WHERE `code` = 'd_blog_module_search';

INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_related_post');
INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_category');
INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_related_product');
INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_latest_posts');
INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_popular_posts');
INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_tags');
INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_date');
INSERT INTO `oc_extension` (`type`, `code`) VALUES ('module', 'd_blog_module_search');

DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_related_post';
DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_category';
DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_related_product';
-- DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_latest_posts';
-- DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_popular_posts';
DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_tags';
DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_date';
DELETE FROM `oc_setting` WHERE `code` = 'd_blog_module_search';

DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_related_post';
DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_category';
DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_related_product';
-- DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_latest_posts';
-- DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_popular_posts';
DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_tags';
DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_date';
DELETE FROM `oc_setting` WHERE `code` = 'module_d_blog_module_search';

INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_category', 'd_blog_module_category_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_latest_posts', 'd_blog_module_latest_posts_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_latest_posts', 'd_blog_module_latest_posts', '{"limit":"3"}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_related_post', 'd_blog_module_related_post_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_popular_posts', 'd_blog_module_popular_posts_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_popular_posts', 'd_blog_module_popular_posts', '{"limit":"3"}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_search', 'd_blog_module_search', '{"limit":"6"}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_search', 'd_blog_module_search_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_date', 'd_blog_module_date_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_date', 'd_blog_module_date_setting', '{"1":{"name":"Calendar"}}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_tags', 'd_blog_module_tags_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_tags', 'd_blog_module_tags_setting', '{"1":{"name":"Tags"}}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_related_product', 'd_blog_module_related_product_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'd_blog_module_related_product', 'd_blog_module_related_product_setting', '{"image_width":"100","image_height":"100","limit":"3"}', '1');

INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_category', 'module_d_blog_module_category_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_latest_posts', 'module_d_blog_module_latest_posts_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_latest_posts', 'module_d_blog_module_latest_posts', '{"limit":"3"}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_related_post', 'module_d_blog_module_related_post_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_popular_posts', 'module_d_blog_module_popular_posts_status', '1', '0');
-- INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_popular_posts', 'module_d_blog_module_popular_posts', '{"limit":"3"}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_search', 'module_d_blog_module_search', '{"limit":"6"}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_search', 'module_d_blog_module_search_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_date', 'module_d_blog_module_date_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_date', 'module_d_blog_module_date_setting', '{"1":{"name":"Calendar"}}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_tags', 'module_d_blog_module_tags_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_tags', 'module_d_blog_module_tags_setting', '{"1":{"name":"Tags"}}', '1');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_related_product', 'module_d_blog_module_related_product_status', '1', '0');
INSERT INTO `oc_setting` ( `store_id`, `code`, `key`, `value`, `serialized`) VALUES ('0', 'module_d_blog_module_related_product', 'module_d_blog_module_related_product_setting', '{"image_width":"100","image_height":"100","limit":"3"}', '1');

DELETE FROM `oc_module` WHERE `code` = 'd_blog_module_latest_posts';
DELETE FROM `oc_module` WHERE `code` = 'd_blog_module_popular_posts';

INSERT INTO `oc_module` ( `module_id`, `name`, `code`, `setting` ) VALUES ( '800', 'Blog Module: Latest Posts by Dreamvention', 'd_blog_module_latest_posts', '{"name":"Blog Module: Latest Posts by Dreamvention","blog_category":["1"],"limit":"5","status":"1"}');
INSERT INTO `oc_module` ( `module_id`, `name`, `code`, `setting` ) VALUES ( '801', 'Blog Module: Popular Posts by Dreamvention', 'd_blog_module_popular_posts', '{"name":"Blog module: Popular Posts by Dreamvention","blog_category":["1"],"limit":"5","status":"1"}');

DELETE FROM `oc_layout_module` WHERE `code` = 'd_blog_module_related_post';
DELETE FROM `oc_layout_module` WHERE `code` = 'd_blog_module_category';
DELETE FROM `oc_layout_module` WHERE `code` = 'd_blog_module_related_product';
DELETE FROM `oc_layout_module` WHERE `code` LIKE 'd_blog_module_latest_posts%';
DELETE FROM `oc_layout_module` WHERE `code` LIKE 'd_blog_module_popular_posts%';
DELETE FROM `oc_layout_module` WHERE `code` = 'd_blog_module_tags';
DELETE FROM `oc_layout_module` WHERE `code` = 'd_blog_module_date';
DELETE FROM `oc_layout_module` WHERE `code` = 'd_blog_module_search';

INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1000', '13', 'd_blog_module_search', 'content_bottom', '0');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1001', '104', 'd_blog_module_popular_posts.801', 'column_right', '0');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1002', '101', 'd_blog_module_category', 'column_right', '0');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1003', '101', 'd_blog_module_popular_posts.801', 'column_right', '1');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1004', '101', 'd_blog_module_latest_posts.800', 'column_right', '2');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1005', '101', 'd_blog_module_date', 'column_right', '3');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1006', '101', 'd_blog_module_tags', 'column_right', '4');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1008', '100', 'd_blog_module_popular_posts.801', 'column_right', '2');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1009', '102', 'd_blog_module_tags', 'column_right', '1');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1010', '100', 'd_blog_module_related_post', 'content_bottom', '1');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1011', '100', 'd_blog_module_latest_posts.800', 'column_right', '1');
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES ('1012', '100', 'd_blog_module_related_product', 'content_bottom', '2');