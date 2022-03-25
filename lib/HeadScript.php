<?php

namespace Laminas\View\Helper;

class HeadScript
{
    /**
     * Script type constants
     *
     * @const string
     */
    const FILE   = 'FILE';
    const SCRIPT = 'SCRIPT';

    protected $output;

    public function __construct()
    {
        $this->output = '';
    }

    /**
     * Return headScript object
     *
     * Returns headScript helper object; optionally, allows specifying a script
     * or script file to include.
     *
     * @param  string $mode      Script or file
     * @param  string $spec      Script/url
     * @param  string $placement Append, prepend, or set
     * @param  array  $attrs     Array of script attributes
     * @param  string $type      Script type and/or array of script attributes
     * @return HeadScript
     */
    public function __invoke(
        $mode = self::FILE,
        $spec = null,
        $placement = 'APPEND',
        array $attrs = [],
        $type = 'text/javascript'
    ) {
        if ((null !== $spec) && is_string($spec)) {
            $action    = ucfirst(strtolower($mode));
            $placement = strtolower($placement);
            switch ($placement) {
                case 'set':
                case 'prepend':
                case 'append':
                    $action = $placement . $action;
                    break;
                default:
                    $action = 'append' . $action;
                    break;
            }
            $this->$action($spec, $type, $attrs);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->output;
    }

    public function appendFile($src, $type = 'text/javascript', $attrs = [])
    {
        $this->output .= $this->getFile($src, $type, $attrs);
        return $this;
    }

    public function prependFile($src, $type = 'text/javascript', $attrs = [])
    {
        $this->output = $this->getFile($src, $type, $attrs) . $this->output;
        return $this;
    }

    public function setFile($src, $type = 'text/javascript', $attrs = [])
    {
        $this->output = $this->getFile($src, $type, $attrs);
        return $this;
    }

    public function appendScript($script, $type = 'text/javascript', $attrs = [])
    {
        $this->output .= $this->getScript($script, $type, $attrs);
        return $this;
    }

    public function prependScript($script, $type = 'text/javascript', $attrs = [])
    {
        $this->output = $this->getScript($script, $type, $attrs) . $this->output;
        return $this;
    }

    public function setScript($script, $type = 'text/javascript', $attrs = [])
    {
        $this->output = $this->getScript($script, $type, $attrs);
        return $this;
    }

    protected function getFile($src, $type, $attrs)
    {
      return "<script src=\"$src\"></script>\n";
    }

    protected function getScript($script, $type, $attrs)
    {
        return "<script type=\"$type\">\n$script\n</script>\n";
    }
}
