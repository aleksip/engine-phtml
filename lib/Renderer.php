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

    private $globalData;

    public function __construct()
    {
        $this->sourceDir = dirname(dirname(dirname(__DIR__))) . '/source/';
        $this->hl = new HeadLink();
        $this->hs = new HeadScript($this->sourceDir);
        $this->escaper = new Escaper();
        $this->attribs = new HtmlAttributes($this->escaper);
        $this->dataStack = [];
        $this->globalData = [];
    }

    public function run($__options)
    {
        $globalData = [];
        if (!empty($__options['data'])) {
            $data = json_decode(base64_decode($__options['data']), true);
            $this->dataStack[] = $data;
            $globalData = $data;
            extract($data);
        }
        if (!empty($__options['jsonFileData']) && !empty($globalData)) {
            $jsonFileData = json_decode(base64_decode($__options['jsonFileData']), true);
            $this->globalData = array_diff_key($globalData, $jsonFileData);
        }
        if (!empty($__options['string'])) {
            eval('?>' . base64_decode($__options['string']));
        } elseif (!empty($__options['file'])) {
            include $__options['file'];
        }
        echo $this->hl;
        echo $this->hs;
    }

    public function render($__name, $__values = [])
    {
        $__name = $this->expandName($__name);
        $__templateData = array_merge($this->globalData, $__values);
        $this->dataStack[] = $__templateData;
        extract($__templateData);
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

    public function basePath($file)
    {
        return '/' . $file;
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
        return $this->escapeHtml($this->translate($str, $tokens, $default));
    }

    public function transEscAttr($str, $tokens = [], $default = null)
    {
        return $this->escapeHtmlAttr($this->translate($str, $tokens, $default));
    }

    public function translate($str, $tokens = [], $default = null)
    {
        return end($this->dataStack)[$str] ?? $str;
    }

    public function url($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        return $name;
    }

    protected function expandName(string $name): string
    {
        if (!empty($name)
            && strpos($name, 'components/') === 0
            && substr($name, -6) !== '.phtml'
        ) {
            $parts = explode('/', $name);
            $last = array_pop($parts);
            if ($last !== array_pop($parts)) {
                $name = $name . '/' . $last . '.phtml';
            }
        }
        return $name;
    }
}
