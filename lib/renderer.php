<?php
class Renderer
{
    public function run($options)
    {
        if (!empty($options['data'])) {
            $data = json_decode(base64_decode($options['data']), true);
            extract($data);
        }
        if (!empty($options['string'])) {
            eval('?>' . base64_decode($options['string']));
        } elseif (!empty($options['file'])) {
            include $options['file'];
        }
    }

    public function render($name, $values = null)
    {
        $patternsPath = dirname(dirname(dirname(dirname(__DIR__)))) . '/source/';
        if (!empty($values)) {
            extract($values);
        }
        include "$patternsPath/$name";
    }
}
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
    include $autoload;
}
$renderer = new Renderer();
$renderer->run(getopt(null, ['string::', 'file::', 'data::']));
