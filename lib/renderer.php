<?php
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
  include $autoload;
}
$options = getopt(null, ['file:', 'data::']);
if (!empty($options['data'])) {
  $data = json_decode(base64_decode($options['data']), true);
  extract($data);
}
include $options['file'];
