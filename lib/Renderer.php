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

    private $dataStack;

    public function __construct()
    {
        $this->sourceDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/source/';
        $this->hl = new HeadLink();
        $this->hs = new HeadScript($this->sourceDir);
        $this->escaper = new Escaper();
        $this->attribs = new HtmlAttributes($this->escaper);
        $this->dataStack = [];
    }

    public function run($__options)
    {
        if (!empty($__options['data'])) {
            $data = json_decode(base64_decode($__options['data']), true);
            $this->dataStack[] = $data;
            extract($data);
        }
        if (!empty($__options['string'])) {
            eval('?>' . base64_decode($__options['string']));
        } elseif (!empty($__options['file'])) {
            include $__options['file'];
        }
    }

    public function render($__name, $values = [])
    {
        $this->dataStack[] = $values;
        extract($values);
        ob_start();
        $includeReturn = include $this->sourceDir . $__name;
        $content = ob_get_clean();
        array_pop($this->dataStack);
        if ($includeReturn === false && empty($content)) {
            throw new \Exception(sprintf(
                '%s: Unable to render template "%s"; file include failed',
                __METHOD__,
                $__name
            ));
        }

        return $content;
    }

    public function escapeHtml($value)
    {
        return $this->escaper->escapeHtml($value);
    }

    public function escapeHtmlAttr($value)
    {
        return $this->escaper->escapeHtmlAttr($value);
    }

    public function headLink(...$args)
    {
        return ($this->hl)(...$args);
    }

    public function htmlAttributes(...$args)
    {
        return ($this->attribs)(...$args);
    }

    public function headScript(...$args)
    {
        return ($this->hs)(...$args);
    }

    public function imageLink($image)
    {
        return '/images/' . $image;
    }

    public function inlineScript(...$args)
    {
        return ($this->hs)(...$args);
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

    public function transEsc($str, $tokens = [], $default = null)
    {
        return $this->escapeHtml($this->translate($str));
    }

    public function translate($str, $tokens = [], $default = null)
    {
        return end($this->dataStack)[$str] ?? $str;
    }

    public function url($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        return $name;
    }
}
