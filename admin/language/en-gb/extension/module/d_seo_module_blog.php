<?php
// Heading
$_['heading_title']								= '<span style="color:#449DD0; font-weight:bold">SEO Module Blog</span><span style="font-size:0.9em; color:#999"> by <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Dreamvention" style="font-size:1em; color:#999" target="_blank">Dreamvention</a></span>';
$_['heading_title_main']						= 'SEO Module Blog';

// Text
$_['text_edit']									= 'Edit SEO Module Blog';
$_['text_install']								= 'Install SEO Module Blog';
$_['text_modules']								= 'Modules';
$_['text_settings']								= 'Settings';
$_['text_instructions']							= 'Instructions';
$_['text_basic_settings'] 						= 'Basic Settings';
$_['text_blog_category'] 						= 'Blog Category';
$_['text_blog_post'] 							= 'Blog Post';
$_['text_blog_author'] 							= 'Blog Author';
$_['text_blog_search'] 							= 'Blog Search';
$_['text_module']								= 'Module';
$_['text_all_stores']			 				= 'All Stores';
$_['text_all_languages']						= 'All Languages';
$_['text_yes'] 									= 'Yes';
$_['text_no'] 									= 'No';
$_['text_enabled']								= 'Enabled';
$_['text_disabled']								= 'Disabled';
$_['text_uninstall_confirm']					= 'After deinstallation is compleate the SEO Module Blog will delete all additional fields in the blog post and blog category that have been added after installation. Are you sure you want to uninstall the SEO Module Blog? ';
$_['text_info_setting_blog_category']			= '<h4>What\'s this for?</h4><p>SEO Module Blog will replace your current Blog Category Title with Custom Blog Category Titles on the store page. But to do this, the module needs to know the location of these tags in your HTML. The same goes for the Custom Blog Category Image tag. The module uses Simple HTML DOM extension to trace the location.</p> <p>Opencart has a great variety of templates. There are no standards of selectors for title and image tags. Some developers add different types of tags with different ids or classes. Therefore, you are given the opportunity to add the required selectors yourself. Exmaple: <code>#content h1</code> will search for a tag with id=content inside which it will look for a h1 tag</p>';
$_['text_info_setting_blog_post']				= '<h4>What\'s this for?</h4><p>Just like the blog category, the SEO Module Blog will replace your current Blog Post Title with Custom Blog Post Titles on the store page.</p>';
$_['text_info_setting_blog_author']				= '<h4>What\'s this for?</h4><p>Just like the blog category, the SEO Module Blog will replace your current Blog Author Name with Custom Blog Author Names on the store page.</p>';
$_['text_instructions_full'] 					= '
<div class="row">
	<div class="col-sm-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="#vtab_instruction_install"  data-toggle="tab">Installation and Updating</a></li>
			<li><a href="#vtab_instruction_setting" data-toggle="tab">Settings</a></li>
		</ul>
	</div>
	<div class="col-sm-10">
		<div class="tab-content">
			<div id="vtab_instruction_install" class="tab-pane active">
				<div class="tab-body">
					<h3>Installation</h3>
					<ol>
						<li>Unzip distribution file.</li>
						<li>Upload everything from the folder <code>UPLOAD</code> into the root folder of you shop.</li>
						<li>Goto admin of your shop and navigate to extensions -> modules -> SEO Module Blog.</li>
						<li>Click install button.</li>
					</ol>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>Our installation process requires you to have access to the internet because we will install all the required dependencies before we install the module. Install SEO Module Blog is possible only after installing SEO Module.</p>
					</div>
					<div class="bs-callout bs-callout-warning">
						<h4>Warning!</h4>
						<p>If you get an error on this step, be sure to make you <code>DOWNLOAD</code> folder (usually in system folder of you shop) writable.</p>
					</div>
					<h3>Updating</h3>
					<ol>
						<li>Unzip distribution file.</li>
						<li>Upload everything from the folder <code>UPLOAD</code> into the root folder of you shop.</li>
						<li>Click overwrite for all files.</li>
					</ol>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>Although we follow strict standards that do not allow feature updates to cause a full reinstall of the module, still it may happen that major releases require you to uninstall/install the module again before new feature take place.</p>
					</div>
					<div class="bs-callout bs-callout-warning">
						<h4>Warning!</h4>
						<p>If you have made custom corrections to the code, your code will be rewritten and lost once you update the module.</p>
					</div>
				</div>
			</div>
			<div id="vtab_instruction_setting" class="tab-pane">
				<div class="tab-body">
					<h3>Basic Settings</h3>
					<p>Here you can:</p>
					<ol>
						<li>Enable/Disable SEO Module Blog on the pages of your shop by click Status.</li>
						<li>Uninstall SEO Module Blog.</li>
					</ol>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>After uninstalling of SEO Module Blog will delete all additional fields in the blog post and blog category that have been added after installation.</p>
					</div>
					<h3>Blog Category</h3>
					<p>After installing of SEO Module Blog in the admin panel of Opencart in the blog category on the tab "General" will appear new fields:</p>
					<ol>
						<li><strong>Custom Title 1</strong> is multilingual field, which allows you to specify the title of the blog category on the blog category page different from the page title.</li>
						<li><strong>Custom Title 2</strong> is multilingual field, which allows you to specify the title of the blog category on the blog category page different from the page title.</li>
						<li><strong>Custom Image Title</strong> is multilingual field, which allows you specify the attribute "title" of main image of the blog category on the blog category page different from the page title.</li>
						<li><strong>Custom Image Alt</strong> is multilingual field, which allows you specify the attribute "alt" of main image of the blog category on the blog category page different from the page title.</li>
						<li><strong>Meta Robots</strong> is multilingual field, which allows the robot to determine, whether it is possible to index this blog category page and to use for search the links provided on the page.</li>
						<li><strong>Target Keyword</strong> is multilingual field, which is important for SEO and must be unique for each page and language.</li>
						<li><strong>SEO Keyword</strong> is multilingual field, which defines the url of the page and must be unique for each page.</li>
					</ol>
					<p>Opencart has a great variety of templates. There are no standards of selectors for title and image tags of blog category. Some developers add different types of tags with different ids or classes. Therefore, in the settings of this module, you are given the opportunity themselves to add the required selectors:</p>
					<ol>
						<li><strong>Custom Title 1 tag selector</strong></li>
						<li><strong>Custom Title 2 tag selector</strong></li>
						<li><strong>Custom Image tag selector</strong></li>
					</ol>
					<p>Selector can be represented as class or ID of the tag. We set selectors for the default template of Opencart.</p>
					<p>Also, here you can set the following fields:</p>
					<ol>
						<li><strong>Unique URL</strong> increasess the uniqueness URL, by redirecting and removing all unnecessary data from URL on the blog category page.</li>
						<li><strong>Exception Data</strong> is comma separated list of exception parameters, that should remain in URL on the blog category page when set Unique URL. Everything else will redirect to the Unique URL.</li>
						<li><strong>Short URL</strong> removes the blog categories and subcategories in links to the blog categories.</li>
					</ol>
					<h3>Blog Post</h3>
					<p>After installing of SEO Module Blog in the admin panel of Opencart in the blog post on the tab "General" will appear new fields:</p>
					<ol>
						<li><strong>Custom Title 1</strong> is multilingual field, which allows you to specify the title of the blog post on the blog post page different from the page title.</li>
						<li><strong>Custom Title 2</strong> is multilingual field, which allows you to specify the title of the blog post on the blog post page different from the page title.</li>
						<li><strong>Custom Image Title</strong> is multilingual field, which allows you specify the attribute title of main image of the blog post on the blog post page different from the page title.</li>
						<li><strong>Custom Image Alt</strong> is multilingual field, which allows you specify the attribute alt of main image of the blog post on the blog post page different from the page title.</li>
						<li><strong>Meta Robots</strong> is multilingual field, which allows the robot to determine, whether it is possible to index this blog post page and to use for search the links provided on the page.</li>
						<li><strong>Target Keyword</strong> is multilingual field, which is important for SEO and must be unique for each page and language.</li>
						<li><strong>SEO Keyword</strong> is multilingual field, which defines the url of the page and must be unique for each page.</li>
					</ol>
					<p>Also, in the blog post on the tab "Links" will appear field "Category Path", which allows you to specify the unique path to the blog post in blog post links and breadcrumbs.</p>
					<p>Opencart has a great variety of templates. There are no standards of selectors for title and image tags of blog post. Some developers add different types of tags with different ids or classes. Therefore, in the settings of this module, you are given the opportunity themselves to add the required selectors:</p>
					<ol>
						<li><strong>Custom Title 1 tag selector</strong></li>
						<li><strong>Custom Title 2 tag selector</strong></li>
						<li><strong>Custom Image tag selector</strong></li>
					</ol>
					<p>Selector can be represented as class or ID of the tag. We set selectors for the default template of Opencart.</p>
					<p>Also, here you can set the following fields:</p>
					<ol>
						<li><strong>Unique URL</strong> increasess the uniqueness URL, by redirecting and removing all unnecessary data from URL on the blog post page.</li>
						<li><strong>Exception Data</strong> is comma separated list of exception parameters, that should remain in URL on the blog post page when set Unique URL. Everything else will redirect to the Unique URL.</li>
						<li><strong>Short URL</strong> removes the blog categories and subcategories in links to the blog posts.</li>
					</ol>
					<h3>Blog Author</h3>
					<p>After installing of SEO Module Blog in the admin panel of Opencart in the blog author on the tab "General" will appear new fields:</p>
					<ol>
						<li><strong>Meta Tag Title</strong> is multilingual field, which allows you to specify the meta tag title of blog author.</li>
						<li><strong>Meta Tag Description</strong> is multilingual field, which allows you to specify the meta tag description of blog author.</li>
						<li><strong>Meta Tag Keywords</strong> is multilingual field, which allows you to specify the meta tag keywords of blog author.</li>
						<li><strong>Custom Title 1</strong> is multilingual field, which allows you to specify the title of the blog author on the blog author page different from the page title.</li>
						<li><strong>Custom Title 2</strong> is multilingual field, which allows you to specify the title of the blog author on the blog author page different from the page title.</li>
						<li><strong>Custom Image Title</strong> is multilingual field, which allows you specify the attribute title of main image of the blog author on the blog author page different from the page title.</li>
						<li><strong>Custom Image Alt</strong> is multilingual field, which allows you specify the attribute alt of main image of the blog author on the blog author page different from the page title.</li>
						<li><strong>Meta Robots</strong> is multilingual field, which allows the robot to determine, whether it is possible to index this blog author page and to use for search the links provided on the page.</li>
						<li><strong>Target Keyword</strong> is multilingual field, which is important for SEO and must be unique for each page and language.</li>
						<li><strong>SEO Keyword</strong> is multilingual field, which defines the url of the page and must be unique for each page.</li>
					</ol>
					<p>Opencart has a great variety of templates. There are no standards of selectors for title and image tags of blog author. Some developers add different types of tags with different ids or classes. Therefore, in the settings of this module, you are given the opportunity themselves to add the required selectors:</p>
					<ol>
						<li><strong>Custom Title 1 tag selector</strong></li>
						<li><strong>Custom Title 2 tag selector</strong></li>
						<li><strong>Custom Image tag selector</strong></li>
					</ol>
					<p>Selector can be represented as class or ID of the tag. We set selectors for the default template of Opencart.</p>
					<p>Also, here you can set the following fields:</p>
					<ol>
						<li><strong>Unique URL</strong> increasess the uniqueness URL, by redirecting and removing all unnecessary data from URL on the blog author page.</li>
						<li><strong>Exception Data</strong> is comma separated list of exception parameters, that should remain in URL on the blog author page when set Unique URL. Everything else will redirect to the Unique URL.</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>';
$_['text_not_found'] = '
<div class="jumbotron">
	<h1>Please install Shopunity</h1>
	<p>Before you can use this module you will need to install Shopunity. Simply download the archive for your version of opencart and install it view Extension Installer or unzip the archive and upload all the files into your root folder from the UPLOAD folder.</p>
	<p><a class="btn btn-primary btn-lg" href="https://shopunity.net/download" target="_blank">Download</a></p>
</div>';

// Features
$_['text_meta_robots_field_per_page']								= 'Meta Robots field per page';
$_['text_edit_meta_information_for_all_pages']						= 'Edit Meta information for all pages';
$_['text_unique_urls_for_all_pages']								= 'Unique urls for all pages';
$_['text_long_or_short_urls_for_blog_category_and_post']			= 'Long or short urls for Blog Category and Post';
$_['text_seo_module_blog_api']										= 'SEO Module Blog API';
$_['text_multi_language_urls_for_all_pages']						= 'Multi Language Urls for all pages';
$_['text_set_canonicals_for_all_pages']								= 'Set Canonicals for all pages';
$_['text_pagination_canonicals']									= 'Pagination Canonicals';
$_['text_pagination_links_next_and_prev']							= 'Pagination links next and prev';
$_['text_alternate_hreflang_tag']									= 'Alternate hreflang tag';

// Entry
$_['entry_status']								= 'Status';
$_['entry_uninstall']							= 'Uninstall Module';
$_['entry_unique_url']        					= 'Unique URL';
$_['entry_exception_data']        				= 'Exception Data';
$_['entry_short_url']        					= 'Short URL';
$_['entry_custom_title_1_class']				= 'Custom Title 1 tag selector';
$_['entry_custom_title_2_class']				= 'Custom Title 2 tag selector';
$_['entry_custom_image_class']					= 'Custom Image tag selector';

// Button
$_['button_save'] 								= 'Save';
$_['button_save_and_stay'] 						= 'Save and Stay';
$_['button_cancel'] 							= 'Cancel';
$_['button_install'] 							= 'Install';
$_['button_uninstall'] 							= 'Uninstall';

// Help
$_['help_install']								= '<br/>
<h1>Install SEO Module Blog now</h1>
<br/>
<p>The Seo Module Blog is built especially for the Blog module. It provides multilingual urls, adds support for metas and seo management. It beautifully integrates into the SEO Module ecosystem and provides you with an outstanding SEO support for your blog! Click install! </p>
<br/>';
$_['help_unique_url']							= 'Unique URL increasess the uniqueness URL, by redirecting and removing all unnecessary data from URL on the page.';
$_['help_exception_data']						= 'Exception Data is comma separated list of exception parameters, that should remain in URL on the page when set Unique URL. Everything else will redirect to the Unique URL.';
$_['help_short_url']							= 'Short URL removes the categories and subcategories from URL on the page.';

// Success
$_['success_save']								= 'Success: You have modified SEO Module Blog!';
$_['success_install']							= 'Success: You have installed SEO Module Blog!';
$_['success_uninstall']							= 'Success: You have uninstalled SEO Module Blog!';

// Error
$_['error_warning']          					= 'Warning: Please check the form carefully for errors!';
$_['error_permission']    						= 'Warning: You do not have permission to modify module SEO Module Blog!';
$_['error_installed']							= 'Warning: You can not install this module because it is already installed!';
$_['error_dependence_d_blog_module']    		= 'Warning: You can not install this module until you install module Blog Module!';
$_['error_dependence_d_seo_module']    			= 'Warning: You can not install this module until you install module SEO Module!';

?>