<?php

use Laminas\View\Helper\HeadScript;

class Renderer
{
    private $sourceDir;

    private $hs;

    public function __construct()
    {
        $this->sourceDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/source/';
        $this->hs = new HeadScript($this->sourceDir);
    }

    public function run($__options)
    {
        if (!empty($__options['data'])) {
            $data = json_decode(base64_decode($__options['data']), true);
            extract($data);
        }
        if (!empty($__options['string'])) {
            eval('?>' . base64_decode($__options['string']));
        } elseif (!empty($__options['file'])) {
            include $__options['file'];
        }
    }

    public function render($__name, $values = null)
    {
        if (!empty($values)) {
            extract($values);
        }
        ob_start();
        $includeReturn = include $this->sourceDir . $__name;
        $content = ob_get_clean();
        if ($includeReturn === false && empty($content)) {
            throw new \Exception(sprintf(
                '%s: Unable to render template "%s"; file include failed',
                __METHOD__,
                $__name
            ));
        }

        return $content;
    }

    public function inlineScript(...$args)
    {
        return ($this->hs)(...$args);
    }

    public function headScript(...$args)
    {
        return ($this->hs)(...$args);
    }
}
