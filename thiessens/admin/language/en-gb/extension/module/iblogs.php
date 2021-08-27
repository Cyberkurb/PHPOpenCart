<?php
$_['heading_title']               = 'iBlog 5';

$_['text_settings']               = 'Settings';
$_['text_dashboard']              = 'Dashboard';
$_['text_post']                   = 'Post';
$_['text_category']               = 'Category';
$_['text_migrate']                = 'Migrate';
$_['text_support']                = 'Support';
$_['text_posts']                  = 'Posts';
$_['text_categories']             = 'Categories';
$_['text_tags']                   = 'Tags';
$_['text_blog_listing']           = 'Blog Listing';
$_['text_post_view']              = 'Post View';

$_['text_information']            = 'Information';
$_['text_modules']                = 'Modules';
$_['text_default']                = 'Default';
$_['text_none']                   = '-- None --';
$_['text_save']                   = 'Save';
$_['text_close']                  = 'Close';
$_['text_cancel']                 = 'Cancel';
$_['text_no']                     = 'No';
$_['text_yes']                    = 'Yes';
$_['text_enabled']                = 'Enabled';
$_['text_disabled']               = 'Disabled';
$_['text_publish']                = 'Publish';
$_['text_draft']                  = 'Draft';

$_['text_add']                    = 'Add';
$_['text_edit']                   = 'Edit';
$_['text_add_post']               = 'Add Post';
$_['text_edit_post']              = 'Edit Post';
$_['text_add_category']           = 'Add Category';
$_['text_edit_category']          = 'Edit Category';

$_['text_id']                     = 'ID';
$_['text_title']                  = 'Title';
$_['text_excerpt']                = 'Excerpt';
$_['text_updated']                = 'updated';
$_['text_publish_unpublish']      = 'Publish - Unpublish';
$_['text_order']                  = 'Order';
$_['text_post_count']             = 'Post Count';
$_['text_status']                 = 'Status';
$_['text_action']                 = 'Action';
$_['text_grid']                   = 'Grid';
$_['text_list']                   = 'List';
$_['text_leading_list']           = 'Leading List';
$_['text_width']                  = 'Width';
$_['text_height']                 = 'Height';

$_['text_total_view']             = 'Total View';
$_['text_view_more']              = 'View more..';
$_['text_popular_60days']         = 'Popular Post in 60 Days';
$_['text_recent_update_post']     = 'Recently Updated Post';

// Form
$_['entry_title']                 = $_['text_title'];
$_['entry_content']               = 'Content';
$_['entry_excerpt']               = $_['text_excerpt'];
$_['entry_global_status']         = 'Global Status';
$_['entry_status']                = $_['text_status'];
$_['entry_meta_title']            = 'Meta Title';
$_['entry_meta_desc']             = 'Meta Description';
$_['entry_meta_keywords']         = 'Meta Keywords';
$_['entry_navbar']                = 'Main Navigation';
$_['entry_nav_order']             = 'Main Nav Order';
$_['entry_listing_layout']        = 'Listing Layout';
$_['entry_post_info_format']      = 'Post Info Format';
$_['entry_image_size']            = 'Image Dimension';
$_['entry_main_image']            = 'Main Image';
$_['entry_comment']               = 'Comment';

$_['entry_name']                  = 'Name';
$_['entry_author']                = 'Author';
$_['entry_featured']              = 'Featured';
$_['entry_category']              = 'Category';
$_['entry_categories']            = 'Categories';
$_['entry_parent']                = 'Parent';
$_['entry_canonical']             = 'Canonical';
$_['entry_image']                 = 'Image';
$_['entry_seo_options']           = 'SEO Options';
$_['entry_url_alias']             = 'URL Alias';
$_['entry_limit']                 = 'Limit';
$_['entry_sort_order']            = 'Sort Order';
$_['entry_publish']               = 'Publish Date';
$_['entry_unpublish']             = 'Unpublish Date';
$_['entry_custom_css']            = 'Custom CSS';

$_['text_custom']                 = 'Custom';
$_['text_by_post_tags']           = 'Post Tags (Meta Keywords)';
$_['text_custom_select']          = 'Custom Selection';

// Side Information
$_['info_setting']                = array(
  'Global Status controls the extension\'s state. If you need to completely disable the module, you will need to disable and refresh the modifications. '
);
$_['info_setting_blog_listing']   = array(
  'This section controls the visual layout of the post listing for the iblog\'s home page and global value for the category\'s post listing. ',
  '<b>Post Info Format</b> is tagline information between post title and content. Available shortcodes:
  <ul class="isl-list" style="font-size:12px">
    <li><code>{author}</code> author name.</li>
    <li><code>{date}</code> show date in format "month day, year".</li>
    <li><code>{category}</code> shot categories post belong to.</li>
  </ul>',
  'In <b>Custom CSS</b> use unique class to provide specific style modification:
  <ul class="isl-list" style="font-size:12px">
    <li>Main post listing <code>.iblogs-home-listing</code>.</li>
    <li>All category post listing <code>.iblogs-category</code> (including main listing).</li>
    <li>Per category post listing <code>.iblogs-category-{x}</code>, where {x} is the category ID.</li>
  </ul>'
);
$_['info_setting_post_view']      = array(
  'This section controls the visual layout of all posts at the frontend of your store.',
  '<b>Post Info Format</b> is tagline information between post title and content. Available shortcodes:
  <ul class="isl-list" style="font-size:12px">
    <li><code>{author}</code> author name.</li>
    <li><code>{date}</code> show date in format "month day, year".</li>
    <li><code>{category}</code> shot categories post belong to.</li>
  </ul>',
  'In <b>Custom CSS</b> use unique class to provide specific style modification:
  <ul class="isl-list" style="font-size:12px">
    <li>All post view <code>.iblogs-post</code>.</li>
    <li>Per post view <code>.iblogs-post-{x}</code>, where {x} is the post ID.</li>
  </ul>'
);
$_['info_list_post']              = array();
$_['info_list_category']          = array();

$_['info_form_post_content']      = array(
  'When <b>Meta Title</b> is empty, then <b>Title</b> will be used as default fallback.',
  '<b>Meta Keywords</b> used as Meta Keywords, SEO Keywords and Post Tags.'
);
$_['info_form_post_setting']      = array(
  '<b>URL Alias</b> automatically convert into lowercase and replace space with dash.',
  '<b>Featured</b> will put post at top post listing.'
);
$_['info_form_category_content']  = array(
  'If Meta Title is empty, then Title will be used as default fallback.',
);
$_['info_form_category_setting']  = array(
  '<b>URL Alias</b> automatically convert into lowercase and replace space with dash.',
);

// Tab Migrate
$_['text_migrate_db_found']       = 'iBlog Legacy database is found';
$_['text_migrate_to']             = 'Do you want to migrate the data to ' . $_['heading_title'] . '?';
$_['text_migrate_info']           = '* Migrated posts and categories will be added as new entries.';

// Tab Support
$_['text_your_license']           = 'Your license';
$_['text_please_enter_the_code']  = 'Please enter your product purchase license code';
$_['text_activate_license']       = 'Activate License';
$_['text_not_having_a_license']   = "Don't have a code? Get it from here.";
$_['text_license_holder']         = 'License Holder';
$_['text_registered_domains']     = 'Registered domains';
$_['text_expires_on']             = 'License Expires on';
$_['text_valid_license']          = 'VALID LICENSE';
$_['text_get_support']            = 'Get Support';
$_['text_community']              = 'Community';
$_['text_ask_our_community']      = 'Ask the community about your issue on the iSenseLabs forum.';
$_['text_tickets']                = 'Tickets';
$_['text_open_a_ticket']          = 'Want to communicate one-to-one with our tech people? Then open a support ticket.';
$_['text_pre_sale']               = 'Pre-sale';
$_['text_pre_sale_desc']          = 'Have a brilliant idea for your webstore? Our team of top-notch developers can make it real.';
$_['text_browse_forums']          = 'Browse forums';
$_['text_open_ticket_for_real']   = 'Open a ticket';
$_['text_bump_the_sales']         = 'Bump the sales';

// Notification
$_['text_loading']                = 'Loading..';
$_['text_processing']             = 'Processing..';
$_['text_success']                = 'Success: You have modified module iBlog!';
$_['text_success_save']           = 'Successfully saved!';
$_['text_no_data']                = 'No data available!';
$_['text_success_migrate']        = 'Success: Migrating {pid} posts and {cid} categories.';

$_['error_general']               = 'Error occured, please try again later!';
$_['error_permission']            = 'Warning: You do not have permission to modify module iBlog!';
$_['error_form']                  = 'Error found, please check all required form!';
$_['error_title']                 = 'Title must be between 3-225 characters.';
$_['error_excerpt']               = 'Excerpt must be more than 3 characters.';
$_['error_content']               = 'Content must be more than 3 characters.';
$_['error_alias']                 = 'URL alias already in use!';
$_['error_delete_category']       = 'Warning: Category cannot be deleted as it is currently assigned to Posts!';
