<?php
/*
Plugin Name: Qiniu Cloud Storage
Version: 1.0.0
Description: Store uploaded photos in Qiniu Cloud Storage and serve them from Qiniu.
Has Settings: True
*/

if (!defined('PHPWG_ROOT_PATH'))
  die('Hacking attempt!');

define('QINIU_STORAGE_DIR', basename(dirname(__FILE__)));
include_once(PHPWG_PLUGINS_PATH . QINIU_STORAGE_DIR . '/include/functions.inc.php');

add_event_handler('loc_end_add_uploaded_file', 'qiniu_storage_upload', EVENT_HANDLER_PRIORITY_NEUTRAL, 1);
add_event_handler('get_src_image_url', 'qiniu_storage_src_url', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);
add_event_handler('get_derivative_url', 'qiniu_storage_derivative_url', EVENT_HANDLER_PRIORITY_NEUTRAL, 4);
?>
