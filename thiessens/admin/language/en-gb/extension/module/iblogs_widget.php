<?php
include('iblogs.php');

$_['heading_title']               = 'iBlog 5 Widget';

$_['text_add_widget']             = 'Add Widget';
$_['text_edit_widget']            = 'Edit Widget';

// Form
$_['entry_placeholder']           = 'Placeholder';

// Side Information
$_['info_form_widget']            = array(
  '<b>Name</b> used to identify widget in admin modules.',
  '<b>Title</b> used as widget header at front site. Empty value to disable widget header.',
);
$_['info_form_widget_type']       = array_merge(
  $_['info_form_widget'],
  array(
    '<b>Type</b> information:
    <ul class="isl-list" style="font-size:12px">
      <li><code>Search</code>Search input.</li>
      <li><code>Category</code>Category listing.</li>
      <li><code>Post Tabs</code>Show recent, popular and tags post in tabs.</li>
      <li><code>Post Recent</code>Recent post listing.</li>
      <li><code>Post Popular</code>Popular post (last 60 days) listing.</li>
      <li><code>Post Tags</code>Tags post listing.</li>
    </ul>
    ',
  )
);
$_['info_form_widget_setting']    = array(
  'In <b>Custom CSS</b> use unique class to provide specific style modification:
  <ul class="isl-list" style="font-size:12px">
    <li>Per widget type <code>.iblogs-widget-{type}</code>, where {type} is the widget type value.</li>
    <li>Per single widget <code>.iblogs-widget-{x}</code>, where {x} is the widget ID.</li>
  </ul>'
);

// Notification
$_['error_permission']            = 'Warning: You do not have permission to modify module ' . $_['heading_title'] . '!';
$_['error_name']                  = 'Name must be between 3-64 characters.';
