<?php
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
  include $autoload;
}
$options = getopt(null, ['string::', 'file::', 'data::']);
if (!empty($options['data'])) {
  $data = json_decode(base64_decode($options['data']), true);
  extract($data);
}
if (!empty($options['string'])) {
  eval('?>' . base64_decode($options['string']));
}
elseif (!empty($options['file'])) {
  include $options['file'];
}