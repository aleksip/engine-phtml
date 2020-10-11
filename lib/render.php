<?php

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
    include $autoload;
}
include 'Renderer.php';
include 'HeadScript.php';

$renderer = new Renderer();
$renderer->run(getopt(null, ['string::', 'file::', 'data::']));
