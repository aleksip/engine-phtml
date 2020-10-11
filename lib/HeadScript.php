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

    protected $sourceDir;

    protected $output;

    public function __construct($sourceDir)
    {
        $this->sourceDir = $sourceDir;
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
        return $this->setFile($src, $type, $attrs);
    }

    public function setFile($src, $type = 'text/javascript', $attrs = [])
    {
        ob_start();
        echo "<script type=\"$type\">\n";
        include $this->sourceDir . $src;
        echo "</script>\n";
        $script = ob_get_clean();
        return $this->output = $script;
    }

    public function appendScript($script, $type = 'text/javascript', $attrs = [])
    {
        return $this->setScript($script, $type, $attrs);
    }

    public function setScript($script, $type = 'text/javascript', $attrs = [])
    {
        $this->output = "<script type=\"$type\">\n$script\n</script>\n";
        return $this->output;
    }
}
