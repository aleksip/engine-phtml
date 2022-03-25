<?php

namespace Laminas\View\Helper;

class HeadLink
{
    protected $output;

    public function __construct()
    {
        $this->output = '';
    }

    public function __invoke(array $attributes = null, $placement = null)
    {
        return $this;
    }

    public function __toString()
    {
        return $this->output;
    }

    public function appendStylesheet($href, $media = 'screen', $conditionalStylesheet = '', $extras = [])
    {
        $this->output .= $this->getLink($href, $media, $conditionalStylesheet, $extras);
        return $this;
    }

    public function prependStylesheet($href, $media = 'screen', $conditionalStylesheet = '', $extras = [])
    {
        $this->output = $this->getLink($href, $media, $conditionalStylesheet, $extras) . $this->output;
        return $this;
    }

    protected function getLink($href, $media = 'screen', $conditionalStylesheet = '', $extras = [])
    {
        return "<link href=\"$href\" rel=\"stylesheet\">\n";
    }
}
