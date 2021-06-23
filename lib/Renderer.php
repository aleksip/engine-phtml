<?php

use Laminas\Escaper\Escaper;
use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\HeadScript;
use Laminas\View\Helper\HtmlAttributes;

class Renderer
{
    private $sourceDir;

    private $hl;

    private $hs;

    private $escaper;

    private $attribs;

    public function __construct()
    {
        $this->sourceDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/source/';
        $this->hl = new HeadLink();
        $this->hs = new HeadScript($this->sourceDir);
        $this->escaper = new Escaper();
        $this->attribs = new HtmlAttributes($this->escaper);
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

    public function htmlAttributes(...$args)
    {
        return ($this->attribs)(...$args);
    }

    public function headLink(...$args)
    {
        return ($this->hl)(...$args);
    }

    public function imageLink($image)
    {
        return '/images/' . $image;
    }

    public function transEsc($str, $tokens = [], $default = null)
    {
        return $this->escaper->escapeHtml($str);
    }

    public function jsTranslations()
    {
        return new class
        {
            public function addStrings(array $new)
            {
            }
        };
    }
}
