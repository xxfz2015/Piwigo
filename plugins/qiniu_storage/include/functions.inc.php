<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

function qiniu_storage_get_conf()
{
  global $conf;
  if (!isset($conf['qiniu_storage']))
    return null;
  if (is_string($conf['qiniu_storage']))
    $conf['qiniu_storage'] = unserialize($conf['qiniu_storage']);
  return $conf['qiniu_storage'];
}

function qiniu_storage_upload($image_infos)
{
  $config = qiniu_storage_get_conf();
  if (empty($config) || empty($config['access_key']) || empty($config['bucket']))
    return;

  $path = PHPWG_ROOT_PATH . $image_infos['path'];
  qiniu_storage_send_file($path, $image_infos['md5sum'], $config);
}

function qiniu_storage_send_file($file, $key, $config)
{
  $deadline = time() + 3600;
  $policy = json_encode(array('scope'=>$config['bucket'].':'.$key, 'deadline'=>$deadline));
  $encoded = str_replace(array('+','/'), array('-','_'), base64_encode($policy));
  $sign = hash_hmac('sha1', $encoded, $config['secret_key'], true);
  $encoded_sign = str_replace(array('+','/'), array('-','_'), base64_encode($sign));
  $token = $config['access_key'].':'.$encoded_sign.':'.$encoded;

  $ch = curl_init('https://upload.qiniup.com');
  $post = array('token'=>$token, 'key'=>$key, 'file'=>new CURLFile($file));
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_exec($ch);
  curl_close($ch);
}

function qiniu_storage_src_url($url, $src_image)
{
  $config = qiniu_storage_get_conf();
  if (empty($config) || empty($config['domain']))
    return $url;
  return 'https://'.$config['domain'].'/'.$src_image->rel_path;
}

function qiniu_storage_derivative_url($url, $params, $src_image, $rel_url)
{
  $config = qiniu_storage_get_conf();
  if (empty($config) || empty($config['domain']))
    return $url;
  return 'https://'.$config['domain'].'/'.$rel_url;
}
?>
