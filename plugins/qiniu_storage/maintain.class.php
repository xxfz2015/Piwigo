<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

class qiniu_storage_maintain extends PluginMaintain
{
  function install($plugin_version, &$errors=array())
  {
    global $conf;
    if (!isset($conf['qiniu_storage']))
    {
      $config = array(
        'access_key' => '',
        'secret_key' => '',
        'bucket'     => '',
        'domain'     => ''
      );
      conf_update_param('qiniu_storage', serialize($config));
    }
  }

  function uninstall()
  {
    conf_delete_param('qiniu_storage');
  }
}
?>
