<?php

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
    include $autoload;
}
include 'Renderer.php';
include 'Escaper.php';
include 'HtmlAttributesSet.php';
include 'HtmlAttributes.php';
include 'HeadLink.php';
include 'HeadScript.php';

$renderer = new Renderer();
$renderer->run(getopt('s::f::d::j::', ['string::', 'file::', 'data::', 'jsonFileData::']));
